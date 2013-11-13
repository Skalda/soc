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
		
		$this->addText('name', 'Jméno:')
			->setRequired('Vložte svoje jméno.');
		$this->addText('surname', 'Příjmení')
			->setRequired('Vložte svoje příjmení');
		$this->addRadioList('sex', 'Pohlaví:', array(
		    'male' => 'Muž',
		    'female' => 'Žena',
		));
		$this->addText('city', 'Město:');

		$this->addSubmit('send', 'Registrovat');

	} 
}
