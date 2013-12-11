<?php

class SearchPresenter extends BasePresenter
{
	/** @var Model\Users */
	public $users;
	
	public function injectUsers(Model\Users $users) {
	    $this->users = $users;
	}
	
	public function renderDefault($q)
	{
	    $this->template->results = $this->users->searchUser($q);
	}

}
