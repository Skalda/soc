<?php

namespace Model;

class Entries extends Table
{
	protected $tableName = 'entries';
	
	public function addEntry($routeId, $location, $timestamp, $event, $user_id, $odometer, $velocity, $consumption, $fuel_remaining, $altitude, $engine_temp, $engine_rpm, $throttle) {
		$this->createRow(array(
			'routes_id' => $routeId,
			'location' => $location,
			'timestamp' => $timestamp,
			'event' => $event,
			'user_id' => $user_id,
			'odometer' => $odometer,
			'velocity' => $velocity,
			'consumption' => $consumption,
			'fuel_remaining' => $fuel_remaining,
			'altitude' => $altitude,
			'engine_temp' => $engine_temp,
			'engine_rpm' => $engine_rpm,
			'throttle' => $throttle,		
		));
	}

	public function getRoutesEntries($routeId){
		return $this->findBy(array('routes_id' => $routeId))->order('timestamp ASC');
	}

	public function getEntry($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}
}