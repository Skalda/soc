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
	    $this->template->vehicleslist = $this->vehicles->getVehicles($this->getUser()->getId());
	}
	
	public function createComponentAddVehicle() {
	    //$form = new \Nette\Application\UI\Form();
	    $form = new \Form\AddVehicleForm();
	    //$form->addText('name', 'Vehicle name:');

	    //$values = $form->getValue('name');
	    //$form->onSuccess[];
	    try {
	    	$this->vehicles->addVehicle($form->getValue('name'));
	    	$this->flashMessage('Registration suceeded.', 'success');
			$this->redirect('Vehicles:');
	    } catch(\Model\DuplicateEntryException $e) {
			$form->addError('Vehicle with this name already exists.');
		}
	    
	    return $form;
	}
}