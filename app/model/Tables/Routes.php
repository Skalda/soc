<?php

namespace Model;

class Routes extends Table
{
	/** @var string */
	protected $tableName = 'routes';
	
	/*public function addRoute($id, $name) {//nacist route z JSON
		$this->createRow(array(
			
			));
	}*/

	public function getRoutesByVehicle($vehicleId){
		$rows = $this->findBy(array('vehicles_id' => $vehicleId))->order('measured DESC');
	    $rou = array();
	    foreach($rows as $key=>$val) {
	    	$rou[$key]['measured'] = $val['measured'];
			$rou[$key]['name'] = $val['name'];
	    }
	    
	    return $rou;
	}

	public function getRoutesByUser($userId){
		$rows = $this->findBy(array('users_id' => $userId))->order('measured DESC');
	    $rou = array();
	    foreach($rows as $key=>$val) {
	    	$rou[$key]['measured'] = $val['measured'];
			$rou[$key]['name'] = $val['name'];
	    }
	    
	    return $rou;
	}

	public function modifyRoute($id, $users_id, $vehicles_id, $name, $measured) {
		$row = $this->find($id);
		return $row->update(array(
			'users_id' => $users_id,
			'vehicles_id' => $vehicles_id,
			'name' => $name,
			'measured' => $measured,
			));
	}

	public function getRoute($id) {
		$row = $this->find($id);
		if(!$row) {
			return null;
		}
		return $data = $row->toArray();
	}
}