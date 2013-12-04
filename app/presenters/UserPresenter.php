<?php

/**
 * Homepage presenter.
 */
class UserPresenter extends BasePresenter
{

	/** @var \Model\Users */
	public $users;
	/** @var \Model\Friends */
	public $friends;
	
	/** @var \Nette\Database\Table\Selection */
	public $userId;
	
	public function injectUsers(\Model\Users $users) {
		$this->users = $users;
	}
	
	public function injectFriends(\Model\Friends $friends) {
		$this->friends = $friends;
	}
	
	public function beforeRender() {
	    $this->template->userId = $this->userId;
	    parent::beforeRender();
	}
    
	public function actionDefault($id) {
		$this->userId = $id;
	}
	
	public function renderDefault($id)
	{
		$user = $this->users->getUser($this->userId);
		if(count($user) == 0) 
		    $this->redirect('Homepage:');
		$this->template->userinfo = $user;
		$viewer = Model\Users::NON_FRIEND;
		if($this->getUser()->isLoggedIn()) {
		    if($this->userId == $this->getUser()->getId()) {
			$viewer = Model\Users::AUTHOR;
		    } elseif($this->friends->getFriendState($this->userId, $this->getUser()->getId()) == \Model\Friends::ACCEPTED) {
			$viewer = Model\Users::FRIEND;
		    }
		}
		$this->template->viewer = $viewer;
		$this->template->wallposts = $this->users->getUsersWall($this->userId, $viewer);
	}
	
	public function createComponentAddWallPost() {
	    if(!$this->getUser()->isLoggedIn() || $this->getUser()->getId() != $this->userId){
		return null;
	    }
	    $form = new Form\AddWallPostForm();
	    $form->onSuccess[] = $this->addWallPostSucceeded;
	    return $form;
	}
	
	public function addWallPostSucceeded($form) {
	    $values = $form->getValues();
	    $privacy = Model\Users::ALL;
	    if($values->privacy == 1) {
		$privacy = Model\Users::FRIENDS_ONLY;
	    }
	    $this->users->addWallPost($this->userId, $values->content, $privacy);
	    $this->flashMessage('Zpráva byla úspěšně přidána.', 'success');
	    $this->redirect('this');
	}
	
	public function createComponentAddCommentForm() {
	    $self = $this;
	    return new \Nette\Application\UI\Multiplier(function ($postId) use ($self) {
		$form = new \Form\AddCommentForm();
		$form['post_id']->setValue($postId);
		$form->onSuccess[] = $self->addCommentFormSucceeded;
		return $form;
	    });
	}
	
	public function addCommentFormSucceeded($form) {
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('this');
	    }
	    $values = $form->getValues();
	    $this->users->addComment($this->getUser()->getId(), $values->post_id, $values->content);
	    $this->flashMessage('Komentář byl úspěšně přidán.', 'success');
	    if($this->getUser()->getId() != $this->userId) {
		$this->notifications->addNotification($this->userId, 'User:default', array('id'=>$this->userId), 'Uživatel {person} okomentoval váš post', array($this->getUser()->getId()));
	    }
	    $this->redirect('this');
	}
	
	public function actionFriends($id) {
		$this->userId = $id;
	}
	
	public function renderFriends($id) {
		$user = $this->users->getUser($this->userId);
		if(count($user) == 0) 
		    $this->redirect('Homepage:');
		$this->template->userinfo = $user;
		$this->template->friends = $this->friends->getUsersFriends($this->userId);
	}
	
	public function createComponentAddFriendsButton() {
	    if(!$this->getUser()->isLoggedIn()) {
		return null;
	    }
	    $control = new \Components\FriendsButton();
	    $control->setFrom($this->getUser()->getId());
	    $control->setTo($this->userId);
	    $control->injectFriends($this->friends);
	    return $control;
	}
}
