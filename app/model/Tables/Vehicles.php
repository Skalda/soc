<?php

namespace Model;

class Vehicles extends Table
{
	/** @var string */
	protected $tableName = 'vehicles';
	
	/**
	 * Creates new vehicle
	 * 
	 * @param int $id id of owner
	 * @param string $unit_id id of board unit
	 * @param string $name name of vehicle
	 * @param string $info info about vehicle
	 * @param string $registration_number vehicle's registration number
	 * @param string $type vehicle's type
	 * @param string $status vehicle's status
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addVehicle($id, $unit_id, $name, $info, $registration_number, $type, $status) {
		return $this->createRow(array(
			'users_id' => $id,
			'name' => $name,
			'info' => $info,
			'registration_number' => $registration_number,
			'unit_id' => ($unit_id)?:NULL,
			'type' => $type,
			'status' => $status,
		));
	}

	/**
	 * Gets all user's vehicles
	 * 
	 * @param int $userId id of user
	 * @return \Nette\Database\Table\Selection User's vehicles
	 */
	public function getUsersVehicles($userId){
		return $this->findBy(array('users_id' => $userId))->order('name DESC');
	}

	/**
	 * Changes info about vehicle
	 * 
	 * @param type $id id of vehicle
	 * @param string $unit_id id of board unit
	 * @param string $name name of vehicle
	 * @param string $info info about vehicle
	 * @param string $registration_number vehicle's registration number
	 * @param string $type vehicle's type
	 * @param string $status vehicle's status
	 * @return \Nette\Database\Table\ActiveRow updated row
	 */
	public function modifyVehicle($id, $unit_id, $name, $info, $registration_number, $type, $status) {
		$row = $this->find($id);
		return $row->update(array(
			'name' => $name,
			'info' => $info,
			'registration_number' => $registration_number,
			'unit_id' => $unit_id,
			'type' => $type,
			'status' => $status,
		));
	}

	/**
	 * Change vehicle's picture
	 * 
	 * @param int $id id of vehicle
	 * @param string $pic profile picture filename
	 * @return type
	 */
	public function changeVehiclePic($id, $pic) {
		$row = $this->find($id);
		return $row->update(array(
			'profilpic' => $pic,
		));
	}

	/**
	 * Changes vehicle's owner
	 * 
	 * @param int $id id of vehicle
	 * @param int $userId id of new owner
	 * @return \Nette\Database\Table\ActiveRow updated row
	 */
	public function changeOwner($id, $userId) {
		return $this->find($id)->update(array(
			'users_id' => $userId,
		));
	}

	/**
	 * Gets single vehicle info
	 * 
	 * @param int $id id of vehicle
	 * @return \Nette\Database\Table\ActiveRow Vehicle info
	 */
	public function getVehicle($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}
}
