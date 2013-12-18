<?php

namespace Model;

class Routes extends Table
{
	protected $tableName = 'routes';
	
	public function addRoute($userId, $unit_id, $secret_key) {
		return $this->createRow(array(
			'users_id' => $userId,
			'unit_id' => $unit_id,
			'secret_key' => $secret_key,
		));
	}

	public function initRoute($id, $duration, $length) {
		$row = $this->find($id)->update(array(
			'duration' => $duration,
			'length' => $length,
		));
	}

	public function getVehiclesRoutes($vehicleId){
		return $this->findBy(array('vehicles_id' => $vehicleId))->order('timestamp ASC');
	}

	public function getUsersRoutes($userId){
		return $this->findBy(array('users_id' => $userId))->order('timestamp ASC');
	}

	public function modifyRoute($id, $users_id, $vehicles_id, $name) {
		$row = $this->find($id);
		return $row->update(array(
			'users_id' => $users_id,
			'vehicles_id' => $vehicles_id,
			'name' => $name,
			));
	}

	public function getRoute($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}

	public function showRoute($id) {
		//zobrazit route na mape
	}
}
