<?php
/**
 * Addons and code snippets for Nette Framework. (unofficial)
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */

use Nette\Debug;
use Nette\Application\AppForm as Form;

/**
 * Presenter for showing demos.
 *
 * @author   Jan Tvrdík
 */
final class DemoPresenter extends BasePresenter
{
	/**
	 * List of all available demos.
	 *
	 * @author   Jan Tvrdík
	 */
	public function renderDefault()
	{

	}

	/**
	 * DatePicker demo.
	 *
	 * @author   Jan Tvrdík
	 */
	public function renderDatepicker()
	{

	}

	/**
	 * Submit handler for DatePickerForm.
	 *
	 * @author   Jan Tvrdík
	 * @param    Form
	 * @return   void
	 */
	public function datePickerFormSubmitted(Form $form)
	{
		$this->template->data = Debug::dump($form->values, TRUE);
	}

	/**
	 * DatePickerForm factory.
	 *
	 * @author   Jan Tvrdík
	 * @return   Form
	 */
	protected function createComponentDatePickerForm()
	{
		$form = new Form();
		$form->addDatePicker('datePicker1');
		$form->addDatePicker('datePicker2')
			->addRule(Form::FILLED, 'Date is required')
			->addRule(Form::VALID, 'Entered date is not valid!');
		$form->addDatePicker('datePicker3')
			->addRule(Form::VALID, 'Entered date is not valid!')
			->addCondition(Form::FILLED)
				->addRule(Form::RANGE, 'Entered date is not within allowed range.', array(new DateTime('-14 days'), new DateTime('+14 days')));
		$form->addSubmit('submit');
		$form->onSubmit[] = callback($this, 'datePickerFormSubmitted');
		return $form;
	}

}
