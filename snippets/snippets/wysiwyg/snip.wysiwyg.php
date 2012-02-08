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

	/**
	 * Name of the Snippet
	 *
	 * @access	public
	 * @var		string
	 */
	public $name			= 'WYSIWYG';

    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Slug
	 *
	 * @access	public
	 * @var		string
	 */
	public $slug			= 'wysiwyg';

    // --------------------------------------------------------------------------
	
	/**
	 * Form Input
	 *
	 * @access	public
	 * @param	string - form value
	 * @return 	string
	 */
	public function form_output($value)
	{
		$form_data = array(
			'class'		  => 'wysiwyg-advanced',
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => htmlspecialchars_decode($value)
		);
		
		$this->ci->load->helper('html');

		return br().br().form_textarea($form_data);
	}

    // --------------------------------------------------------------------------

	/**
	 * Event - add WYSIWYG items to the header
	 *
	 * @access	public
	 * @return	void
	 */	
	public function event()
	{
		$this->ci->template->append_metadata(get_instance()->load->view('fragments/wysiwyg', array(), TRUE));
	}

}