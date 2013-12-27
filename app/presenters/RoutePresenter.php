<?php

class RoutePresenter extends BasePresenter
{
	/** @var \Model\Users */
	public $users;

	/** @var \Model\Routes */
	public $routes;

	/** @var \Model\Vehicles */
	public $vehicles;

	/** @var \Model\Friends */
	public $friends;

	/** @var \Model\RouteUsers */
	public $routeUsers;

	/** @var \Nette\Database\Table\Selection */
	public $routeId;

	public function injectUsers(\Model\Users $users) {
		$this->users = $users;
	}

	public function injectRoutes(\Model\Routes $routes) {
		$this->routes = $routes;
	}

	public function injectVehicles(\Model\Vehicles $vehicles) {
		$this->vehicles = $vehicles;
	}

	public function injectFriends(\Model\Friends $friends) {
		$this->friends = $friends;
	}

	public function injectRouteusers(\Model\RouteUsers $routeUsers) {
		$this->routeUsers = $routeUsers;
	}
	
	public function beforeRender() {
	    $this->template->routeId = $this->routeId;
	    parent::beforeRender();
	}
    
	public function actionDefault($id){
		$this->routeId = $id;
	}
	
	public function renderDefault($id){
		$route = $this->routes->find($id);
		if(count($route) == 0) $this->redirect('Homepage:');
		$this->template->routeinfo = $route;
		$this->template->vehiclename = $this->vehicles->getVehicle($route->vehicles_id)['name'];
		$this->template->usermail = $this->users->getUser($route->users_id)['email'];
		$this->template->sharers = $this->routeUsers->getRoutesUsers($this->routeId);
		$this->template->path = $this->routes->getPath($id);
	}

	public function actionModifyRoute($id) {
		$this->routeId = $id;
	}

	public function renderModifyRoute($id) {
		$route = $this->routes->find($id);
		if(count($route) == 0) $this->redirect('Homepage:');
		$this->template->routeinfo = $route;
		$this->template->vehiclename = $this->vehicles->getVehicle($route->vehicles_id)['name'];
		$this->template->usermail = $this->users->getUser($route->users_id)['email'];
		$this->template->sharers = $this->routeUsers->getRoutesUsers($this->routeId);
		$this->template->path = $this->routes->getPath($id);
	    $this['modifyRouteForm']->setDefaults($route);
	}
	
	public function createComponentModifyRouteForm() {
	    $form = new Form\ModifyRouteForm();
	    $vehicles = $this->vehicles->getUsersVehicles($this->getUser()->getId())->fetchPairs('id', 'name');
	    $form->addSelect('vehicle', 'Vozidlo:', $vehicles)->setDefaultValue($this->routes->find($this->routeId)->vehicles_id);
	    $friends = $this->friends->getUsersFriends($this->getUser()->getId())->fetchPairs('id', 'email');
	    $form->addMultiSelect('sharers', 'Sdílet s:', $friends)->setPrompt('Vyberte všechny uživatele pro sdílení');
	    $form->addSubmit('send', 'Upravit trasu');
	    $form->onSuccess[] = $this->modifyRouteFormSucceeded;
	    return $form;
	}
	
	public function modifyRouteFormSucceeded($form) {
	    $values = $form->getValues();
	    $route = $this->routes->modifyRoute($this->routeId, $values->name, $values->vehicle, $values->sharers);
	    $this->flashMessage('Údaje byly změněny.', 'success');
	    $this->template->routeinfo = $route;
	    $this->template->vehiclename = $this->vehicles->getVehicle($route->vehicles_id)['name'];
		$this->template->usermail = $this->users->getUser($route->users_id)['email'];
		$this->template->sharers = $this->routeUsers->getRoutesUsers($this->routeId);
		$this->template->path = $this->routes->getPath($this->routeId);
	    $this->redirect('default', array('id'=>$this->routeId));
	}

	public function actionSharers($id) {
		$this->routeId = $id;
	}

	public function renderSharers($id) {
		$route = $this->routes->find($id);
		if(count($route) == 0) $this->redirect('Homepage:');
		$this->template->routeinfo = $route;
		$this->template->vehiclename = $this->vehicles->getVehicle($route->vehicles_id)['name'];
		$this->template->usermail = $this->users->getUser($route->users_id)['email'];
		$this->template->sharers = $this->routeUsers->getRoutesUsers($this->routeId);
		$this->template->path = $this->routes->getPath($id);
	}
}