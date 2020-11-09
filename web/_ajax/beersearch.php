<?php
require(__DIR__.'/../../init.php');
Header('Content-Type: application/json');

$results = [];
foreach (Untappd::beerSearch($_GET['term']) as $beer)
{
	// select2 expects the id/text fields, and the 'beer' field is passed to the onselect handler
	$newresult = [
		'id'        => $beer['bid'],
		'text'      => $beer['brewery'].' - '.$beer['beer_name'],
		'beer'      => $beer,
	];
	$brewery = new Brewery(['untappd_id' => $beer['brewery_id']]);
	if ($brewery->isInitialized())
	{
		$newresult['beer']['BREWERYID'] = intVal($brewery['BREWERYID']);
	}
	else
	{
		$newresult['beer']['BREWERYID'] = 0;
	}

	$results[] = $newresult;
}

echo json_encode(['results' => $results]);
