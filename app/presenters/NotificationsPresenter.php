<?php

class NotificationsPresenter extends BasePresenter
{
		
	public function renderDefault()
	{
	    if(!$this->getUser()->isLoggedIn())
		$this->redirect('Homepage:');
	    $this->template->notifications = $this->notifications->getNotifications($this->getUser()->getId());
	}
	
	public function createComponentAddNotif() {
	    $form = new \Nette\Application\UI\Form();
	    $form->addText('users_id', 'User id:');
	    $form->addText('link', 'Link:');
	    $form->addTextArea('message', 'Message:');
	    $form->addTextArea('attr', 'Atributy:');
	    $form->addSubmit('submit', 'vložit');
	    $form->onSuccess[] = $this->addNotifSucceeded;
	    return $form;
	}
	
	public function addNotifSucceeded($form) {
	    $values = $form->getValues();
	    $linkparams = array(
		'id' => 5,
		'name' => 'stranka',
	    );
	    if(trim($values->attr) != "") {
		$attr = explode("\n", $values->attr);
	    } else {
		$attr = array();
	    }
	    $this->notifications->addNotification($values->users_id, $values->link, $linkparams, $values->message, $attr);
	}

}
