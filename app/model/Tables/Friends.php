<?php

namespace Model;


/**
 * @author Tomáš Skalický
 */
class Friends extends Table
{
	/** @var string */
	protected $tableName = 'friends';
	
	/** @var Notifications */
	protected $notifications;


	/** friendship states */
	const NONE = 'none',
		PENDING_FROM = 'pending_from',
		PENDING_TO = 'pending_to',
		ACCEPTED = 'accepted';
	
	
	
	public function __construct(\Nette\Database\Connection $db, Notifications $notifications) {
	    parent::__construct($db);
	    $this->notifications = $notifications;
	}
	
	
	/**
	 * Add connection (not accepted) between users who want to be friends
	 * 
	 * @param int $from id of user sending friendship request
	 * @param int $to id of user receiving friendship request
	 * @throws FriendRequestAlreadyPendingException thrown when request is already pending
	 * @return void
	 */
	public function addFriend($from, $to) {
	    $data = $this->getBothWays($from, $to);
	    if($data->count() > 0) {
		throw new FriendRequestAlreadyPendingException();
	    }
	    $this->getTable()->insert(array(
		'from' => $from,
		'to' => $to,
	    ));
	    $this->notifications->addNotification($to, 'User:', array('id' => $from), "Uživatel {person} váš požádat o přátelství", array($from));
	}
	
	
	/**
	 * Change state of friendship request to accepted
	 * 
	 * @param type $from id of user who is accepting friendship request
	 * @param type $to id of user who sent friendship request
	 * @throws FriendRequestDoesNotExistException thrown when friendship request doesn't exist
	 * @return void
	 */
	public function acceptFriend($from, $to) {
	    $data = $this->getOneWay($to, $from);
	    if($data->count() == 0) {
		throw new FriendRequestDoesNotExistException();
	    }
	    $data->update(array(
		'accepted' => 1,
	    ));
	    $this->notifications->addNotification($to, 'User:', array('id' => $from), "Uživatel {person} potvrdil vaší žádost o přátelství", array($from));
	}
	
	/**
	 * Remove friendship connection
	 * 
	 * @param type $from id of first user
	 * @param type $to id of second user
	 * @throws FriendRequestDoesNotExistException thrown when friendship request doesn't exist
	 * @return void
	 */
	public function removeFriend($from, $to) {
	    $data = $this->getBothWays($from, $to);
	    if($data->count() == 0) {
		throw new FriendRequestDoesNotExistException();
	    }
	    $data->delete();
	}
	
	
	/**
	 * Return friendship state between two users
	 * 
	 * @param type $from id of first user
	 * @param type $to id of second user
	 * @return string friendship state
	 */
	public function getFriendState($from, $to) {
	    $data = $this->getBothWays($from, $to);
	    if($data->count() == 0) {
		return self::NONE;
	    }
	    $fetch = $data->fetch();
	    if($fetch['accepted'] == 1) {
		return self::ACCEPTED;
	    }
	    if($fetch['from'] == $from) {
		return self::PENDING_FROM;
	    }
	    if($fetch['to'] == $from) {
		return self::PENDING_TO;
	    }
	}
	
	/**
	 * Return all user's friends 
	 * 
	 * @param type $id id of user
	 * @return \Nette\Database\Table\Selection friends
	 */
	public function getUsersFriends($id) {
	    $table = $this->getTable();
	    $res = $table->where('from = ? OR to = ?', array($id, $id));
	    $friends = array();
	    foreach($res as $val) {
		$friends[] = ($val['from'] == $id)?$val['to']:$val['from'];
	    }
	    return $this->connection->table('users')->where('id', $friends);
	}
	
	/**
	 * Return all friendship connections between users (does not matter who sent the request)
	 * 
	 * @param type $from id of first user
	 * @param type $to id of second user
	 * @return \Nette\Database\Table\Selection friendship connections
	 */
	private function getBothWays($from, $to) {
	    $table = $this->getTable();
	    return $table->where('(from = ? AND to = ?) OR (to = ? AND from = ?)', array($from, $to, $from, $to));
	}
	
	/**
	 * Return all friendship connections between users (does matter who sent the request)
	 * 
	 * @param type $from id of first user
	 * @param type $to id of second user
	 * @return \Nette\Database\Table\Selection friendship connections
	 */
	private function getOneWay($from, $to) {
	    $table = $this->getTable();
	    return $table->where('(from = ? AND to = ?)', array($from, $to));
	}
}


class FriendRequestAlreadyPendingException extends \Exception {}
class FriendRequestDoesNotExistException extends \Exception {}