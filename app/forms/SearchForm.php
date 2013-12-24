<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class SearchForm extends Base
{
	
	protected function buildForm() {
		$this->addText('search', 'Search:')
			->setRequired('Vložte hledaný výraz.');

		
		$this->addSubmit('send', 'Hledat');
	} 
}
