<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

use Nette\Application\Routers\Route;
use Nette\Config\Configurator;
use Nette\Forms;

require_once LIBS_DIR . '/Nette/loader.php';

$configurator = new Configurator();
$configurator->enableDebugger();
$configurator->setTempDirectory(TEMP_DIR);
$configurator->createRobotLoader()
	->addDirectory([APP_DIR, LIBS_DIR, APP_DIR . '/../../NetteExtras/Components', APP_DIR . '/../../NetteExtras/Templates'])
	->register();

$dic = $configurator->createContainer();
$dic->router[] = new Route('index.php', 'Demo:default', Route::ONE_WAY);
$dic->router[] = new Route('<action>', 'Demo:default');

Forms\Container::extensionMethod('addDatePicker', function (Forms\Container $container, $name, $label = NULL) {
	return $container[$name] = new JanTvrdik\Components\DatePicker($label);
});

$dic->application->run();
