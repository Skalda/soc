<?php

class FeedPresenter extends BasePresenter
{

	/** @var \Model\Users */
	public $users;
	/** @var \Model\Friends */
	public $friends;
	
	public function injectUsers(\Model\Users $users) {
		$this->users = $users;
	}
	
	public function injectFriends(\Model\Friends $friends) {
		$this->friends = $friends;
	}
	public function actionDefault() {
		if(!$this->getUser()->isLoggedIn()) {
		    $this->redirect('Homepage:');
		}
	}
	
	public function renderDefault()
	{
		$friends = $this->friends->getUsersFriends($this->getUser()->getId());
		
		$this->template->wallposts = $this->users->getFriendsWallPosts($friends);
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
	    $post = $this->users->getSingleWallPost($values->post_id, Model\Users::FRIEND)->fetch();
	    $this->flashMessage('Komentář byl úspěšně přidán.', 'success');
	    if($this->getUser()->getId() != $post->user_id) {
		$this->notifications->addNotification($post->user_id, 'User:default', array('id'=>$post->user_id), 'Uživatel {person} okomentoval váš post', array($this->getUser()->getId()));
	    }
	    $this->redirect('this');
	}
}
