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
	
	public function addUser($email, $password, $name, $surname, $sex, $city) {
		$salt = sha1(time().$email."ad~632as@!oa");
		$password = $this->getPasswordHash($password, $salt);
			$user = $this->createRow(array(
			    'email' => $email,
			    'password' => $password,
			    'salt' => $salt,
			    'name' => $name,
			    'surname' => $surname,
			    'sex' => $sex,
			    'city' => $city,
			));
			return $user;
	}
	
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
	
	public function modifyUser($id, $name, $surname, $sex, $city) {
	    $row = $this->find($id);
	    return $row->update(array(
		'name' => $name,
		'surname' => $surname,
		'sex' => $sex,
		'city' => $city,
	    ));
	}
	
	public function changeProfilPic($id, $pic) {
	    $row = $this->find($id);
	    return $row->update(array(
		'profilpic' => $pic,
	    ));
	}
	
	public function getUser($id) {
	    $row = $this->find($id);
	    $data = $row->toArray();
	    unset($data['password'], $data['salt']);
	    return $data;
	}
	
	/**
	 * Performs an authentication.
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
	
	public function getPasswordHash ($password, $salt) {
		return sha1($password . "are14%!u@#raia" . $salt);
	}
        
}

