<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

namespace JanTvrdik\Components;

use Nette;
use DateTime;

/**
 * Form control for selecting date.
 *
 * Compatible with jQuery UI DatePicker and HTML 5
 *
 * @author   Jan Tvrdík
 */
class DatePicker extends Nette\Forms\FormControl
{
	/** @link    http://dev.w3.org/html5/spec/common-microsyntaxes.html#valid-date-string */
	const W3C_DATE_FORMAT = 'Y-m-d';

	/** @var     DateTime|NULL     internal date reprezentation */
	protected $value;

	/** @var     string            unfiltered, by user entered value */
	protected $rawValue;

	/** @var     string            class name */
	private $className = 'date';

	/**
	 * @var      string            date format for jQuery UI DatePicker
	 * @link     http://docs.jquery.com/UI/Datepicker/formatDate
	 */
	private $dateFormat = 'd. m. yy';

	/** @var     DateTime|NULL     minimum selectable date */
	private $minDate;

	/** @var     DateTime|NULL     maximum selectable date */
	private $maxDate;



	/**
	 * Class constructor.
	 *
	 * @author   Jan Tvrdík
	 * @param    string            label
	 * @param    DateTime          minimum selectable date
	 * @param    DateTime          maximum selectable date
	 */
	public function __construct($label = NULL, DateTime $minDate = NULL, DateTime $maxDate = NULL)
	{
		parent::__construct($label);
		$this->control->type = 'date';
		$this->minDate = $minDate;
		$this->maxDate = $maxDate;
	}



	/**
	 * Returns class name.
	 *
	 * @author   Jan Tvrdík
	 * @return   string
	 */
	public function getClassName()
	{
		return $this->className;
	}



	/**
	 * Sets class name for input element.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   self
	 */
	public function setClassName($className)
	{
		$this->className = $className;
		return $this;
	}



	/**
	 * Returns date format for jQuery UI DatePicker.
	 *
	 * @author   Jan Tvrdík
	 * @return   string
	 */
	public function getDateFormat()
	{
		return $this->dateFormat;
	}



	/**
	 * Sets date format for jQuery UI DatePicker.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   self
	 */
	public function setDateFormat($dateFormat)
	{
		$this->dateFormat = $dateFormat;
		return $this;
	}



	/**
	 * Returns minimum selectable date.
	 *
	 * @author   Jan Tvrdík
	 * @return   DateTime|NULL
	 */
	public function getMinDate()
	{
		return $this->minDate;
	}



	/**
	 * Sets minimum selectable date.
	 *
	 * @author   Jan Tvrdík
	 * @param    DateTime|NULL
	 * @return   self
	 */
	public function setMinDate($minDate)
	{
		if (isset($minDate) && !$minDate instanceof DateTime)
			throw new \InvalidArgumentException('Parameter $minDate must be instance of DateTime or NULL.');
		$this->minDate = $minDate;
		return $this;
	}



	/**
	 * Returns maximum selectable date.
	 *
	 * @author   Jan Tvrdík
	 * @return   DateTime|NULL
	 */
	public function getMaxDate()
	{
		return $this->maxDate;
	}



	/**
	 * Sets maximum selectable date.
	 *
	 * @author   Jan Tvrdík
	 * @param    DateTime|NULL
	 * @return   self
	 */
	public function setMaxDate($maxDate)
	{
		if (isset($maxDate) && !$maxDate instanceof DateTime)
			throw new \InvalidArgumentException('Parameter $maxDate must be instance of DateTime or NULL.');
		$this->maxDate = $maxDate;
		return $this;
	}



	/**
	 * Generates control's HTML element.
	 *
	 * @author   Jan Tvrdík
	 * @return   Nette\Web\Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		if ($this->minDate) $control->min = $this->minDate->format(self::W3C_DATE_FORMAT);
		if ($this->maxDate) $control->max = $this->maxDate->format(self::W3C_DATE_FORMAT);
		if ($this->value) $control->value = $this->value->format(self::W3C_DATE_FORMAT);
		$control->data['datepicker-dateformat'] = $this->dateFormat;
		$control->class[] = $this->className;

		return $control;
	}



	/**
	 * Sets DatePicker value.
	 *
	 * @author   Jan Tvrdík
	 * @param    DateTime|int|string
	 * @return   self
	 */
	public function setValue($value)
	{
		if ($value instanceof DateTime) {

		} elseif (is_int($value)) { // timestamp

		} elseif (empty($value)) {
			$rawValue = $value;
			$value = NULL;

		} elseif (is_string($value)) {
			$rawValue = $value;

			if (preg_match('#^(?P<dd>\d{1,2})[. -] *(?P<mm>\d{1,2})([. -] *(?P<yyyy>\d{4})?)?$#', $value, $matches)) {
				$dd = $matches['dd'];
				$mm = $matches['mm'];
				$yyyy = isset($matches['yyyy']) ? $matches['yyyy'] : date('Y');

				if (checkdate($mm, $dd, $yyyy)) {
					$value = "$yyyy-$mm-$dd";
				} else {
					$value = NULL;
				}
			}

		} else {
			throw new \InvalidArgumentException();
		}

		if ($value !== NULL) {
			// DateTime constructor throws Exception when invalid input given
			try {
				$value = Nette\Tools::createDateTime($value); // clone DateTime when given
			} catch (\Exception $e) {
				$value = NULL;
			}
		}

		if (!isset($rawValue) && isset($value)) {
			$rawValue = $value->format($this->dateFormat);
		}

		$this->value = $value;
		$this->rawValue = $rawValue;

		return $this;
	}



	/**
	 * Returns unfiltered value.
	 *
	 * @author   Jan Tvrdík
	 * @return   string
	 */
	public function getRawValue()
	{
		return $this->rawValue;
	}



	/**
	 * Does user enter anything? (the value doesn't have to be valid)
	 *
	 * @author   Jan Tvrdík
	 * @param    DatePicker
	 * @return   bool
	 */
	public static function validateFilled(Nette\Forms\IFormControl $control)
	{
		if (!$control instanceof self) throw new \InvalidStateException('Unable to validate ' . get_class($control) . ' instance.');
		$rawValue = $control->rawValue;
		return !empty($rawValue);
	}



	/**
	 * Is entered value valid? (empty value is also valid!)
	 *
	 * @author   Jan Tvrdík
	 * @param    DatePicker
	 * @return   bool
	 */
	public static function validateValid(Nette\Forms\IFormControl $control)
	{
		if (!$control instanceof self) throw new \InvalidStateException('Unable to validate ' . get_class($control) . ' instance.');
		$value = $control->value;
		return (empty($control->rawValue) || ($value instanceof DateTime
			&& (!$control->minDate || $value >= $control->minDate)
			&& (!$control->maxDate || $value <= $control->maxDate)));
	}

}
