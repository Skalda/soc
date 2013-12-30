<?php

use Nette\Application\UI;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{
	/** @var \Model\Users */
	public $users;
	
	public function injectUsers(\Model\Users $users) {
		$this->users = $users;
	}
	
	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm() {
		$form = new \Form\SignInForm();
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}


	public function signInFormSucceeded($form) {
		$values = $form->getValues();

		if ($values->remember) {
			$this->getUser()->setExpiration('14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->email, $values->password);
			$this->flashMessage('Byl jste úspěšně přihlášen.', 'success');
			if($this->getUser()->getIdentity()->filled_data == 0) {
			    $this->flashMessage('Vyplňte si prosím informace o sobě v nastavení svého profilu.', 'info');
			}
			$this->redirect('Feed:');

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}
        
    public function createComponentSignUpForm() {
		$form = new \Form\SignUpForm();
		$form->onSuccess[] = $this->signUpFormSucceeded;
		return $form;
        }
	
	public function signUpFormSucceeded($form) {
		$values = $form->getValues();
		
		try {
			$this->users->addUser($values->email, $values->password);
			$this->flashMessage('Registrace proběhla úspěšně.', 'success');
			$this->redirect('Homepage:');
		} catch(\Model\DuplicateEntryException $e) {
			$form->addError('Uživatel se stejným emailem již existuje.');
		}
	}


	public function actionOut() {
		$this->getUser()->logout(True);
		$this->flashMessage('Byl jste odhlášen.');
		$this->redirect('in');
	}

}
