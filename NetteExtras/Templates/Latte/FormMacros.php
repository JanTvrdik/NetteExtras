<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

namespace JanTvrdik\Templates;

use Nette;
use Nette\Templates\LatteFilter;
use Nette\Templates\LatteMacros;
use Nette\Templates\LatteException;

/**
 * Latte macros for comfortable form rendering.
 *
 * List of modifiers:
 * ------------------
 * {form ...} – class, style
 * {input ...} – value, caption, size, rows, cols, placeholder, class, style
 * {label ...} – text, class, style
 *
 * <code>
 * {form registrationForm}
 *   <p class="error" n:foreach="$formErrors as $error">{$error}</p>
 *   <table>
 *     <tr>
 *       <th>{label name, text => "Username"}</th>
 *       <td>{input name}</td>
 *     </tr>
 *     <tr>
 *       <th>{label password, text => "Password"}</th>
 *       <td>{input password}</td>
 *     </tr>
 *     {formContainer contact}
 *       <tr>
 *         <th>{label mail, text => "E-mail"}</th>
 *         <td>{input mail}</td>
 *       </tr>
 *       <tr>
 *         <th>{label twitter, text => "Twitter"}</th>
 *         <td>{input twitter}</td>
 *       </tr>
 *     {/formContainer}
 *     <tr>
 *       <th></th>
 *       <td>{input register, caption => "Register"}</td>
 *     </tr>
 *   </table>
 * {/form}
 * </code>
 *
 * @author   Jan Marek, Jan Tvrdík
 */
class FormMacros
{
	/** @var LatteMacros */
	private static $latte;

	/** @var Nette\Forms\FormContainer */
	private static $form;

	/** @var int */
	private static $containerLevel = 0;



	/**
	 * Static class – cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new \LogicException('Cannot instantiate static class ' . get_class($this));
	}



	/**
	 * Registers form macros.
	 *
	 * @return   void
	 */
	public static function register(LatteMacros $latte)
	{
		$latte->macros['form'] = '<?php %' . __CLASS__ . '::macroBegin% ?>';
		$latte->macros['/form'] = '<?php ' . __CLASS__ . '::endForm() ?>';
		$latte->macros['formContainer'] = '<?php %' . __CLASS__ . '::macroBeginContainer% ?>';
		$latte->macros['/formContainer'] = '<?php ' . __CLASS__ . '::endContainer() ?>';
		$latte->macros['input'] = '<?php %' . __CLASS__ . '::macroInput% ?>';
		$latte->macros['label'] = '<?php %' . __CLASS__ . '::macroLabel% ?>';

		self::$latte = $latte;
	}



	/**
	 * Returns current form.
	 *
	 * @return   Nette\Forms\Form
	 */
	public static function getForm()
	{
		return self::$form;
	}



	/**
	 * {form ...}
	 *
	 * @param    string
	 * @return   string
	 */
	public static function macroBegin($content)
	{
		list($name, $modifiers) = self::fetchNameAndModifiers($content);
		return '$formErrors = ' . __CLASS__ . "::beginForm($name, \$control, $modifiers)->getErrors()";
	}



	/**
	 * Helper for {form ...} macro.
	 *
	 * @param    Nette\Forms\Form|string form instance or form name in given control
	 * @param    Nette\Application\PresenterComponent
	 * @param    array             list of modifiers (name => value)
	 * @return   Nette\Forms\Form
	 */
	public static function beginForm($form, Nette\Application\PresenterComponent $control, array $modifiers = NULL)
	{
		self::$form = ($form instanceof Nette\Forms\Form ? $form : $control->getComponent($form));
		if ($modifiers) self::addAttributes(self::$form->getElementPrototype(), $modifiers, array('class', 'style'));
		self::$form->render('begin');
		return self::$form;
	}



	/**
	 * Helper for {/form} macro.
	 *
	 * @return   void
	 * @throws   LatteException    if some containers remain unclosed
	 */
	public static function endForm()
	{
		if (self::$containerLevel > 0) {
			throw new LatteException('There are some unclosed containers.');
		}
		self::$form->render('end');
	}



	/**
	 * {formContainer ...}
	 *
	 * @param    string
	 * @return   string
	 */
	public static function macroBeginContainer($content)
	{
		$name = LatteFilter::formatString(LatteFilter::fetchToken($content));
		return __CLASS__ . '::beginContainer(' . $name . ')';
	}



	/**
	 * Helper for {formContainer ...} macro.
	 *
	 * @param    string            container name
	 * @return   void
	 * @throws   LatteException    if container is not Nette\Forms\FormContainer instance
	 */
	public static function beginContainer($name)
	{
		$container = self::$form[$name];
		if (!$container instanceof Nette\Forms\FormContainer) {
			throw new LatteException('Form container must be instance of Nette\Forms\FormContainer.');
		}
		self::$form = $container;
		self::$containerLevel++;
	}



	/**
	 * Helper for {/formContainer} macro.
	 *
	 * @return   void
	 * @throws   LatteException    if there is no container to close
	 */
	public static function endContainer()
	{
		if (self::$containerLevel < 1) {
			throw new LatteException('Trying to close container which is not open.');
		}

		self::$form = self::$form->getParent();
		self::$containerLevel--;
	}



	/**
	 * {input ...}
	 *
	 * @param    string
	 * @return   string
	 */
	public static function macroInput($content)
	{
		list($name, $modifiers) = self::fetchNameAndModifiers($content);
		return __CLASS__ . "::input($name, $modifiers)";
	}



	/**
	 * Helper for {input ...} macro.
	 *
	 * @param    string            input name
	 * @param    array             list of modifiers (name => value)
	 * @return   void
	 */
	public static function input($name, array $modifiers = NULL)
	{
		$input = self::$form[$name]->getControl();
		if ($modifiers) {
			self::addAttributes($input, $modifiers, array('value', 'size', 'rows', 'cols', 'placeholder', 'class', 'style'));
			if (isset($modifiers['caption'])) $input->value = $modifiers['caption'];
		}
		echo $input;
	}



	/**
	 * {label ...}
	 *
	 * @param    string
	 * @return   string
	 */
	public static function macroLabel($content)
	{
		list($name, $modifiers) = self::fetchNameAndModifiers($content);
		return __CLASS__ . "::label($name, $modifiers)";
	}



	/**
	 * Helper for {label ...} macro.
	 *
	 * @param    string            input name
	 * @param    array             list of modifiers (name => value)
	 * @return   void
	 */
	public static function label($name, array $modifiers = NULL)
	{
		$label = self::$form[$name]->getLabel();
		if ($modifiers) {
			self::addAttributes($label, $modifiers, array('class', 'style'));
			if (isset($modifiers['text'])) $label->setText($modifiers['text']);
		}
		echo $label;
	}



	/**
	 * Parses given string a returns formatted name and modifiers as array.
	 *
	 * @param    string
	 * @return   array             0 => name, 1 => modifiers
	 */
	private static function fetchNameAndModifiers($code)
	{
		$name = self::$latte->formatString(self::$latte->fetchToken($code));
		$modifiers = self::$latte->formatArray($code) ?: 'array()';
		return array($name, $modifiers);
	}



	/**
	 * Adds allowed attributes to given element.
	 *
	 * @param    Nette\Web\Html
	 * @param    array             list of attributes (name => value)
	 * @param    array             list of allowed attributes (# => name)
	 * @return   void
	 */
	private static function addAttributes(Nette\Web\Html $el, array $attributes, array $allowedAttributes)
	{
		foreach ($attributes as $attribute => $value) {
			if (!in_array($attribute, $allowedAttributes)) continue;
			if (is_array($el->$attribute)) {
				$el->{$attribute}[] = $value;
			} else {
				$el->$attribute = $value;
			}
		}
	}

}
