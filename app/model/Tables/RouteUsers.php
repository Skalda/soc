<?php

namespace Model;

class RouteUsers extends Table
{
	/** @var string */
	protected $tableName = 'routeUsers';
	
	public function addRouteUser($route, $user) {
	    $data = $this->getBothWays($route, $user);
	    $this->getTable()->insert(array(
			'route' => $route,
			'user' => $user,
	    ));
	    //$this->notifications->addNotification($user, 'User:', array('id' => $route), "Uživatel {person} váš požádat o přátelství", array($route));
	    //vytvorit hlasku ze systemu, ze byla pridana trasa
	}
	
	public function removeRoutesUser($route, $user) {
	    $data = $this->getTable()->where('routes_id', $route);
	    if(count($data)>1) $data->where('users_id', $user)->delete();
	}

	public function getRoutesUsers($id) {
	    $table = $this->getTable();
	    $res = $table->where('routes_id', $id);
	    $sharers = array();
	    foreach($res as $val) {
			$sharers[] = $val['users_id'];
	    }
	    return $this->connection->table('users')->where('id', $sharers);
	}
}