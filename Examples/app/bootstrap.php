<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

use Nette\Diagnostics\Debugger;
use Nette\Application\Routers\Route;
use Nette\Environment as Env;
use Nette\Forms\Container;

require LIBS_DIR . '/Nette/loader.php';

Debugger::$strictMode = TRUE;
Debugger::enable();

Env::loadConfig();

$application = Env::getApplication();
$router = $application->getRouter();
$router[] = new Route('index.php', 'Demo:default', Route::ONE_WAY);
$router[] = new Route('<action>', 'Demo:default');

Container::extensionMethod('addDatePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new JanTvrdik\Components\DatePicker($label);
});

$application->run();
