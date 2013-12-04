<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class SignUpForm extends Base
{
	
	protected function buildForm() {
		$this->addText('email', 'Email:')
			->setRequired('Vložte svůj email.')
			->addRule(self::EMAIL, 'Email nemá správný tvar.');

		$this->addPassword('password', 'Heslo:')
			->setRequired('Vložte své heslo.');

		$this->addPassword('confirm_password', 'Heslo znovu:')
			->setRequired('Vložte své heslo znovu.')
			->addRule(self::EQUAL, 'Hesla se neshodují', $this['password']);

		$this->addSubmit('send', 'Registrovat');

	} 
}
