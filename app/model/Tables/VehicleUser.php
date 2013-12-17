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
}