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
	public $name = 'WYSIWYG';

    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Slug
	 *
	 * @access	public
	 * @var		string
	 */
	public $slug = 'wysiwyg';

    // --------------------------------------------------------------------------

	/**
	 * Snippet Parameters
	 *
	 * @access	public
	 * @var		array
	 */	
	public $parameters = array('editor_type');

    // --------------------------------------------------------------------------
	
	/**
	 * Form Input
	 *
	 * @access	public
	 * @param	string - form value
	 * @return 	string
	 */
	public function form_output($value, $params)
	{
		$class = (isset($params['editor_type'])) ? $params['editor_type'] : 'wysiwyg-advanced';

		$form_data = array(
			'class'		  => $class,
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => htmlspecialchars_decode($value)
		);
		
		$this->ci->load->helper('html');

		return br().br().form_textarea($form_data);
	}

	// --------------------------------------------------------------------------

	/**
	 * Editor Type
	 *
	 * Choose the type of WYSIWYG editor.
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function param_editor_type($value = null)
	{
		$types = array(
				'wysiwyg-simple' 	=> lang('streams.wysiwyg.simple'),
				'wysiwyg-advanced' 	=> lang('streams.wysiwyg.advanced')
			);

		return form_dropdown('editor_type', $types, $value);
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
		$this->ci->template->append_metadata(get_instance()->load->view('fragments/wysiwyg', array(), true));
	}

}