<?php


class Shape_Core
{
	protected $view;
	private $_fileView;
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
		$this->_fileView = 'base';
		Dewep_MySQL::start();
		Dewep_CSRF::check();
	}

	public function end_core()
	{
		Dewep_MySQL::commit();
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

	public function baseUrl($path = '')
	{
		return Shape::baseUrl($path);
	}

	public function generateView_core()
	{
		$this->view->_run($this->_fileView);
	}

	public function setView($file)
	{
		$this->_fileView = $file;
	}

	public function getParam($key)
	{
		if (isset($this->_params[$key]))
			return $this->_params[$key];
		return false;
	}
}


?>