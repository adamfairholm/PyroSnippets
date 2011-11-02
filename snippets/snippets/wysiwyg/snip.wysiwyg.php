<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets WYSIWYG Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Parse19
 */ 
class Snippet_wysiwyg extends Snippet {

	public $name			= 'WYSIWYG';
	
	public $slug			= 'wysiwyg';

    // --------------------------------------------------------------------------
	
	/**
	 * Form Output
	 *
	 * @access	public
	 * @return	string
	 */
	public function form_output($value)
	{
		$form_data = array(
			'class'		  => 'wysiwyg-advanced',
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => htmlspecialchars_decode($value)
		);

		return form_textarea($form_data);
	}

    // --------------------------------------------------------------------------

	/**
	 * Event - add WYSIWYG items
	 *
	 * @access	public
	 * @return	void
	 */	
	public function event()
	{
		get_instance()->template->append_metadata(get_instance()->load->view('fragments/wysiwyg', array(), TRUE));
	}

}