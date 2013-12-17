<?php

class SearchPresenter extends BasePresenter
{
	/** @var Model\Users */
	public $users;
	
	/** @var \Model\Groups */
	public $groups;
	
	public function injectGroups(\Model\Groups $groups) {
		$this->groups = $groups;
	}
	
	public function injectUsers(Model\Users $users) {
	    $this->users = $users;
	}
	
	public function actionDefault($q)
	{
	    $this->redirect('users', array('q' => $q));
	}
	
	public function renderUsers($q)
	{
	    $this->template->results = $this->users->searchUser($q);
	    $this->template->q = $q;
	}
	
	public function renderGroups($q) {
	    $this->template->results = $this->groups->searchGroups($q);
	    $this->template->q = $q;
	}

}
