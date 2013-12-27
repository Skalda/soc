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
		$this->connection->table('vehicles')->where('unit_id', $row->unit_id)->mileage += $length;
	}

	public function getVehiclesRoutes($vehicleId){
		return $this->findBy(array('vehicles_id' => $vehicleId))->order('start_time ASC');
	}

	public function getUsersRoutes($userId){
		$routeUsers = $this->connection->table('routeUsers')->where('users_id', $userId);
	    $helper = array();
	    foreach($routeUsers as $row) $helper[] = $row['routes_id'];
	    $results1 = iterator_to_array($this->getTable()->where('id', $helper));
	    $results2 = iterator_to_array($this->getTable()->where('users_id', $userId));
	    return array_unique(array_merge($results1, $results2));
	}

	public function modifyRoute($id, $name, $vehicles_id, $sharers) {
		$row = $this->find($id)->update(array(
			'name' => $name,
			'vehicles_id' => $vehicles_id,
		));
		$this->connection->table('routeUsers')->where('routes_id', $id)->delete();
		foreach($sharers as $sharer) $this->connection->table('routeUsers')->insert(array(
			'routes_id' => $id,
			'users_id' => $sharer,
	    ));
		return $row;
	}

	public function getPath($id){
		$path = 'http://maps.googleapis.com/maps/api/staticmap?size=800x450&sensor=false&path=';
		$entries = $this->connection->table('entries')->select("*, x(location) AS xlocation, y(location) AS ylocation")->where('routes_id', $id);
		foreach($entries as $entry) $path .= $entry->xlocation . ',' . $entry->ylocation . '|';
		$path = substr("$path", 0, -1);
		return $path;
	}

	public function getRoute($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $row->toArray();
	}
}