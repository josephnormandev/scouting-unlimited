<?php
/*
All web page requests pass through this file. It sets up some environment things that all pages need, and then chooses the 
proper controller object to route the request through by the 'controller' query string element.
*/
ini_set('session.gc_maxlifetime', 60*60*48);
$startTime = microtime(true);

//tell PHP to use UFT-8 character encoding
ini_set('default_charset', 'utf-8');

//tell PHP to always use the CST timezone
ini_set('date.timezone', 'America/Chicago');

//record the path for all files (one step up from public_html).
$fileRoot = $_SERVER['DOCUMENT_ROOT'] . '/../';

function autoload($class) {
	$fileRoot = $GLOBALS['fileRoot'];
	
	if (strstr($class, 'View') === 'View') {
		$includeFolder = '/views/';
	} else if (strstr($class, 'Model') === 'Model') {
		$includeFolder = '/models/';
	} else if (strstr($class, 'Controller') === 'Controller') {
		$includeFolder = '/controllers/';
	} else {
		$includeFolder = '/classes/';
	}
	
	if(file_exists($fileRoot . $includeFolder . $class . '.php')) {
		include ($fileRoot . $includeFolder . $class . '.php'); 
	}
}

//tell PHP to use our autoload function ^
spl_autoload_register('autoload');

//create a Configuration object to load config.ini
$config = new Configuration($fileRoot . 'config.ini');

//set up and load session information (such as the logged in user)
Session::setup();
Sanitizer::sanitize();

$controller = isset($_GET['p']) ? $_GET['p'] : 'login';
$action = isset($_GET['do']) ? $_GET['do'] : 'view';

switch($controller)
{
	case 'edit':
		(new EditDefinitionsController($action))->executeAction();
		break;
	case 'login':
		(new LoginController($action))->executeAction();
		break;
	case 'dataentry':
		(new DataEntryController($action))->executeAction();
		break;
	case 'upcoming':
		(new ScheduleController('viewUpcoming'))->executeAction();
		break;
	case 'teams':
		(new TeamsListController($action))->executeAction();
		break;
	case 'teamdata':
		(new TeamsListController('viewTeam'))->executeAction();
		break;
	case 'rankings':
		(new TeamsController('rankings'))->executeAction();
		break;
	case 'matchdata':
		(new DataEntryController('viewMatchData'))->executeAction();
		break;
	case 'adminpanel':
		(new AdminPanelController($action))->executeAction();
		break;
	case 'schedule':
		(new ScheduleController($action))->executeAction();
		break;
	case 'offline':
		(new OfflineClientController($action))->executeAction();
		break;
	default:
		(new ErrorView())->render("Your URL is broken.");
		break;
}

$length = microtime(true) - $startTime;