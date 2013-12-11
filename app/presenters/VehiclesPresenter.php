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
	    $this->template->vehicleslist = $this->vehicles->findAll()/*getVehicles($this->getUser()->getId())*/;
	}
	
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
	    	$this->vehicles->addVehicle($this->getUser()->getId(), $values->name);
	    	$this->flashMessage('Vehicle added.', 'success');
			$this->redirect('Vehicles:');
	    } catch(\Model\DuplicateEntryException $e) {
			$form->addError('Vehicle with this name already exists.');
		}

	    return $form;
	}
}