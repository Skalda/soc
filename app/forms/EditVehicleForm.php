<?php
namespace Form;

class EditVehicleForm extends Base
{
	
	protected function buildForm() {
		$this->addText('name', 'Název:')
			->setRequired('Vložte název vozidla.');
		$this->addText('info', 'Info:');
		$this->addText('registration_number', 'Registrační číslo:')
			->addCondition(self::FILLED)
			->addRule(self::LENGTH, 'Registrační číslo musí mít délku 7 znaků.', 7);
		$this->addRadioList('type', 'Typ:', array(
		    'car' => 'Auto',
		    'motorcycle' => 'Motorka',
		    'quad' => 'Čtyřkolka',
		    'tricycle' => 'Trojkolka',
		    'scooter' => 'Skůtr',
		    'motor bike' => 'Motorové kolo',
		));
		$this->addRadioList('status', 'Stav:', array(
		    'ready' => 'Připraveno',
		    'in use' => 'Právě používáno',
		    'not ready' => 'Nepřipraveno',
		));

		$this->addSubmit('send', 'Změnit údaje');
	} 
}
