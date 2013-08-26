<?php

class Dewep_CSRF
{
	public static function check()
	{
		if (!self::checkReferer())
			return false;
		if (!Shape::getConf('security', 'csrf_protection'))
			return true;
		self::deleteOldsTokens();
		if (!isset($_SESSION['tokens']) || !isset($_POST['token']) || !isset($_SESSION['tokens'][$_POST['token']]))
		{
			$_POST = array();
			return false;
		}
		unset($_SESSION['tokens'][$_POST['token']]);
		return true;
	}

	public static function get()
	{
		if (!Shape::getConf('security', 'csrf_protection'))
			return false;
		$token = sha1(Shape::getConf('security', 'high_salt') . md5('TOKEN' . time() . uniqid()));
		if (!isset($_SESSION['tokens']))
			$_SESSION['tokens'] = array();
		$_SESSION['tokens'][$token] = time();
		self::deleteOldsTokens();
		return $token;
	}

	public static function deleteOldsTokens()
	{
		if (!Shape::getConf('security', 'csrf_protection'))
			return false;
		if (!isset($_SESSION['tokens']))
			return false;
		foreach ($_SESSION['tokens'] as $token => $value)
		{
			if ($value + Shape::getConf('security', 'csrf_time_seconds') < time())
			{
				unset($_SESSION['tokens'][$token]);
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