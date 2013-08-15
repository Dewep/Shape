<?php

class Dewep_MySQL2
{
	public static $_db;

	public static function connect()
	{
		if(!self::$_db)
		{
			$db = new PDO('mysql:host=' . Shape::getConf('bdd', 'host') . ';port=' . Shape::getConf('bdd', 'port') . ';dbname=' . Shape::getConf('bdd', 'base'), Shape::getConf('bdd', 'user'), Shape::getConf('bdd', 'password'), array(PDO::ATTR_PERSISTENT => true));
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$db->query("SET NAMES 'utf8'");
			self::$_db = $db;
		}
		return self::$_db;
	}
}

?>