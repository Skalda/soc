<?php
namespace Form;

class AddVehicleForm extends Base
{
	protected function buildForm() {
		$this->addText('name', 'Vehicle name:')
		    ->setRequired('Insert vehicle name.');
		$this->addSubmit('send', 'Add');
	}
}
