<?php
class Brewery extends BaseDBObject
{
	var $fields = [
		'BREWERYID',
		'name',
	];
	var $record = [];
	var $db_key = 'BREWERYID';
	var $db_table = 'brewery';

	public static function getAll()
	{
		global $db;

		$breweries = [];

		$res = $db->Execute('select * from brewery order by name');
		foreach ($res as $cur)
		{
			$breweries[] = new Brewery(['record' => $cur]);
		} 

		return $breweries;
	}
}