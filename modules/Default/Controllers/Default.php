<?php


class Modules_Default_Default extends Modules_Default
{
	public function init_controller()
	{
		echo "init_controller.\n";
	}

	public function inter_controller()
	{
		echo "inter_controller.\n";
	}

	public function end_controller()
	{
		echo "end_controller.\n";
	}

	public function indexAction()
	{
		$this->view->test = 'toto';
		$this->view->salut = 'coucou';
		$this->view->list = array('1', '2' => 5);
		echo "action\n";
	}
}


?>