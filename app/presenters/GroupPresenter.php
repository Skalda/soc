<?php

class GroupPresenter extends BasePresenter
{

	/** @var \Model\Users */
	public $users;
	/** @var \Model\Groups */
	public $groups;
	
	public $groupId;
	
	public function injectUsers(\Model\Users $users) {
		$this->users = $users;
	}
	
	public function injectGroups(\Model\Groups $groups) {
		$this->groups = $groups;
	}
	public function actionDefault() {
		if(!$this->getUser()->isLoggedIn()) {
		    $this->redirect('Homepage:');
		}
	}
	
	public function renderDefault()
	{
		$this->template->groupslist = $this->groups->findAll();
	}
	
	public function actionAddGroup() {
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('Homepage:');
	    }
	}
	
	public function actionModifyGroup($id) {
	    $group = $this->groups->find($id);
	    if(!$this->getUser()->isLoggedIn() || $group == null || $group->user->id != $this->getUser()->getId()) {
		$this->redirect('Homepage:');
	    }
	}
	
	public function renderModifyGroup($id) {
	    $group = $this->groups->find($id);
	    $this['modifyGroupForm']->setDefaults($group);
	}
	
	public function createComponentModifyGroupForm() {
	    $form = new Form\AddGroupForm();
	    $form->addHidden('id');
	    $form->onSuccess[] = $this->modifyGroupFormSucceeded;
	    return $form;
	}
	
	public function modifyGroupFormSucceeded($form) {
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('this');
	    }
	    $values = $form->getValues();
	    $this->groups->modifyGroup($values->id, $values->name, $values->desc);
	    $this->flashMessage('Skupina byla upravena', 'success');
	    $this->redirect('show', array('id'=>$values->id));
	}
	
	public function actionShow($id) {
	    $this->groupId = $id;
	}
	
	
	public function basicGroup($id) {
	    $group = $this->groups->find($id);
	    if($group == NULL) {
		$this->redirect('Homepage:');
	    }
	    $this->template->groupId = $id;
	    $this->template->group = $group;
	    $member = $this->groups->isMember($id, $this->getUser()->getId());
	    $this->template->member = $member;
	}
	
	public function renderShow($id) {
	    $this->basicGroup($id);
	    $member = $this->groups->isMember($id, $this->getUser()->getId());
	    if($member) {
		$this->template->wallposts = $this->groups->getGroupWall($id);
	    }
	}
	
	public function renderMembers($id) {
	    $this->basicGroup($id);
	    $this->template->members = $this->groups->getMembers($id);
	}
	
	public function handleBecomeMember($id) {
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('Homepage:');
	    }
	    $this->groups->addMember($id, $this->getUser()->getId());
	    $this->flashMessage('Byl jste zařazen do skupiny', 'success');
	    $this->redirect('show', array('id' => $id));
	}
	
	public function handleRemoveMember($id) {
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('Homepage:');
	    }
	    $this->groups->removeMember($id, $this->getUser()->getId());
	    $this->flashMessage('Vaše členství bylo úspěšně zrušeno.', 'success');
	    $this->redirect('show', array('id' => $id));
	}
	
	public function createComponentAddGroupForm() {
	    $form = new Form\AddGroupForm();
	    $form->onSuccess[] = $this->addGroupFormSucceeded;
	    return $form;
	}
	
	public function addGroupFormSucceeded($form) {
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('this');
	    }
	    $values = $form->getValues();
	    $group = $this->groups->addGroup($this->getUser()->getId(), $values->name, $values->desc);
	    $this->flashMessage('Skupina byla vytvořena', 'success');
	    $this->redirect('show', array('id'=>$group->id));
	}
	
	
	public function createComponentAddWallPost() {
	    $form = new Form\AddWallPostForm();
	    unset($form['privacy']);
	    $form->onSuccess[] = $this->addWallPostSucceeded;
	    return $form;
	}
	
	public function addWallPostSucceeded($form) {
	    $values = $form->getValues();
	    $this->groups->addWallPost($this->groupId, $this->getUser()->getId(), $values->content);
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
	    $this->groups->addComment($values->post_id, $this->getUser()->getId(), $values->content);
	    $this->redirect('this');
	}
}
