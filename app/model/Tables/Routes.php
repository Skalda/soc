<?php

namespace Model;

class Routes extends Table
{
	protected $tableName = 'routes';
	
	/**
	 * Creates new route
	 * 
	 * @param int $vehicleId id of vehicle
	 * @param int $userId id of user
	 * @param int $unit_id id of board unit
	 * @param type $secret_key secret key
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addRoute($vehicleId, $userId, $unit_id, $secret_key) {
		return $this->createRow(array(
			'vehicles_id' => $vehicleId,
			'users_id' => $userId,
			'unit_id' => $unit_id,
			'secret_key' => $secret_key,
		));
	}

	/**
	 * Add information to route about length and duration
	 * 
	 * @param type $id id of route
	 * @param type $start time of route's start
	 * @param type $end time of route's end
	 * @param type $length length of route
	 * @return void
	 */
	public function initRoute($id, $start, $end, $length) {
		$row = $this->find($id);
		$starttime = \Nette\DateTime::from($start);
		$endtime = \Nette\DateTime::from($end);
		$duration = $endtime->diff($starttime);
		
		$row->update(array(
			'start_time' => $starttime,
			'duration' => $duration->format('%h:%i:%s'),
			'length' => $length,
		));
		$vehicle = $this->connection->table('vehicles')->where('unit_id', $row->unit_id);
		$vehicle->update(array(
			'mileage' => $vehicle->fetch()->mileage + $length,
		));
	}

	/**
	 * Gets all vehicle's route
	 * 
	 * @param int $vehicleId id of vehicle
	 * @return \Nette\Database\Table\Selection Vehicle's routes
	 */
	public function getVehiclesRoutes($vehicleId){
		return $this->findBy(array('vehicles_id' => $vehicleId))->order('start_time ASC');
	}

	/**
	 * Gets all users's route
	 * 
	 * @param int $userId id of user
	 * @return \Nette\Database\Table\Selection Users's routes
	 */
	public function getUsersRoutes($userId){
		$routeUsers = $this->connection->table('route_users')->where('users_id', $userId);
	    $helper = array();
	    foreach($routeUsers as $row) $helper[] = $row['routes_id'];
	    $results1 = iterator_to_array($this->getTable()->where('id', $helper));
	    $results2 = iterator_to_array($this->getTable()->where('users_id', $userId));
	    return array_unique(array_merge($results1, $results2));
	}

	/**
	 * Changes info about route
	 * 
	 * @param int $id id of route
	 * @param string $name name of route
	 * @param int $vehicles_id id of used vehicle
	 * @param array $sharers array of ids of users who share route
	 * @return \Nette\Database\Table\ActiveRow updated row
	 */
	public function modifyRoute($id, $name, $vehicles_id, $sharers) {
		$row = $this->find($id)->update(array(
			'name' => $name,
			'vehicles_id' => $vehicles_id,
		));
		$this->connection->table('route_users')->where('routes_id', $id)->delete();
		foreach($sharers as $sharer) $this->connection->table('route_users')->insert(array(
			'routes_id' => $id,
			'users_id' => $sharer,
	    ));
		return $row;
	}

	/**
	 * Gets route's waypoints
	 * 
	 * @param int $id id of route
	 * @return \Nette\Database\Table\Selection Route's waypoints
	 */
	public function getRoutePoints($id) {
	    return $this->connection->table('entries')->select("x(location) AS x, y(location) AS y")->where('routes_id', $id);
	}

	/**
	 * Gets single route
	 * 
	 * @param int $id id of route
	 * @return array Route
	 */
	
	public function getRoute($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $row->toArray();
	}
}