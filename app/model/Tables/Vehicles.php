<?php

namespace Model;

class Vehicles extends Table
{
	/** @var string */
	protected $tableName = 'vehicles';
	
	public function addVehicle($name) {
		$this->createRow(array(
			'users_id' => $userid,
			));
	}

	public function getVehicles($userId){
		$rows = $this->findBy(array('users_id' => $userId))->order('name DESC');
	    $veh = array();
	    foreach($rows as $key=>$val) {
			//$veh[$key]['name'] = $name;
	    }
	    
	    return $veh;
	}

	public function modifyVehicle($id, $info, $registration_number, $brand, $model, $production_year, $color,
		$type, $motor_count, $wheel_count, $propeller_count, $seats_count, $window_count, $fuel_type,
		$engine_capacity, $engine_power, $mileage, $status) {
		$row = $this->find($id);
		return $row->update(array(
			'info' => $info,
			'registration_number' => $registration_number,
			'brand' => $brand,
			'model' => $model,
			'production_year' => $production_year,
			'color' => $color,
			'type' => $type,
			'motor_count' => $motor_count,
			'wheel_count' => $wheel_count,
			'propeller_count' => $propeller_count,
			'seats_count' => $seats_count,
			'window_count' => $window_count,
			'fuel_type' => $fuel_type,
			'engine_capacity' => $engine_capacity,
			'engine_power' => $engine_power,
			'mileage' => $mileage,
			'status' => $status,
			));
	}

	public function changeProfilePic($id, $pic) {
		$row = $this->find($id);
		return $row->update(array(
			'profilpic' => $pic,
			));
	}

	public function getVehicle($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}
	
	public function getVehicleWall($id, $viewer = self::NON_FRIEND) {
		$vehicle = $this->find($id);
		$wallpost = $vehicle->related('wall')->order('date DESC');
		if($viewer == self::NON_FRIEND) {
			$wallpost = $wallpost->where(array('privacy' => 0));
		}
		return $wallpost;
	}
	
	public function getSingleWallPost($id, $viewer = self::NON_FRIEND) {
		$wallpost = $this->connection->table('wall')->find($id);
		if($viewer == self::NON_FRIEND) {
			$wallpost = $wallpost->where(array('privacy' => 0));
		}
		return $wallpost;
	}
	
	public function addWallPost($id, $content, $privacy = SELF::ALL) {
		$wall = $this->connection->table('wall');
		return $wall->insert(array(
			'user_id' => $id,
			'content' => $content,
			'privacy' => $privacy,
			'date' => new \Nette\Database\SqlLiteral('NOW()'),
			));
	}
	
	public function addComment($userId, $wallpostId, $content) {
		$comments = $this->connection->table('comments');
		return $comments->insert(array(
			'user_id' => $userId,
			'wall_id' => $wallpostId,
			'content' => $content,
			'date' => new \Nette\Database\SqlLiteral('NOW()'),
			));
	}
}