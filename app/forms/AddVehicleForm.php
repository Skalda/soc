<?php
namespace Form;

class AddVehicleForm extends Base
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
		))
		->setRequired('Vyberte typ vozidla.');;
		$this->addRadioList('status', 'Stav:', array(
		    'ready' => 'Připraveno',
		    'in use' => 'Právě používáno',
		    'not ready' => 'Nepřipraveno',
		))
		->setRequired('Vyberte stav vozidla.');;;

		$this->addSubmit('send', 'Přidat');
	}
}
