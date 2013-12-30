<?php

namespace Model;


/**
 * @author Tomáš Skalický
 */
class Groups extends Table
{
	/** @var string */
	protected $tableName = 'groups';
	
	/**
	 * Creates new group
	 * 
	 * @param int $user_id owner id
	 * @param string $name name
	 * @param string $desc description
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addGroup($user_id, $name, $desc) {
		$row = $this->createRow(array(
		    'user_id' => $user_id,
		    'name' => $name,
		    'desc' => $desc,
		));
		$this->addMember($row->id, $user_id);
		return $row;
	}
	
	/**
	 * Changes info about group
	 * 
	 * @param int $id id of group
	 * @param string $name name
	 * @param string $desc description
	 * @return \Nette\Database\Table\ActiveRow updated row
	 */
	public function modifyGroup($id, $name, $desc) {
		return $this->find($id)->update(array(
		    'name' => $name,
		    'desc' => $desc,
		));
	}
	
	/**
	 * Finds all group which contains words in $q
	 * 
	 * @param string $q search query 
	 * @return \Nette\Database\Table\Selection Found groups
	 */
	public function searchGroups($q) {
	    $words = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $q, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	    $results = $this->getTable();
	    foreach($words as $w) {
			$query = "%".$w."%";
			$results->where('name LIKE ? OR desc LIKE ?', array($query, $query));
	    }
	    return $results;
	}
	
	/**
	 * Finds out if user is member of group
	 * 
	 * @param int $group_id id of group
	 * @param int $user_id id of user
	 * @return bool
	 */
	public function isMember($group_id, $user_id) {
	    $row = $this->connection->table('groups_members')->where(array('group_id' => $group_id, 'user_id' => $user_id));
	    return $row->count()>0;
	    
	}
	
	/**
	 * Makes user member of group
	 * 
	 * @param int $group_id id of group
	 * @param int $user_id id of user
	 * @return void
	 */
	public function addMember($group_id, $user_id) {
	    if(!$this->isMember($group_id, $user_id)) {
		$this->connection->table('groups_members')->insert(array(
		    'group_id' => $group_id,
		    'user_id' => $user_id,
		));
	    }
	}
	
	/**
	 * Removes user from group
	 * 
	 * @param int $group_id id of group
	 * @param int $user_id id of user
	 */
	public function removeMember($group_id, $user_id) {
	    if($this->isMember($group_id, $user_id)) {
		$this->getMembers($group_id)->where(array('user_id'=>$user_id))->delete();
	    }
	}
	
	/**
	 * Get members of group
	 * 
	 * @param int $group_id id of group
	 * @return \Nette\Database\Table\Selection Members of group
	 */
	public function getMembers($group_id) {
	    $row = $this->connection->table('groups_members')->where(array('group_id' => $group_id));
	    return $row;
	}
	
	/**
	 * Get wallposts
	 * 
	 * @param int $group_id id of group
	 * @return \Nette\Database\Table\Selection Wallposts
	 */
	public function getGroupWall($group_id) {
	    $row = $this->connection->table('groups_wall')->where(array('group_id' => $group_id));
	    return $row;
	}
        
	/**
	 * Adds new wallpost to group wall
	 * 
	 * @param int $group_id id of group
	 * @param int $user_id id of post's author
	 * @param string $content content of the post
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addWallPost($group_id, $user_id, $content) {
	    return $this->connection->table('groups_wall')->insert(array(
		'group_id' => $group_id,
		'user_id' => $user_id,
		'content' => $content,
		'date' => new \Nette\Database\SqlLiteral('NOW()'),
	    ));
	}
	
	/**
	 * Adds comment to group's wallpost
	 * 
	 * @param int $wall_id id of wallpost
	 * @param int $user_id id of comment's author
	 * @param string $content content of the comment
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addComment($wall_id, $user_id, $content) {
	    return $this->connection->table('groups_comments')->insert(array(
		'groups_wall_id' => $wall_id,
		'user_id' => $user_id,
		'content' => $content,
		'date' => new \Nette\Database\SqlLiteral('NOW()'),
	    ));
	}
	
}