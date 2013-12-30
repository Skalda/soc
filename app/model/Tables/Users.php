<?php

namespace Model;

use Nette\Security;

/**
 * @author Tomáš Skalický
 */
class Users extends Table implements Security\IAuthenticator
{
	/** @var string */
	protected $tableName = 'users';
	
	const NON_FRIEND = 0,
		FRIEND = 1,
		AUTHOR = 2;
	
	const ALL = 0,
		FRIENDS_ONLY = 1;
	
	/**
	 * Creates new user
	 * 
	 * @param string $email email
	 * @param string $password password
	 * @throws \Model\DuplicateEntryException
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addUser($email, $password) {
		$salt = sha1(time().$email."ad~632as@!oa");
		$password = $this->getPasswordHash($password, $salt);
			$user = $this->createRow(array(
			    'email' => $email,
			    'password' => $password,
			    'salt' => $salt,
			));
			return $user;
	}
	
	/**
	 * Changes user's password
	 * 
	 * @param int $id id of user
	 * @param string $oldPassword old password
	 * @param string $newPassword new password
	 * @return \Nette\Database\Table\ActiveRow updated row
	 * @throws WrongPasswordException
	 */
	public function changePassword($id, $oldPassword, $newPassword) {
		$row = $this->find($id);
		if($row->password !== $this->getPasswordHash($oldPassword, $row->salt)) {
		    throw new WrongPasswordException('Staré heslo není správné.');
		}
		$password = $this->getPasswordHash($newPassword, $row->salt);
		return $row->update(array(
		    'password' => $password,
		));
	}
	
	/**
	 * Changes user's detail
	 * 
	 * @param int $id id of user
	 * @param string $name name
	 * @param string $surname surname
	 * @param string(male|female) $sex sex
	 * @param city $city city
	 * @return \Nette\Database\Table\ActiveRow updated row
	 */
	public function modifyUser($id, $name, $surname, $sex, $city) {
	    $row = $this->find($id);
	    return $row->update(array(
			'name' => $name,
			'surname' => $surname,
			'sex' => $sex,
			'city' => $city,
			'filled_data' => 1
	    ));
	}
	
	/**
	 * Changes user's profile picture
	 * 
	 * @param int $id id of user
	 * @param string $pic profile picture filename
	 * @return \Nette\Database\Table\ActiveRow updated row
	 */
	public function changeProfilePic($id, $pic) {
	    $row = $this->find($id);
	    return $row->update(array(
			'profilpic' => $pic,
	    ));
	}
	
	/**
	 * Gets info about user
	 * 
	 * @param type $id id of user
	 * @return array User's info
	 */
	public function getUser($id) {
	    $row = $this->find($id);
	    if(!$row) {
			return null;
	    }
	    $data = $row->toArray();
	    unset($data['password'], $data['salt']);
	    return $data;
	}
	
	/**
	 * Finds all user which contains words in $q
	 * 
	 * @param string $q search query 
	 * @return \Nette\Database\Table\Selection Found users
	 */
	public function searchUser($q) {
	    $words = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $q, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	    $results = $this->getTable();
	    foreach($words as $w) {
			$query = "%".$w."%";
			$results->where('email LIKE ? OR name LIKE ? OR surname LIKE ?', array($query, $query ,$query));
	    }
	    return $results;
	}
	
	/**
	 * Gets user's wallposts
	 * 
	 * @param int $id id of user
	 * @param int $viewer viewer's friend status
	 * @return \Nette\Database\Table\Selection User's wallposts
	 */
	public function getUsersWall($id, $viewer = self::NON_FRIEND) {
	    $user = $this->find($id);
	    $wallpost = $user->related('wall')->order('date DESC');
	    if($viewer == self::NON_FRIEND) {
			$wallpost = $wallpost->where(array('privacy' => 0));
	    }
	    return $wallpost;
	}
	
	/**
	 * Gets wallposts of user's friends
	 * 
	 * @param array $friends array of user's friends ids
	 * @return \Nette\Database\Table\Selection Friends wallposts
	 */
	public function getFriendsWallPosts($friends) {
	    return $this->connection->table('wall')->where('user_id', $friends)->order('date DESC');
	}
	
	/**
	 * Gets single wallpost
	 * 
	 * @param int $id id of wallpost
	 * @param int $viewer viewer's friend status
	 * @return \Nette\Database\Table\ActiveRow Wallpost
	 */
	public function getSingleWallPost($id, $viewer = self::NON_FRIEND) {
	    $wallpost = $this->connection->table('wall')->find($id);
	    if($viewer == self::NON_FRIEND) {
			$wallpost = $wallpost->where(array('privacy' => 0));
	    }
	    return $wallpost;
	}
	
	/**
	 * Adds wallpost to user's wall
	 * 
	 * @param int $id id of user
	 * @param string $content content of wallpost
	 * @param int $privacy privacy settings
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addWallPost($id, $content, $privacy = SELF::ALL) {
	    $wall = $this->connection->table('wall');
	    return $wall->insert(array(
			'user_id' => $id,
			'content' => $content,
			'privacy' => $privacy,
			'date' => new \Nette\Database\SqlLiteral('NOW()'),
	    ));
	}
	
	/**
	 * Adds comment to users's wallpost
	 * 
	 * @param int $userId id of comment's author
	 * @param int $wallpostId id of wallpost
	 * @param string $content content of the comment
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addComment($userId, $wallpostId, $content) {
	    $comments = $this->connection->table('comments');
	    return $comments->insert(array(
		'user_id' => $userId,
		'wall_id' => $wallpostId,
		'content' => $content,
		'date' => new \Nette\Database\SqlLiteral('NOW()'),
	    ));
	}
	
	/**
	 * Performs an authentication.
	 * 
	 * @param array $credentials array of users credentials array(email, password)
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
		$row = $this->findOneBy(array('email' => $email));

		if (!$row) {
			throw new Security\AuthenticationException('Uživatelské jméno neexistuje.', self::IDENTITY_NOT_FOUND);
		}
		
		if ($row->password !== $this->getPasswordHash($password, $row->salt)) {
			throw new Security\AuthenticationException('Heslo není správné.', self::INVALID_CREDENTIAL);
		}

		$arr = $row->toArray();
		unset($arr['password'], $arr['salt']);
		return new Security\Identity($row->id, 1, $arr);
	}
	
	/**
	 * Creates password hash
	 * 
	 * @param string $password password
	 * @param string $salt salt
	 * @return string Hashed password
	 */
	public function getPasswordHash ($password, $salt) {
		return sha1($password . "are14%!u@#raia" . $salt);
	}
        
}
