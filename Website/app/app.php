<?php
include __DIR__."/../vendor/autoload.php";

// Load libraries
Use eftec\bladeone\BladeOne;
Use eftec\routeone\RouteOne;

// Load Controllers
include "app/Controllers/HomeController.php";
include "app/Controllers/AdminController.php";
include "app/Controllers/UserController.php";
include "app/Controllers/TournamentController.php";
include "app/Controllers/TableController.php";

// Load helpers
include "app/flash.php";
include "app/db.php";
include "app/middleware.php";
include "app/redirect.php";
include "app/auth.php";
include "app/response.php";

// Load Objects
include "app/Objects/GameSettings.php";

function path() {
	return "http://".$_SERVER["SERVER_ADDR"]."/PMTool/Website/";
}

function configPath() {
    return "http://".$_SERVER["SERVER_ADDR"]."/PMTool/config.ini";
}

// Blade container
function blade() {
	global $blade;

	if ($blade == null) {
		$blade = new BladeOne(__DIR__."/Views", __DIR__."/Compiles", BladeOne::MODE_DEBUG);
		$blade->setBaseUrl(path());
	}

	return $blade;
}

// Router container
function router() {
	global $router;

	if ($router == null) {
		$router = new RouteOne();
		$router->fetch();
	}

	return $router;
}
?>