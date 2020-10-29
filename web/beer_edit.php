<?php
require(__DIR__.'/../init.php');

if (!isset($_REQUEST['BEERID']))
{
	$BEERID = 'new';
	$beer = new Beer();
}
else
{
	$BEERID = intVal($_REQUEST['BEERID']);
	$beer = new Beer(['BEERID' => $BEERID]);
}

if (isset($_POST['action']) && $_POST['action'] == 'update_beer')
{
	$params = [];
	foreach ($beer->fields as $key)
	{
		if (isset($_POST[$key]) && $key != 'BEERID')
		{
			$params[$key] = $_POST[$key];
		}
	}

	if ($BEERID == 'new')
	{
		if ($beer->add($params))
		{
			Header('Location: /');
			exit();
		}
	}
	else
	{
		if ($beer->set($params))
		{
			Header('Location: /');
			exit();
		}
	}
}


displayPage('beer_edit.html', [
	'beer'      => $beer,
	'BEERID'    => $BEERID,
	'breweries' => Brewery::getAll(),
]);