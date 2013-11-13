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
	
	public function beforeRender() {
	    parent::beforeRender();
	    if($this->getUser()->isLoggedIn()) {
		$this->template->numNotif = $this->notifications->getUnseenNotificationsCount($this->getUser()->getId());
	    }
	}
}
