<?php


class _Project
{
	static public $routages = array();

	public static function start()
	{
		session_start();


		date_default_timezone_set("Europe/Paris");

		putenv('LC_ALL=fr_FR');

		setlocale(LC_ALL, 'fr_FR.utf8', 'fra');


		require_once(dirname(__FILE__) . '/Errors.php');
	}

	public static function load($routages)
	{
		self::$routages = $routages;

		$base = '';
		if (isset($_SERVER['DOCUMENT_ROOT']))
			$base = substr(dirname(dirname(__FILE__)), strlen($_SERVER['DOCUMENT_ROOT']));

		foreach ($routages as $route)
		{
			if (preg_match('#^/?' . preg_quote($base) . $route['url'] . '/?$#isU', $_SERVER['REQUEST_URI'], $matches))
			{
				$module = $route['module'];
				$controller = $route['controller'];
				$action = $route['action'];
				foreach ($matches as $key => $value)
				{
					$module = str_replace('{' . $key . '}', $value, $module);
					$controller = str_replace('{' . $key . '}', $value, $controller);
					$action = str_replace('{' . $key . '}', $value, $action);
				}
				$module = preg_replace('#{(.*)}#', '', $module);
				$controller = preg_replace('#{(.*)}#', '', $controller);
				$action = preg_replace('#{(.*)}#', '', $action);
				echo $module . '/' . $controller . '/' . $action;
				// __construct du controller
				// On appelle init_global
				// On appelle init_module
				// On appelle init_controller
				// On appelle l'action
				// On appelle inter_controller
				// On appelle inter_module
				// On appelle inter_global
				// On appelle la view
				// On appelle end_controller
				// On appelle end_module
				// On appelle end_global
				return ;
			}
		}
		throw new Exception("Impossible de trouver le routage de cette URL.");
	}
}

?>