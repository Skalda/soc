<?php

namespace Model;

class Vehicles extends Table
{
	/** @var string */
	protected $tableName = 'vehicles';
	
	public function addVehicle($id, $name, $info, $registration_number, $type, $status) {
		$this->createRow(array(
			'users_id' => $id,
			'name' => $name,
			'info' => $info,
			'registration_number' => $registration_number,
			'type' => $type,
			'status' => $status,
		));
	}

	public function getUsersVehicles($userId){
		return $this->findBy(array('users_id' => $userId))->order('name DESC');
	}

	public function modifyVehicle($id, $name, $info, $registration_number, $type, $status) {
		$row = $this->find($id);
		return $row->update(array(
			'name' => $name,
			'info' => $info,
			'registration_number' => $registration_number,
			'type' => $type,
			'status' => $status,
		));
	}

	public function changeVehiclePic($id, $pic) {
		$row = $this->find($id);
		return $row->update(array(
			'profilpic' => $pic,
		));
	}

	public function changeOwner($id, $userId) {
		return $this->find($id)->update(array(
			'users_id' => $userId,
		));
	}

	public function getVehicle($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}
}