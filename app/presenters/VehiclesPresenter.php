<?php

class VehiclesPresenter extends BasePresenter
{
	/** @var Model\Vehicles */
	public $vehicles;
	
	public function injectVehicles(Model\Vehicles $vehicles) {
	    $this->vehicles = $vehicles;
	}
	
	public function renderDefault()
	{
	    if(!$this->getUser()->isLoggedIn())
	    	$this->redirect('Homepage:');
	    $this->template->vehicleslist = $this->vehicles->getUsersVehicles($this->getUser()->getId());
	}

	/*public function renderShowSingle($id){
		
	}*/
	
	public function createComponentAddVehicleForm() {
		if(!$this->getUser()->isLoggedIn())
			return null;
	    $form = new \Form\AddVehicleForm();
	    $form->onSuccess[] = $this->addVehicleSucceeded;
	    return $form;
	}
	
	public function addVehicleSucceeded($form) {
	    $values = $form->getValues();
	    
	    try {
	    	$this->vehicles->addVehicle($this->getUser()->getId(), $values->unit_id, $values->name, $values->info, $values->registration_number, $values->type, $values->status);
	    	$this->flashMessage('Vehicle added.', 'success');
			$this->redirect('Vehicles:');
	    } catch(\Model\DuplicateEntryException $e) {
			$form->addError('Vehicle with this name already exists.');
		}

	    return $form;
	}
}