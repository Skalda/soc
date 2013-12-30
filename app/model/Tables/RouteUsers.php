<?php

namespace Model;

class RouteUsers extends Table
{
	/** @var string */
	protected $tableName = 'route_users';
	
	/**
	 * Gets users who share route 
	 * 
	 * @param int $id id of route
	 * @return \Nette\Database\Table\Selection Users who share route
	 */
	public function getRoutesUsers($id) {
	    $table = $this->getTable();
	    $res = $table->where('routes_id', $id);
	    $sharers = array();
	    foreach($res as $val) {
			$sharers[] = $val['users_id'];
	    }
	    return $this->connection->table('users')->where('id', $sharers);
	}
}