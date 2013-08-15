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
			$base = substr(dirname(dirname(__FILE__)), strlen($_SERVER['DOCUMENT_ROOT'])) . '/www/';

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
				$module = ucfirst(basename(preg_replace('#{(.*)}#', '', $module)));
				$controller = ucfirst(basename(preg_replace('#{(.*)}#', '', $controller)));
				$action = ucfirst(basename(preg_replace('#{(.*)}#', '', $action)));

				$real_module = $module;
				$real_controller = 'Modules_' . $module . '_' . $controller;
				$real_action = $action . 'Action';

				if (!file_exists(dirname(__FILE__) . '/../modules/' . $real_module))
					throw new Exception("Impossible de trouver ce module.");
				if (!class_exists($real_controller))
					throw new Exception("Impossible de trouver ce controller.");
				if (!method_exists($real_controller, $real_action))
					throw new Exception("Impossible de trouver cette action.");

				$page = new $real_controller();
				if (method_exists($page, 'init_core'))
					$page->init_core($module, $controller, $action);
				if (method_exists($page, 'init_global'))
					$page->init_global();
				if (method_exists($page, 'init_module'))
					$page->init_module();
				if (method_exists($page, 'init_controller'))
					$page->init_controller();
				$page->$real_action();
				if (method_exists($page, 'inter_core'))
					$page->inter_core();
				if (method_exists($page, 'inter_controller'))
					$page->inter_controller();
				if (method_exists($page, 'inter_module'))
					$page->inter_module();
				if (method_exists($page, 'inter_global'))
					$page->inter_global();
				$page->generateView();
				if (method_exists($page, 'end_controller'))
					$page->end_controller();
				if (method_exists($page, 'end_module'))
					$page->end_module();
				if (method_exists($page, 'end_global'))
					$page->end_global();
				if (method_exists($page, 'end_core'))
					$page->end_core();
				return ;
			}
		}
		throw new Exception("Impossible de trouver le routage de cette URL.");
	}
}

?>