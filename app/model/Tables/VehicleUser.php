<?php

namespace Model;

class VehicleUser extends Table
{
	/** @var string */
	protected $tableName = 'vehicleUser';
	
	public function addVehicleUser($vehicle, $user) {
	    $data = $this->getBothWays($vehicle, $user);
	    $this->getTable()->insert(array(
		'vehicle' => $vehicle,
		'user' => $user,
	    ));
	    //$this->notifications->addNotification($user, 'User:', array('id' => $vehicle), "Uživatel {person} váš požádat o přátelství", array($vehicle));
	    //zmenit na notification na pridani vozidla (i cizi mi muze pridat)
	}
	
	public function removeVehicleUser($vehicle, $user) {
	    $data = $this->getBothWays($vehicle, $user);
	    $data->delete();
	}

	public function getVehicles($user) {
	}

	public function getUsers($vehicle) {
	}
	
	/*public function getUsersFriends($id) {
	    $table = $this->getTable();
	    $res = $table->where('vehicle = ? OR user = ?', array($id, $id));
	    $friends = array();
	    foreach($res as $val) {
		$friends[] = ($val['vehicle'] == $id)?$val['user']:$val['vehicle'];
	    }
	    return $this->connection->table('users')->where('id', $friends);
	}
	
	private function getBothWays($vehicle, $user) {
	    $table = $this->getTable();
	    return $table->where('(vehicle = ? AND user = ?) OR (user = ? AND vehicle = ?)', array($vehicle, $user, $vehicle, $user));
	}
	
	private function getOneWay($vehicle, $user) {
	    $table = $this->getTable();
	    return $table->where('(vehicle = ? AND user = ?)', array($vehicle, $user));
	}
}*/