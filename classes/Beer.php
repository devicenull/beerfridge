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
		'untappd_id',
	];

	var $db_key = 'BEERID';
	var $db_table = 'beer';

	public function getObject($key)
	{
		if ($key == 'BREWERYID')
		{
			return new Brewery(['BREWERYID'=>$this['BREWERYID']]);
		}

		return null;
	}

	public static function getAvailable()
	{
		global $db;
		$res = $db->Execute('
			select beer.*
			from beer
			left join brewery on beer.BREWERYID=brewery.BREWERYID
			where count_available > 0
			order by style, brewery.name, name asc
		');
		$beers = [];
		foreach ($res as $cur)
		{
			$beers[] = new Beer(['record' => $cur]);
		}

		return $beers;
	}

	public static function getByUPC($upc): Beer
	{
		global $db;

		$res = $db->Execute('
			select *
			from upc
			left join beer on upc.BEERID=beer.BEERID
			where upc.UPC=?',
			[$upc]
		);
		if ($res->RecordCount() == 1)
		{
			return new Beer(['record' => $res->fields]);
		}

		return new Beer();
	}

	public static function getByUntappdID($id): Beer
	{
		global $db;

		$res = $db->Execute('select * from beer where untappd_id=?', [$id]);
		if ($res->RecordCount() == 1)
		{
			return new Beer(['record' => $res->fields]);
		}

		return new Beer();
	}
}
