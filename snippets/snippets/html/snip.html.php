<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets HTML Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Parse19
 */ 
class Snippet_html extends Snippet {

	public $name			= 'HTML';
	
	public $slug			= 'html';
	
	public function form_output($value)
	{
		$form_data = array(
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => htmlspecialchars_decode($value)
		);

		return form_textarea($form_data);
	}

	public function pre_save($value)
	{
		return htmlspecialchars($value);
	}
	
	public function pre_output($value)
	{
		htmlspecialchars_decode($value);
	}

}