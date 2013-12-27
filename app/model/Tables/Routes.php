<?php

namespace Model;

class Routes extends Table
{
	protected $tableName = 'routes';
	
	public function addRoute($vehicleId, $userId, $unit_id, $secret_key) {
		return $this->createRow(array(
			'vehicles_id' => $vehicleId,
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
		return $this->findBy(array('vehicles_id' => $vehicleId))->order('start_time ASC');
	}

	public function getUsersRoutes($userId){
		return $this->findBy(array('users_id' => $userId))->order('start_time ASC');
	}

	public function modifyRoute($id, $name, $vehicles_id, $sharers) {
		$row = $this->find($id);
		$row->update(array(
			'name' => $name,
			'vehicles_id' => $vehicles_id,
		));
		$this->connection->table('routeUsers')->where('routes_id = ?', $id)->delete();
		foreach($sharers as $sharer) $this->connection->table('routeUsers')->addRouteUser($id, $sharer);
		return $row;
	}

	public function getRoute($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $row->toArray();
	}
}