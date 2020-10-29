<?php
class BaseDBObject implements ArrayAccess
{
	var $fields = [];
	var $record = [];
	var $virtual_fields = [];

	public function __construct($params=[])
	{
		if (isset($params['record']))
		{
			$this->record = $params['record'];
		}
	}

	public function offsetExists($offset)
	{
		return isset($this->record[$offset]);
	}
	
	public function offsetGet($offset) 
	{
		return $this->record[$offset] ?? '';
	}

	public function offsetSet($offset, $value) 
	{
		throw new Exception('not implemented');
	}

	public function offsetUnset ($offset) 
	{
		throw new Exception('not implemented');
	}	
}