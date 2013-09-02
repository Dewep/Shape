<?php

class Dewep_MySQL_Expr
{
	private $sql = '';

	public function __construct($sql)
	{
		$this->sql = $sql;
	}

	public function __toString()
	{
		return $this->sql;
	}
}

?>