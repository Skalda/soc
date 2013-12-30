<?php

namespace Model;

class Entries extends Table
{
	protected $tableName = 'entries';
	
	/**
	 * Creates new Entry
	 * 
	 * @param int $routeId id of route
	 * @param array $location location point
	 * @param string $timestamp timestamp of entry
	 * @param string $event event
	 * @param int $user_id id of driver
	 * @param double $odometer odometer
	 * @param double $velocity velocity
	 * @param double $consumption consumption
	 * @param double $fuel_remaining remaining fuel
	 * @param double $altitude altitude
	 * @param double $engine_temp engine temp
	 * @param double $engine_rpm engine rpm
	 * @param double $throttle throttle
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addEntry($routeId, $location, $timestamp, $event, $user_id, $odometer, $velocity, $consumption, $fuel_remaining, $altitude, $engine_temp, $engine_rpm, $throttle) {
		$date = \Nette\DateTime::from($timestamp);
			
		return $this->createRow(array(
			'routes_id' => $routeId,
			'location' => new \Nette\Database\SqlLiteral('POINT('.implode(", ", $location).')'),
			'timestamp' => $date,
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

	/**
	 * Get entries by route's ID
	 * 
	 * @param int $routeId id of route
	 * @return \Nette\Database\Table\Selection Entries
	 */
	public function getRoutesEntries($routeId){
		return $this->findBy(array('routes_id' => $routeId))->order('timestamp ASC');
	}

	/**
	 * Get single entry by its ID
	 * 
	 * @param int $id id of entry
	 * @return \Nette\Database\Table\ActiveRow Entry
	 */
	
	public function getEntry($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}
}
