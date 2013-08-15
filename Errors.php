<?php


function gestionDesErreurs($type, $message, $fichier, $ligne)
{
	switch ($type)
	{
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_CORE_WARNING:
		case E_COMPILE_ERROR:
		case E_COMPILE_WARNING:
		case E_USER_ERROR:
			$type_erreur = "Erreur fatale";
			break;

		case E_WARNING:
		case E_USER_WARNING:
			$type_erreur = "Avertissement";
			break;

		case E_NOTICE:
		case E_USER_NOTICE:
			$type_erreur = "Remarque";
			break;

		case E_STRICT:
			$type_erreur = "Syntaxe Obsolète";
			break;

		default:
			$type_erreur = "Erreur inconnue";
	}


	//if (in_array($_SERVER["SERVER_NAME"], array('localhost', 'local', 'dewep', 'villers')))
	//{
		echo '<p><b>' . $type_erreur . '</b> : <b style="color:red">' . $message . '</b> (<b>' . $fichier . '</b> <span style="margin:0 5px">ligne</span> <b>' . $ligne . '</b>).</p>';
	//}

	//require_once (dirname(__FILE__) . '/Librairies.php');
	//Libs::alertes()->addAlerte("Erreur PHP", '[b]' . $type_erreur.'[/b] : [b]' . $message . '[/b] ligne ' . $ligne . ' (' . $fichier . ')');

}

function gestionDesExceptions($exception)  
{
	gestionDesErreurs(E_USER_ERROR, $exception->getMessage(), $exception->getFile(), $exception->getLine());  
}

function gestionDesErreursFatales()
{
	if (is_array($e = error_get_last()) && !(isset($_GET['action']) && $_GET['action'] == 'bdd'))
	{
		$type = isset($e['type']) ? $e['type'] : 0;
		$message = isset($e['message']) ? $e['message'] : '';
		$fichier = isset($e['file']) ? $e['file'] : '';
		$ligne = isset($e['line']) ? $e['line'] : '';

		if ($type > 0) gestionDesErreurs($type, $message, $fichier, $ligne);
	}
}

error_reporting(0);

set_error_handler('gestionDesErreurs');
set_exception_handler("gestionDesExceptions");
register_shutdown_function('gestionDesErreursFatales');


?>