<?php
require(__DIR__.'/../init.php');

$beers = Beer::getAvailable();

$vars = [
	'beers' => $beers,
];

displayPage('index.html', $vars);