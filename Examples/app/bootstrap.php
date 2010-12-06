<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

use Nette\Debug;
use Nette\Application\Route;
use Nette\Environment as Env;
use Nette\Forms\FormContainer;

require LIBS_DIR . '/Nette/loader.php';

Debug::$strictMode = TRUE;
Debug::enable();

Env::loadConfig();

$application = Env::getApplication();
$router = $application->getRouter();
$router[] = new Route('index.php', 'Demo:default', Route::ONE_WAY);
$router[] = new Route('<action>', 'Demo:default');

FormContainer::extensionMethod('addDatePicker', function (FormContainer $container, $name, $label = NULL, DateTime $minDate = NULL, DateTime $maxDate = NULL) {
	return $container[$name] = new JanTvrdik\Components\DatePicker($label, $minDate, $maxDate);
});

$application->run();
