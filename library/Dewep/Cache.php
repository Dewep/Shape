<?php

class Dewep_Cache
{
	public static $_caches = array();
	public static $_load = false;
	public static $_edit = false;

	public static function load()
	{
		if (!self::$_load)
			self::$_caches = array();
		self::$_load = true;
	}

	public static function has($name)
	{
		self::load();
		return isset(self::$_caches[$name]);
	}

	public static function get($name)
	{
		self::load();
		return self::has($name) ? self::$_caches[$name] : null;
	}

	public static function set($name, $value)
	{
		self::load();
		self::$_caches[$name] = $value;
		self::$_edit = true;
		return $value;
	}

	public static function store()
	{
		if (self::$_edit)
		{
			self::$_edit = false;
			return true;
		}
		return false;
	}
}

?>