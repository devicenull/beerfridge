<?php
require(__DIR__.'/../init.php');

if (isset($_POST['action']) && $_POST['action'] == 'delete_beer')
{
	$beer = new Beer(['BEERID' => $_POST['BEERID']]);
	if ($beer->delete())
	{
		displaySuccess('Deleted '.$beer['name'], '/');
	}
}


$beers = Beer::getAvailable();

$vars = [
	'beers'       => $beers,
	// My fridge uses a raspberry pi as a display, don't show edit/del buttons there
	'showbuttons' => !stripos($_SERVER['USER_AGENT'], 'Raspbian'),
];
displayPage('index.html', $vars);
