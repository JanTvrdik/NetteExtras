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
	 * @todo     Find a better way to register FormMacros!
	 *
	 * @author   Patrik Votoček, David Grudl
	 * @license  CC BY-SA 3.0
	 * @param    Nette\Templating\Template
	 * @return   void
	 */
	public function templatePrepareFilters($template)
	{
		$template->registerFilter(function($s) {
			$parser = new Nette\Latte\Parser;
			$parser->setDelimiters('\\{(?![\\s\'"{}*])', '\\}');

			// context-aware escaping
			$parser->escape = '$template->escape';

			// initialize handlers
			$parser->handler = new Nette\Latte\DefaultMacros;
			JanTvrdik\Templates\FormMacros::register($parser->handler);
			$parser->handler->initialize($parser, $s);

			// process all {tags} and <tags/>
			$s = $parser->parse($s);

			$parser->handler->finalize($s);

			return $s;
		});
	}
}
