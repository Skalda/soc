<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class AddWallPostForm extends Base
{
	
	protected function buildForm() {
		$this->addTextArea('content', NULL, 100, 5)
		    ->setRequired('Vložte obsah postu.');
		$this->addSelect('privacy', 'Zobrazit:')
			->setItems(array(
			    0 => 'Všem',
			    1 => 'Pouze přátelům',
			))
			->setDefaultValue(0);

		$this->addSubmit('send', 'Vložit');

	} 
}
