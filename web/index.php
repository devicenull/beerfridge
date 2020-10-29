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
	'beers' => $beers,
];

displayPage('index.html', $vars);