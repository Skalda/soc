<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class SignInForm extends Base
{
	
	protected function buildForm() {
		$this->addText('email', 'Email:')
			->setRequired('Vložte svůj email.')
			->addRule(self::EMAIL, 'Email nemá správný tvar.');

		$this->addPassword('password', 'Heslo:')
			->setRequired('Vložte své heslo.');

		$this->addCheckbox('remember', 'Zůstat přihlášen?');

		$this->addSubmit('send', 'Přihlásit');

	} 
}
