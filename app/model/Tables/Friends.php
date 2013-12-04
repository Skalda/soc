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
	
	public function removeFriend($from, $to) {
	    $data = $this->getBothWays($from, $to);
	    if($data->count() == 0) {
		throw new FriendRequestDoesNotExistException();
	    }
	    $data->delete();
	}
	
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
	
	public function getUsersFriends($id) {
	    $table = $this->getTable();
	    $res = $table->where('from = ? OR to = ?', array($id, $id));
	    $friends = array();
	    foreach($res as $val) {
		$friends[] = ($val['from'] == $id)?$val['to']:$val['from'];
	    }
	    return $this->connection->table('users')->where('id', $friends);
	}
	
	private function getBothWays($from, $to) {
	    $table = $this->getTable();
	    return $table->where('(from = ? AND to = ?) OR (to = ? AND from = ?)', array($from, $to, $from, $to));
	}
	
	private function getOneWay($from, $to) {
	    $table = $this->getTable();
	    return $table->where('(from = ? AND to = ?)', array($from, $to));
	}
}


class FriendRequestAlreadyPendingException extends \Exception {}
class FriendRequestDoesNotExistException extends \Exception {}