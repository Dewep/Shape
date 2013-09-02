<?php

class Dewep_MySQL
{
	public static $_db;
	public static $_canCommit = false;

	public static function connect()
	{
		if(!self::$_db)
		{
			$db = new PDO('mysql:host=' . Shape::getConf('bdd', 'host') . ';port=' . Shape::getConf('bdd', 'port') . ';dbname=' . Shape::getConf('bdd', 'base'), Shape::getConf('bdd', 'user'), Shape::getConf('bdd', 'password'), array(PDO::ATTR_PERSISTENT => true));
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$db->query("SET AUTOCOMMIT = 0;");
			$db->query("SET NAMES 'utf8'");
			self::$_db = $db;
		}
		return self::$_db;
	}

	public static function start()
	{
		$db = self::connect();
		$db->query("START TRANSACTION;");
		self::$_canCommit = true;
	}

	public static function commit()
	{
		if (self::$_canCommit)
		{
			$db = self::connect();
			$db->query("COMMIT;");
			self::start();
			return true;
		}
		return false;
	}

	public static function get()
	{
		return self::$_db;
	}

	public static function query($req)
	{
		return self::$_db->query($req);
	}

	public static function exec($req)
	{
		return self::$_db->exec($req);
	}

	public static function getValues($data, $with_key = false)
	{
		$rq = '';
		foreach ($data as $row) {
			$values = '';
			foreach ($row as $key => $value) {
				$values .= ($values ? ',' : '');
				if ($with_key)
					$values .= '`'.self::escape($key, '`').'` = ';
				if ($value instanceof MySQL_Expr)
					$values .= "($value)";
				else if ($value === NULL)
					$values .= "NULL";
				else
					$values .= "'".self::escape($value)."'";
			}
			$rq .= ($rq ? ',' : '') . "($values)";
		}
		return $rq;
	}

	public static function insert($table, $data)
	{
		$fields = '';
		$for_fields = $data;
		if (is_array($data[0]))
			$for_fields = $data[0];
		foreach ($for_fields as $key => $value) {
			$fields .= ($fields ? ',' : '').'`'.self::escape($key, '`').'`';
		}
		$values = self::getValues(is_array($data[0]) ? $data : array($data));
		return self::$_db->exec("INSERT INTO `".self::escape($table, '`')."` ($fields) VALUES $values;");
	}

	public static function update($table, $data, $where)
	{
		return self::$_db->exec("UPDATE `".self::escape($table, '`')."` SET " . substr(self::getValues(array($data), true), 1, -1) . " WHERE $where;");
	}

	public static function escape($rq, $escape = "'")
	{
		return str_replace(array($escape, '\\'), array($escape.$escape, '\\\\'), $rq);
	}
}

?>