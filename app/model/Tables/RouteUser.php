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
	
	public function removeRoutesUser($route, $user) {
		//overit, zda neni poslednim
	    $data = $this->getBothWays($route, $user);
	    $data->delete();
	}

	public function getUsersRoutes($user) {
	}

	public function getRoutesUsers($route) {
	}
}