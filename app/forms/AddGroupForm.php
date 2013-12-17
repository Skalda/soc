<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class AddGroupForm extends Base
{
	
	protected function buildForm() {
		$this->addText('name', 'Název: ')
		    ->setRequired('Vložte prosím název skupiny.');
		$this->addTextArea('desc', 'Popisek:')
			->setRequired('Vložte prosím popisek skupiny.');
		$this->addSubmit('send', 'Vytvořit');
	}
}