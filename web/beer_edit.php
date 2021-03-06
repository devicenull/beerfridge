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
				'name'       => $_POST['new_brewery'],
				'untappd_id' => $_POST['brewery_untappd_id'],
			]);
			$params['BREWERYID'] = $brewery['BREWERYID'];
		}

		/**
		*	It's possible that one beer has multiple IDs - If we already
		*	know about this beer, just add the UPC to the existing beer,
		*	rather then adding another one
		*/
		$beer = Beer::getByUntappdId($_POST['untappd_id']);
		if ($beer->isInitialized())
		{
			$upc = new UPC();
			if ($upc->add([
				'UPC'    => $_POST['upc'],
				'BEERID' => $beer['BEERID'],
			]))
			{
				$beer->set(['count_available' => $_POST['count_available']]);
			}
			displaySuccess('Added new UPC for '.$beer['name'], '/');
		}
		else if ($beer->add($params))
		{
			$_SESSION['last_beer'] = $beer['BEERID'];
			$upc = new UPC();
			$upc->add([
				'UPC'    => $_POST['upc'],
				'BEERID' => $beer['BEERID'],
			]);

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
	'upc'       => $_REQUEST['upc'] ?? '',
	'breweries' => Brewery::getAll(),
]);
