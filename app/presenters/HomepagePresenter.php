<?php

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	/** @var Model\Users */
	public $users;
	
	public function injectUsers(Model\Users $users) {
	    $this->users = $users;
	}
	
	public function renderDefault()
	{
		$this->template->userslist = $this->users->findAll();
	}

}
