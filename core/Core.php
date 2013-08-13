<?php


class _Core
{
	protected $view;

	public function init_core()
	{
		$this->view = new _View();
		echo "init_core.\n";
	}

	public function inter_core()
	{
		echo "inter_core.\n";
	}

	public function end_core()
	{
		echo "end_core.\n";
	}

	public function generateView()
	{
		//$this->view->run();
		echo "generateView.\n";
	}
}


?>