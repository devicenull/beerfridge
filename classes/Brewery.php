<?php
class Brewery extends BaseDBObject
{
	var $fields = [
		'BREWERYID',
		'name',
	];
	var $record = [];

	public function __construct($params=[])
	{
		global $db;
		
		if (isset($params['BREWERYID']))
		{
			$res = $db->Execute('select * from brewery where BREWERYID=?', [$params['BREWERYID']]);
			if ($res->RecordCount() > 0)
			{
				$this->record = $res->fields;
			}
		}
		else
		{
			parent::__construct($params);
		}
	}
}