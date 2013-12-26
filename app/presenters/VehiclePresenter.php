<?php

class VehiclePresenter extends BasePresenter
{
	/** @var \Model\Vehicles */
	public $vehicles;

	/** @var \Model\Friends */
	public $friends;

	/** @var \Nette\Database\Table\Selection */
	public $vehicleId;

	public function injectVehicles(\Model\Vehicles $vehicles) {
		$this->vehicles = $vehicles;
	}

	public function injectFriends(\Model\Friends $friends) {
		$this->friends = $friends;
	}
	
	public function beforeRender() {
	    $this->template->vehicleId = $this->vehicleId;
	    parent::beforeRender();
	}
    
	public function actionDefault($id) {
		$this->vehicleId = $id;
	}
	
	public function renderDefault($id)
	{
		$vehicle = $this->vehicles->getVehicle($id);
		if(count($vehicle) == 0) 
		    $this->redirect('Homepage:');
		$this->template->vehicleinfo = $vehicle;
	}

	public function actionEditVehicle($id) {
		$this->vehicleId = $id;
	}

	public function renderEditVehicle($id) {
		$vehicle = $this->vehicles->getVehicle($id);
	    $this['editVehicleForm']->setDefaults($vehicle);
	}
	
	public function createComponentEditVehicleForm() {
	    $form = new Form\EditVehicleForm();
	    $form->onSuccess[] = $this->editVehicleFormSucceeded;
	    return $form;
	}
	
	public function editVehicleFormSucceeded($form) {
	    $values = $form->getValues();
	    $this->vehicles->modifyVehicle($this->vehicleId, $values->name, $values->info, $values->registration_number, $values->type, $values->status);
	    $this->flashMessage('Údaje byly změněny.', 'success');
	    $this->redirect('this');
	}

	public function actionVehiclePic($id) {
		$this->vehicleId = $id;
	}

	public function renderVehiclePic($id) {
	    $vehicle = $this->vehicles->getVehicle($id);
	    $this->template->profilPic = $vehicle['profilpic'];
	}

	public function createComponentImageUploadForm() {
	    $form = new Form\ImageUploadForm();
	    $form->onSuccess[] = $this->imageUploadFormSucceeded;
	    return $form;
	}
	
	public function imageUploadFormSucceeded($form) {
	    $values = $form->getValues();
	    $file = $values->image;
	    if($file->isOk()) {
			$name = $this->vehicleId . '-' . $file->getSanitizedName();
			$file->move(WWW_DIR.'/data/profil2/'.$name);
			$this->vehicles->changeVehiclePic($this->vehicleId, $name);
			$this->flashMessage('Fotka vozidla byla změněna.', 'success');
			$this->redirect('this');
	    }
	    $form->addError('Při uploadu nastala chyba, zkuste to prosím znova později.');
	}

	public function actionOwnerChange($id) {
		$this->vehicleId = $id;
	}

	public function renderOwnerChange($id) {
		$this->template->friends = $this->friends->getUsersFriends($this->getUser()->getId());
		$friends = $this->friends->getUsersFriends($this->getUser()->getId());
	    $this['ownerChangeForm']->setDefaults($friends);
	}
	
	public function createComponentOwnerChangeForm() {
	    $form = new Form\OwnerChangeForm();
	    $form->onSuccess[] = $this->ownerChangeFormSucceeded;
	    return $form;
	}
	
	public function ownerChangeFormSucceeded($form) {
	    $values = $form->getValues();
	    $this->vehicles->modifyVehicle($this->vehicleId, $values->owner);
	    $this->flashMessage('Vozidlo bylo přeneseno k jinému uživateli.', 'success');
	    $this->redirect('this');
	}
}