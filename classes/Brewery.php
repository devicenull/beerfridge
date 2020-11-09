<?php
class Brewery extends BaseDBObject
{
	var $fields = [
		'BREWERYID',
		'name',
		'untappd_id',
	];
	var $record = [];
	var $db_key = 'BREWERYID';
	var $db_table = 'brewery';

	public function __construct($params=null)
	{
		global $db;
		if (isset($params['untappd_id']) && $params['untappd_id'] != 0)
		{
			$res = $db->execute('select * from brewery where untappd_id=?', [$params['untappd_id']]);
			if ($res->RecordCount() == 1)
			{
				$this->record = $res->fields;
				return;
			}
		}
		parent::__construct($params);
	}

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
