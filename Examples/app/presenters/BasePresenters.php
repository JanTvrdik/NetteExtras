<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

use Nette\Diagnostics\Debugger;

/**
 * Base class for all presenters.
 *
 * @author   Jan Tvrdík
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Setup templates filters.
	 *
	 * @author   Jan Tvrdík
	 * @param    Nette\Templating\Template
	 * @return   void
	 */
	public function templatePrepareFilters($template)
	{
		$latte = new Nette\Latte\Engine();
		JanTvrdik\Templates\FormMacros::register($latte->getHandler());
		$template->registerFilter($latte);
	}
}
