<?php


class Shape
{
	public $routages = array();
	static $config = array();
	static $root = '';

	public function __construct($root)
	{
		session_start();


		date_default_timezone_set("Europe/Paris");

		putenv('LC_ALL=fr_FR');

		setlocale(LC_ALL, 'fr_FR.utf8', 'fra');


		require_once(dirname(__FILE__) . '/Errors.php');

		self::$root = rtrim($root, '/') . '/';
		$this->read_conf(dirname(__FILE__) . 'config.ini');
		$this->read_conf(self::$root . 'config.ini');
		$this->read_conf(self::$root . 'config.local.ini');

		require_once(dirname(__FILE__) . '/Autoload.php');
		require_once(dirname(__FILE__) . '/View.php');
		require_once(dirname(__FILE__) . '/Core.php');
	}

	private function read_conf($file)
	{
		if (file_exists($file))
			self::$config = array_replace_recursive(self::$config, parse_ini_file($file, true));
	}

	public static function getConf($section, $key)
	{
		if (!isset(self::$config[$section][$key]))
			throw new Exception("Impossible de trouver cette variable config : " . $key);
		return (self::$config[$section][$key]);
	}

	public function load($routages)
	{
		$this->routages = $routages;
		$base = self::getConf('shape', 'base_url');

		foreach ($routages as $route)
		{
			if (preg_match('#^/?' . preg_quote(rtrim($base, '/')) . '/' . trim($route['url'], '/') . '/?$#isU', $_SERVER['REQUEST_URI'], $matches))
			{
				$module = $route['module'];
				$controller = $route['controller'];
				$action = $route['action'];
				$params = $route['param'];
				foreach ($matches as $key => $value)
				{
					$module = str_replace('{' . $key . '}', $value, $module);
					$controller = str_replace('{' . $key . '}', $value, $controller);
					$action = str_replace('{' . $key . '}', $value, $action);
					$params = str_replace('{' . $key . '}', $value, $params);
				}
				$module = ucfirst(basename(preg_replace('#{(.*)}#', '', $module)));
				$controller = ucfirst(basename(preg_replace('#{(.*)}#', '', $controller)));
				$action = ucfirst(basename(preg_replace('#{(.*)}#', '', $action)));

				$real_module = $module;
				$real_controller = 'Module_' . $module . '_' . $controller;
				$real_action = $action . 'Action';

				if (!file_exists(self::$root . self::getConf('shape', 'module') . '/' . $real_module))
					throw new Exception("Impossible de trouver ce module.");
				if (!class_exists($real_controller))
					throw new Exception("Impossible de trouver ce controller.");
				if (!method_exists($real_controller, $real_action))
					throw new Exception("Impossible de trouver cette action.");

				$page = new $real_controller();
				$page->init_core($module, $controller, $action, $params);
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
				$page->generateView_core();
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