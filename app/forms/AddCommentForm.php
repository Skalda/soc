<?php
namespace Form;

/**
 * @author Tomáš Skalický
 */
class AddCommentForm extends Base
{
	
	protected function buildForm() {
		$this->addTextArea('content', NULL, 80, 3)
		    ->setRequired('Vložte obsah komentáře.');
		$this->addHidden('post_id');
		$this->addSubmit('send', 'Vložit');
	}
}