<?php
namespace Form;

class AddVehicleForm extends Base
{
	protected function buildForm() {
		$this->addText('name', 'Název vozidla:')
		    ->setRequired('Vložte název vozidla.');
		$this->addSubmit('send', 'Přidat');
	}
}
