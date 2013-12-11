<?php

namespace Model;

class RouteUser extends Table
{
	/** @var string */
	protected $tableName = 'routeUser';
	
	public function addRouteUser($route, $user) {
	    $data = $this->getBothWays($route, $user);
	    $this->getTable()->insert(array(
		'route' => $route,
		'user' => $user,
	    ));
	    //$this->notifications->addNotification($user, 'User:', array('id' => $route), "Uživatel {person} váš požádat o přátelství", array($route));
	    //vytvorit hlasku ze systemu, ze byla pridana trasa
	}
	
	public function removeRouteUser($route, $user) {
		//overit, zda neni poslednim
	    $data = $this->getBothWays($route, $user);
	    $data->delete();
	}

	public function getRoutes($user) {
	}

	public function getUsers($route) {
	}
	
	/*public function getUsersFriends($id) {
	    $table = $this->getTable();
	    $res = $table->where('route = ? OR user = ?', array($id, $id));
	    $friends = array();
	    foreach($res as $val) {
		$friends[] = ($val['route'] == $id)?$val['user']:$val['route'];
	    }
	    return $this->connection->table('users')->where('id', $friends);
	}
	
	private function getBothWays($route, $user) {
	    $table = $this->getTable();
	    return $table->where('(route = ? AND user = ?) OR (user = ? AND route = ?)', array($route, $user, $route, $user));
	}
	
	private function getOneWay($route, $user) {
	    $table = $this->getTable();
	    return $table->where('(route = ? AND user = ?)', array($route, $user));
	}
}*/