<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

namespace JanTvrdik\CodeSnippets;

use Nette;

/**
 * Base control for all controls in application.
 *
 * @author   Jan Tvrdík
 * @version  0.3
 * @link     http://nette.merxes.cz/base-control/
 */
abstract class BaseControl extends Nette\Application\UI\Control
{
	/** @var     bool              automatically derive template path from class name */
	protected $autoSetupTemplateFile = TRUE;



	/**
	 * Automatically registers template file.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   Nette\Templating\FileTemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		if ($this->autoSetupTemplateFile) $template->setFile($this->getTemplateFilePath());

		return $template;
	}



	/**
	 * Derives template path from class name.
	 *
	 * @author   Jan Tvrdík
	 * @return   string
	 */
	protected function getTemplateFilePath()
	{
		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$filename = $reflection->getShortName() . '.latte';
		return $dir . \DIRECTORY_SEPARATOR . $filename;
	}

}
