<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var \Model\Notifications */
	public $notifications;

	public function injectNotifications(\Model\Notifications $notifications) {
	    $this->notifications = $notifications;
	}
	
	public function startup() {
	    parent::startup();
	    Panel\User::register()
		    ->addCredentials('user1@user.cz', 'user')
		    ->addCredentials('user2@user.cz', 'user')
		    ->addCredentials('user3@user.cz', 'user')
		    ->addCredentials('user4@user.cz', 'user')
		    ->addCredentials('user5@user.cz', 'user')
		    ->addCredentials('user6@user.cz', 'user');
	}
	
	protected function createComponentSearchForm() {
	    $form = new \Form\SearchForm();
	    $form->onSuccess[] = $this->searchFormSucceeded;
	    return $form;
	}
	
	public function searchFormSucceeded($form) {
	    $this->redirect('Search:', array('q' => $form->getValues()->search));
	}


	public function beforeRender() {
	    parent::beforeRender();
	    if($this->getUser()->isLoggedIn()) {
		$this->template->numNotif = $this->notifications->getUnseenNotificationsCount($this->getUser()->getId());
	    }
	}
}
