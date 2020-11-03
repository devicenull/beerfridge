<?php
require(__DIR__.'/../init.php');

if (!isset($_REQUEST['BEERID']) || $_REQUEST['BEERID'] == 'new')
{
	$BEERID = 'new';
	$beer = new Beer();
	if (isset($_REQUEST['upc']))
	{
		// cheating again - the barcode add process will set this before redirecting here
		$beer->record['upc'] = $_REQUEST['upc'];
		$beer->record['count_available'] = 1;
	}
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
		if ($params['BREWERYID'] == 'new_brewery')
		{
			$brewery = new Brewery();
			$brewery->add([
				'name' => $_POST['new_brewery'],
			]);
			$params['BREWERYID'] = $brewery['BREWERYID'];
		}

		if ($beer->add($params))
		{
			$_SESSION['last_beer'] = $beer['BEERID'];
			displaySuccess('Added '.$params['name'], '/');
		}
		else
		{
			displayError('Failed: '.$beer->error);
			// cheating to preserve the data
			$beer->record = $params;
		}
	}
	else
	{
		if ($beer->set($params))
		{
			displaySuccess('Updated '.$beer['name'], '/');
		}
		else
		{
			displayError('Failed: '.$beer->error);
		}
	}
}


displayPage('beer_edit.html', [
	'beer'      => $beer,
	'BEERID'    => $BEERID,
	'breweries' => Brewery::getAll(),
]);
