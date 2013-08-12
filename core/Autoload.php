<?php


function __autoload($name)
{
	$base = ($name[0] == '_') ? 'core' : 'library';
	require_once(dirname(__FILE__) . '/../' . $base . '/' . str_replace('_', '/', $name)) . '.php';
}


?>