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
 * @version  0.1
 * @link     http://nette.merxes.cz/base-control/
 */
abstract class BaseControl extends Nette\Application\Control
{
	/** @var     bool              automatically derive template path from class name */
	protected $autoSetupTemplateFile = TRUE;



	/**
	 * Automatically registers template file.
	 *
	 * @author   Jan Tvrdík
	 * @return   Nette\Templates\FileTemplate
	 */
	protected function createTemplate()
	{
		$template = parent::createTemplate();
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
