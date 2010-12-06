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
			->setDateFormat('yy-mm-dd')
			->setDefaultValue(new DateTime('2010-09-01'));
		$form->addDatePicker('datePicker3', NULL, new DateTime('-14 days'), new DateTime('+14 days'))
			->addRule(Form::VALID, 'Entered date is not valid!');
		$form->addSubmit('submit');
		$form->onSubmit[] = callback($this, 'datePickerFormSubmitted');
		return $form;
	}

}
