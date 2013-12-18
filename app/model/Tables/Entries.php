<?php

namespace Model;

class Entries extends Table
{
	protected $tableName = 'entries';
	
	public function addEntry() {
		$this->createRow(array(
			
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