<?php
class BaseDBObject implements ArrayAccess
{
	// Name of the primary key
	var $db_key = '';
	// Name of the database table
	var $db_table = '';
	// Full list of all DB fields (including primary key)
	var $fields = [];

	var $record = [];

	public function __construct($params=[])
	{
		if (isset($params['record']))
		{
			$this->record = $params['record'];
		}
		else if (isset($params[$this->db_key]))
		{
			global $db;
			$res = $db->Execute('select * from '.$this->db_table.' where '.$this->db_key.'=?', [$params[$this->db_key]]);
			if ($res->RecordCount() == 1)
			{
				$this->record = $res->fields;
			}
		}
	}

	public function set(array $params)
	{
		global $db;

		$update = [];
		foreach ($this->fields as $field)
		{
			if (!isset($params[$field]) || $params[$field] == $this->record[$field])
			{
				continue;
			}

			$update[] = $field.'='.$db->qstr($params[$field]);
		}

		if (count($update) > 0)
		{
			return $db->Execute('update '.$this->db_table.' set '.implode(', ', $update).' where '.$this->db_key.'=?', [$this->record[$this->db_key]]);
		}

		// nothing changed?
		return true;
	}

	public function add(array $params)
	{
		global $db;

		$add_cols = [];
		$add_vals = [];
		foreach ($this->fields as $field)
		{
			if (!isset($params[$field]))
			{
				continue;
			}

			$add_cols[] = $field;
			$add_vals[] = $db->qstr($params[$field]);
		}

		if (count($add_cols) > 0)
		{
			return $db->Execute('insert into '.$this->db_table.'('.implode(',', $add_cols).') values('.implode(', ', $add_vals).')');
		}

		// no data provided?
		return false;
	}

	public function delete(): bool
	{
		global $db;
		
		return (bool)$db->Execute('delete from '.$this->db_table.' WHERE '.$this->db_key.'=?', [$this->record[$this->db_key]]);
	}

	public function offsetExists($offset)
	{
		return in_array($offset, $this->fields);
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