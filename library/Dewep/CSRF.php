<?php

class Dewep_CSRF
{
	public static function check()
	{
		if (!count($_POST))
			return true;
		if (!self::checkReferer())
			return false;
		if (!Shape::getConf('security', 'csrf_protection'))
			return true;
		self::deleteOldsTokens();
		if (!isset($_SESSION['User']) || !isset($_SESSION['User']['Tokens']) || !isset($_POST['token']) || !isset($_SESSION['User']['Tokens'][$_POST['token']]))
		{
			$_POST = array();
			return false;
		}
		//unset($_SESSION['User']['Tokens'][$_POST['token']]);
		return true;
	}

	public static function get()
	{
		if (!Shape::getConf('security', 'csrf_protection'))
			return false;
		$token = sha1(Shape::getConf('security', 'high_salt') . md5('TOKEN' . time() . uniqid()));
		if (!isset($_SESSION['User']))
			$_SESSION['User'] = array();
		if (!isset($_SESSION['User']['Tokens']))
			$_SESSION['User']['Tokens'] = array();
		$_SESSION['User']['Tokens'][$token] = time();
		self::deleteOldsTokens();
		return $token;
	}

	public static function deleteOldsTokens()
	{
		if (!Shape::getConf('security', 'csrf_protection'))
			return false;
		if (!isset($_SESSION['User']))
			return false;
		if (!isset($_SESSION['User']['Tokens']))
			return false;
		foreach ($_SESSION['User']['Tokens'] as $token => $value)
		{
			if ($value + Shape::getConf('security', 'csrf_time_seconds') < time())
			{
				unset($_SESSION['User']['Tokens'][$token]);
			}
		}
		return true;
	}

	public static function checkReferer()
	{
		if (!Shape::getConf('security', 'referer_protection'))
			return true;
		$referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : false;
		if (!isset($_SERVER['HTTP_HOST']) || !$referer  || $referer['host'] != $_SERVER['HTTP_HOST'])
		{
			$_POST = array();
			return false;
		}
		return true;
	}
}

?>