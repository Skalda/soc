<?php

class HomepagePresenter extends BasePresenter
{
	/** @var Model\Users */
	public $users;
	
	public function injectUsers(Model\Users $users) {
	    $this->users = $users;
	}
	
	public function renderDefault()
	{
		$this->template->userslist = $this->users->findAll();
	}

	public function handleJson() {
		/*{ 
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
}*/
		$input = $_GET["route"];
		//$input = file_get_contents('php://input');//jina moznost z jine stranky
		$route = json_decode($input);
		$unit_id = $route -> {"unit_id"};
		$secret_key = $route -> {"secret_key"};
		$entries = $route -> {"entries"};
		$rou = $this->routes->addRoute($this->getUser()->getId, $unit_id, $secret_key);
		foreach ($entries as $entry) {
			$location = $entry -> {"location"};
            $timestamp = $entry -> {"timestamp"};
            $event = $entry -> {"event"};
            $user_id = $entry -> {"user_id"};
            $odometer = $entry -> {"odometer"};
            $velocity = $entry -> {"velocity"};
            $consumption = $entry -> {"consumption"};
            $fuel_remaining = $entry -> {"fuel_remaining"};
            $altitude = $entry -> {"altitude"};
            $engine_temp = $entry -> {"engine_temp"};
            $engine_rpm = $entry -> {"engine_rpm"};
            $throttle = $entry -> {"throttle"};
            $entry = $this->entries->addEntry($rou->id, $location, $timestamp, $event, $user_id, $odometer, $velocity, $consumption, $fuel_remaining, $altitude, $engine_temp, $engine_rpm, $throttle);
		}
		$this->routes->initRoute($rou->id, $entries[count($entries)-1]->timestamp - $entries[0]->timestamp, $entries[count($entries)-1]->odometer - $entries[0]->odometer);
		if (json_last_error()) {
		 	$output = array("status" => "error", "message" => "Invalid request format (detailed description)." );
		} else $output = array("status" => "ok");
		echo json_encode($output);
	}
}