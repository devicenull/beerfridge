<?php
require_once(__DIR__.'/vendor/autoload.php');

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates/');

$twig = new \Twig\Environment($loader, [
	'cache' => false,
	'auto_reload' => true,
	'strict_variables' => true,
	'autoescape' => true,
]);

require_once(__DIR__.'/config.php');

require_once(__DIR__.'/vendor/adodb/adodb-php/adodb.inc.php');
$db = newAdoConnection('mysqli');
$db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

function displayPage($template, $vars=[])
{
	global $twig;

	echo $twig->render($template, $vars);
}