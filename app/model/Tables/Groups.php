<?php

namespace Model;


/**
 * @author Tomáš Skalický
 */
class Groups extends Table
{
	/** @var string */
	protected $tableName = 'groups';
	
	
	public function addGroup($user_id, $name, $desc) {
		$row = $this->createRow(array(
		    'user_id' => $user_id,
		    'name' => $name,
		    'desc' => $desc,
		));
		$this->addMember($row->id, $user_id);
		return $row;
	}
	
	public function modifyGroup($id, $name, $desc) {
		return $this->find($id)->update(array(
		    'name' => $name,
		    'desc' => $desc,
		));
	}
	
	public function searchGroups($q) {
	    $words = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $q, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	    $results = $this->getTable();
	    foreach($words as $w) {
		$query = "%".$w."%";
		$results->where('name LIKE ? OR desc LIKE ?', array($query, $query));
	    }
	    return $results;
	}
	
	public function isMember($group_id, $user_id) {
	    $row = $this->connection->table('groups_members')->where(array('group_id' => $group_id, 'user_id' => $user_id));
	    return $row->count()>0;
	    
	}
	
	public function addMember($group_id, $user_id) {
	    if(!$this->isMember($group_id, $user_id)) {
		return $this->connection->table('groups_members')->insert(array(
		    'group_id' => $group_id,
		    'user_id' => $user_id,
		));
	    }
	}
	
	public function removeMember($group_id, $user_id) {
	    if($this->isMember($group_id, $user_id)) {
		$this->getMembers($group_id)->where(array('user_id'=>$user_id))->delete();
	    }
	}
	
	public function getMembers($group_id) {
	    $row = $this->connection->table('groups_members')->where(array('group_id' => $group_id));
	    return $row;
	}
	
	public function getGroupWall($group_id) {
	    $row = $this->connection->table('groups_wall')->where(array('group_id' => $group_id));
	    return $row;
	}
        
	public function addWallPost($group_id, $user_id, $content) {
	    return $this->connection->table('groups_wall')->insert(array(
		'group_id' => $group_id,
		'user_id' => $user_id,
		'content' => $content,
		'date' => new \Nette\Database\SqlLiteral('NOW()'),
	    ));
	}
	
	public function addComment($wall_id, $user_id, $content) {
	    return $this->connection->table('groups_comments')->insert(array(
		'groups_wall_id' => $wall_id,
		'user_id' => $user_id,
		'content' => $content,
		'date' => new \Nette\Database\SqlLiteral('NOW()'),
	    ));
	}
	
}