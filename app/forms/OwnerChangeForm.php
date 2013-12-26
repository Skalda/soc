<?php
namespace Form;

class OwnerChangeForm extends Base
{
	
	protected function buildForm() {
		$this->addSelect('owner', 'Majitel:')
		->setPrompt('Zvolte nového majitele');
		
		$this->addSubmit('send', 'Změnit majitele');
	} 
}