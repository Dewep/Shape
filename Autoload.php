<?php


function __autoload($name)
{
	if (substr($name, 0, 7) == 'Module_')
	{
		$name = substr($name, 7);
		$dec = explode('_', $name);
		if (count($dec) >= 2)
		{
			$end = $dec[count($dec) - 1];
			$dec[count($dec) - 1] = null;
			if (file_exists(Shape::$root . Shape::getConf('shape', 'module') . '/' . implode('/', $dec) . '/Controllers/' . $end . '.php'))
			{
				require_once(Shape::$root . Shape::getConf('shape', 'module') . '/' . implode('/', $dec) . '/Controllers/' . $end . '.php');
				return true;
			}
		}
		else if (file_exists(Shape::$root . Shape::getConf('shape', 'module') . '/' . str_replace('_', '/', $name) . '.php'))
		{
			require_once(Shape::$root . Shape::getConf('shape', 'module') . '/' . str_replace('_', '/', $name) . '.php');
			return true;
		}
	}
	else
	{
		if (file_exists(Shape::$root . Shape::getConf('shape', 'library') . '/' . str_replace('_', '/', $name) . '.php'))
		{
			require_once(Shape::$root . Shape::getConf('shape', 'library') . '/' . str_replace('_', '/', $name) . '.php');
			return true;
		}
	}
	return false;
}


?>