<?php


class _View
{
	private $_config;

	public function __construct($module, $controller, $action)
	{
		$this->_config = new stdClass();
		$this->_config->module = $module;
		$this->_config->controller = $controller;
		$this->_config->action = $action;
	}

	private function _require($file, $force)
	{
		if ($force)
			require($file);
		else
			require_once($file);
	}

	public function _run()
	{
		$this->_require(dirname(__FILE__) . '/../modules/Common/Views/base.phtml', true);
	}

	public function getModule()
	{
		return ($this->_config->module);
	}

	public function getController()
	{
		return ($this->_config->controller);
	}

	public function getAction()
	{
		return ($this->_config->action);
	}

	public function render($name, $force = false)
	{
		$name = basename($name) . '.phtml';
		if (file_exists(dirname(__FILE__) . '/../modules/Common/Views/' . $name))
			$this->_require(dirname(__FILE__) . '/../modules/Common/Views/' . $name, $force);
		else if (file_exists(dirname(__FILE__) . '/../modules/' . $this->getModule() . '/Views/Common/' . $name))
			$this->_require(dirname(__FILE__) . '/../modules/' . $this->getModule() . '/Views/Common/' . $name, $force);
		else if (file_exists(dirname(__FILE__) . '/../modules/' . $this->getModule() . '/Views/' . $this->getController() . '/' . $name))
			$this->_require(dirname(__FILE__) . '/../modules/' . $this->getModule() . '/Views/' . $this->getController() . '/' . $name, $force);
		else
			throw new Exception("Impossible de trouver la vue.");
	}

	public function renderAction($force = false)
	{
		$name = $this->getAction() . '.phtml';
		if (file_exists(dirname(__FILE__) . '/../modules/' . $this->getModule() . '/Views/' . $this->getController() . '/' . $name))
			$this->_require(dirname(__FILE__) . '/../modules/' . $this->getModule() . '/Views/' . $this->getController() . '/' . $name, $force);
		else
			throw new Exception("Impossible de trouver la vue.");
	}
}


?>