<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class ImageUploadForm extends Base
{
	
	protected function buildForm() {
		$this->addUpload('image', 'Soubor:')
			->setRequired('Musíte vybrat soubor')
			->addRule(self::IMAGE, 'Soubor musí být obrázek');
		
		$this->addSubmit('send', 'Uložit.');

	} 
}
