<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Text Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Adam Fairholm
 */ 
class Snippet_text extends Snippet {

	/**
	 * Name of the Snippet
	 *
	 * @access	public
	 * @var		string
	 */
	public $name = 'Text';

    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Slug
	 *
	 * @access	public
	 * @var		string
	 */	
	public $slug = 'text';

	// --------------------------------------------------------------------------

	/**
	 * Form Input
	 *
	 * @access	public
	 * @param	string $value form value
	 * @return 	string
	 */
	public function form_output($value)
	{
		$form_data = array(
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => $value
		);

		return form_textarea($form_data);
	}

}