<?php
class Beer extends BaseDBObject
{
	var $fields = [
		'BEERID',
		'BREWERYID',
		'name',
		'abv',
		'style',
		'count_available',
	];
	var $record = [];
	var $db_key = 'BEERID';
	var $db_table = 'beer';

	public static function getAvailable()
	{
		global $db;
		$res = $db->Execute('
			select *
			from beer
			where count_available > 0
			order by BREWERYID, style, name asc
		');
		$beers = [];
		foreach ($res as $cur)
		{
			$beers[] = new Beer(['record' => $cur]);
		}

		return $beers;
	}

	public function getObject($key)
	{
		if ($key == 'BREWERYID')
		{
			return new Brewery(['BREWERYID'=>$this['BREWERYID']]);
		}

		return null;
	}
}