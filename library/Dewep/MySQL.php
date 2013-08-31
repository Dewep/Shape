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

	public static function escape($rq, $guillemets = false)
	{
		if ($guillemets)
			return str_replace('"', '""', $rq);
		return str_replace("'", "''", $rq);
	}
}

?>