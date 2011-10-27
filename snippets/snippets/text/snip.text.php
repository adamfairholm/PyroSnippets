<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Text Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Parse19
 */ 
class Snippet_text extends Snippet {

	public $name			= 'Text';
	
	public $slug			= 'text';

	public function form_output()
	{
		$form_data = array(
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => $this->value
		);

		return form_textarea($form_data);
	}

}