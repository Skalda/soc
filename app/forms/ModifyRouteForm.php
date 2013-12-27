<?php
namespace Form;

class ModifyRouteForm extends Base
{
	
	protected function buildForm() {
		$this->addText('name', 'Název: ');
	}
}