<?php

class RoutesPresenter extends BasePresenter {

    /** @var \Model\Routes */
    public $routes;

    /** @var \Model\Users */
    public $users;

    /** @var \Model\Entries */
    public $entries;

    /** @var \Model\Vehicles */
    public $vehicles;
    
    public function injectRoutes(Model\Routes $routes) {
	$this->routes = $routes;
    }
    
    public function injectUsers(Model\Users $users) {
	$this->users = $users;
    }

    public function injectVehicles(Model\Vehicles $vehicles) {
	$this->vehicles = $vehicles;
    }
    public function injectEntries(Model\Entries $entries) {
	$this->entries = $entries;
    }

    public function renderDefault() {
	if (!$this->getUser()->isLoggedIn())
	    $this->redirect('Homepage:');
	$this->template->routeslist = $this->routes->getUsersRoutes($this->getUser()->getId());
    }

    public function actionJson() {
	header('content-type: application/json; charset=utf-8');
	header("access-control-allow-origin: *");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	/* { 
	  "unit_id": 12345,
	  "secret_key": "sdfadfsa6as987654754",
	  "entries": [
	  {
	  "location": [14.42067, 50.07598],
	  "timestamp": "2012-10-24T01:00:27.372Z",
	  "event": "UNLOCKED",
	  "user_id": 123,
	  "odometer": 23549.75,
	  "velocity": 0.0,
	  "consumption": 0.0,
	  "fuel_remaining": 35.46,
	  "altitude": 201.46,
	  "engine_temp": 25.3,
	  "engine_rpm": 0.0,
	  "throttle": 0.0
	  },
	  {
	  "location": [14.42065, 50.07599],
	  "timestamp": "2012-10-24T01:03:27.372Z",
	  "user_id": 123,
	  "odometer": 23550,
	  "velocity": 23.631,
	  "consumption": 5.4,
	  "fuel_remaining": 35.46,
	  "altitude": 202.76,
	  "engine_temp": 42.3,
	  "engine_rpm": 2103.5,
	  "throttle": 23.1
	  }
	  ]
	  } */
	$input = file_get_contents('php://input');
	
	$route = json_decode($input,true);
	$unit_id = $route["unit_id"];
	$secret_key = $route["secret_key"];
	$entries = $route["entries"];
	$user_id = $entries[0]["user_id"];
	$user = $this->users->getUser($user_id);
	if (count($user) == 0) {
	    $output = array("status" => "error", "message" => "User with requested user_id doesn't exist.");
	    $this->sendResponse(new \Nette\Application\Responses\JsonResponse($output));
	}
	$vehicle = $this->vehicles->getUsersVehicles($user['id'])->where('unit_id', $unit_id)->fetch();
	if (count($vehicle) == 0) {
	    $output = array("status" => "error", "message" => "Vehicle with requested unit_id doesn't exist.");
	    $this->sendResponse(new \Nette\Application\Responses\JsonResponse($output));
	} else {
	    $newRoute = $this->routes->addRoute($vehicle->id, $vehicle->users_id, $unit_id, $secret_key);
	}
	foreach ($entries as $entry) {
	    $location = $entry["location"];
	    $timestamp = $entry["timestamp"];
	    if(isset($entry["event"])) {
		$event = $entry["event"];
	    } else {
		$event = null;
	    }
	    $user_id = $entry["user_id"];
	    $odometer = $entry["odometer"];
	    $velocity = $entry["velocity"];
	    $consumption = $entry["consumption"];
	    $fuel_remaining = $entry["fuel_remaining"];
	    $altitude = $entry["altitude"];
	    $engine_temp = $entry["engine_temp"];
	    $engine_rpm = $entry["engine_rpm"];
	    $throttle = $entry["throttle"];
	    $this->entries->addEntry($newRoute->id, $location, $timestamp, $event, $user_id, $odometer, $velocity, $consumption, $fuel_remaining, $altitude, $engine_temp, $engine_rpm, $throttle);
	}
	$this->routes->initRoute($newRoute->id, $entries[0]["timestamp"], $entries[count($entries) - 1]["timestamp"], $entries[count($entries) - 1]["odometer"] - $entries[0]["odometer"]);
	if (json_last_error()) {
	    $output = array("status" => "error", "message" => "Invalid request format (detailed description).");
	} else {
	    $output = array("status" => "ok");
	}
	$output = array("status" => "ok");
	$this->sendResponse(new \Nette\Application\Responses\JsonResponse($output));
    }

}