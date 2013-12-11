<?php

class RoutesPresenter extends BasePresenter
{
	/** @var Model\Routes */
	public $routes;
	
	public function injectVehicles(Model\Routes $routes) {
	    $this->routes = $routes;
	}
	
	public function renderDefault()
	{
	    if(!$this->getUser()->isLoggedIn())
	    	$this->redirect('Homepage:');
	    $this->template->routeslist = $this->routes->findAll()/*getRoutes($this->getUser()->getId())*/;
	}
}