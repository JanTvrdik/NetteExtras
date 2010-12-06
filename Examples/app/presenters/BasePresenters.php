<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

use Nette\Debug;

/**
 * Base class for all presenters.
 *
 * @author   Jan Tvrdík
 */
abstract class BasePresenter extends Nette\Application\Presenter
{
	/**
	 * Setup templates filters.
	 *
	 * @author   Jan Tvrdík
	 * @param    Nette\Templates\Template
	 * @return   void
	 */
	public function templatePrepareFilters($template)
	{
		$latte = new Nette\Templates\LatteFilter();
		JanTvrdik\Templates\FormMacros::register($latte->getHandler());
		$template->registerFilter($latte);
	}
}
