<?php
namespace Form;

use	Nette;

/**
 * @author Tomáš Skalický
 */
abstract class Base extends \Nette\Application\UI\Form 
{
	/** @var \GettextTranslator\Gettext */
	protected $translator;
	
	public function __construct(Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		\Nette\Forms\Container::extensionMethod('addDatePicker', function (\Nette\Forms\Container $container, $name, $label = NULL) {
		    return $container[$name] = new \JanTvrdik\Components\DatePicker($label);
		});
		/** @var \Nette\Forms\Rendering\DefaultFormRenderer */
		$renderer = $this->getRenderer();
		if($renderer instanceof Nette\Forms\Rendering\DefaultFormRenderer) {
			
		}
		$this->setTranslator($this->translator);
		$this->buildForm();
	}
	
	/**
	 * Abstract function which handles the form creation.
	 * @abstract
	 * @return void
	 */
	protected abstract function buildForm();
}
