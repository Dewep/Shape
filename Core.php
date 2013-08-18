<?php


class Shape_Core
{
	protected $view;
	private $_params;
	private $_config;

	public function init_core($module, $controller, $action, $params)
	{
		$this->view = new Shape_View($module, $controller, $action);
		$this->_config = new stdClass();
		$this->_config->module = $module;
		$this->_config->controller = $controller;
		$this->_config->action = $action;
		$this->_params = $params;
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

	public function generateView_core()
	{
		$this->view->_run();
	}

	public function getParam($key)
	{
		if (isset($this->_params[$key]))
			return $this->_params[$key];
		return false;
	}
}


?>