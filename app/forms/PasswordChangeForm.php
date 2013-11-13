<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class PasswordChangeForm extends Base
{
	
	protected function buildForm() {
		$this->addPassword('current_password', 'Stávájící heslo:')
			->setRequired('Vložte své stávající heslo');
	    
		$this->addPassword('password', 'Nové heslo:')
			->setRequired('Vložte své nové heslo.');

		$this->addPassword('confirm_password', 'Nové heslo znovu:')
			->setRequired('Vložte své nové heslo znovu.')
			->addRule(self::EQUAL, 'Hesla se neshodují', $this['password']);
		
		$this->addSubmit('send', 'Změnit heslo.');

	} 
}
