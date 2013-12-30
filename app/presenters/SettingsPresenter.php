<?php



/**
 * Sign in/out presenters.
 */
class SettingsPresenter extends BasePresenter
{
	/** @var \Model\Users */
	public $users;
	
	public function injectUsers(\Model\Users $users) {
		$this->users = $users;
	}
	
	public function startup() {
	    parent::startup();
	    if(!$this->getUser()->isLoggedIn()) {
		$this->redirect('Homepage:');
	    }
	}
	
	public function createComponentPasswordChangeForm() {
	    $form = new \Form\PasswordChangeForm();
	    $form->onSuccess[] = $this->passwordChangeFormSucceeded;
	    return $form;
	}
	
	public function passwordChangeFormSucceeded($form) {
	    $values = $form->getValues();
	    try {
		$this->users->changePassword($this->getUser()->getId(), $values->current_password, $values->password);
		$this->flashMessage('Heslo bylo úspěšně změněno.', 'success');
		$this->redirect('default');
	    } catch(Model\WrongPasswordException $e) {
		$form->addError($e->getMessage());
	    }
	}
	
	public function renderEditInfo() {
	    $this['editInfoForm']->setDefaults($this->users->getUser($this->getUser()->getId()));
	}
	
	public function createComponentEditInfoForm() {
	    $form = new Form\EditInfoForm();
	    $form->onSuccess[] = $this->editInfoFormSucceeded;
	    return $form;
	}
	
	public function editInfoFormSucceeded($form) {
	    $values = $form->getValues();
	    $this->users->modifyUser($this->getUser()->getId(), $values->name, $values->surname, $values->sex, $values->city);
	    $this->flashMessage('Udaje byly změněny', 'success');
	    $this->redirect('default');
	}
	
	public function renderProfilPic() {
	    $user = $this->users->getUser($this->getUser()->getId());
	    $this->template->profilPic = $user['profilpic'];
	}
	
	public function createComponentImageUploadForm() {
	    $form = new Form\ImageUploadForm();
	    $form->onSuccess[] = $this->imageUploadFormSucceeded;
	    return $form;
	}
	
	public function imageUploadFormSucceeded($form) {
	    $values = $form->getValues();
	    $file = $values->image;
	    if($file->isOk()) {
			$name = $this->getUser()->getId() . '-' . $file->getSanitizedName();
			$file->move(WWW_DIR.'/data/profil/'.$name);
			$this->users->changeProfilePic($this->getUser()->getId(), $name);
			$this->flashMessage('Profilová fotka byla změněna.', 'success');
			$this->redirect('this');
	    }
	    $form->addError('Při uploadu nastala chyba, zkuste to prosím znova později.');   
	}
}