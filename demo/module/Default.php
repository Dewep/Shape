<?php


class Module_Default extends Module_Common_Global
{
	public function init_module()
	{
		echo "init_module.\n";
	}

	public function inter_module()
	{
		echo "inter_module.\n";
	}

	public function end_module()
	{
		echo "end_module.\n";
	}
}


?>