<?php
class UPC extends BaseDBObject
{
	var $fields = [
		'UPC',
		'BEERID',
	];

	var $db_key = 'UPC';
	var $db_table = 'upc';

	public function getObject($key)
	{
		if ($key == 'BEERID')
		{
			return new Beer(['BEERID'=>$this['BEERID']]);
		}

		return null;
	}
}
