<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class EditInfoForm extends Base
{
	
	protected function buildForm() {
		$this->addText('name', 'Jméno:')
		    ->setRequired('Vložte svoje jméno.');
		$this->addText('surname', 'Příjmení:')
			->setRequired('Vložte svoje příjmení');
		$this->addRadioList('sex', 'Pohlaví:', array(
		    'male' => 'Muž',
		    'female' => 'Žena',
		));
		$this->addText('city', 'Město:');

		$this->addSubmit('send', 'Změnit údaje');

	} 
}
