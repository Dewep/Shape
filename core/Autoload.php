<?php


function __autoload($name)
{
	$root = dirname(__FILE__) . '/../';

	if (substr($name, 0, 8) == 'Modules_')
	{
		$name = substr($name, 8);
		$dec = explode('_', $name);
		if (count($dec) >= 2)
		{
			$end = $dec[count($dec) - 1];
			$dec[count($dec) - 1] = null;
			if (file_exists($root . 'modules/' . implode('/', $dec) . '/Controllers/' . $end . '.php'))
			{
				require_once($root . 'modules/' . implode('/', $dec) . '/Controllers/' . $end . '.php');
				return true;
			}
		}
		else if (file_exists($root . 'modules/' . str_replace('_', '/', $name) . '.php'))
		{
			require_once($root . 'modules/' . str_replace('_', '/', $name) . '.php');
			return true;
		}
	}
	else if (substr($name, 0, 1) == '_')
	{
		$name = substr($name, 1);
		if (file_exists($root . 'core/' . str_replace('_', '/', $name) . '.php'))
		{
			require_once($root . 'core/' . str_replace('_', '/', $name) . '.php');
			return true;
		}
	}
	else
	{
		if (file_exists($root . 'library/' . str_replace('_', '/', $name) . '.php'))
		{
			require_once($root . 'library/' . str_replace('_', '/', $name) . '.php');
			return true;
		}
	}
	return false;
}


?>