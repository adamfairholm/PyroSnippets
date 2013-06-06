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
	 * @var		string
	 */
	public $name = 'WYSIWYG';
	
	/**
	 * Snippet Slug
	 *
	 * @var		string
	 */
	public $slug = 'wysiwyg';

	/**
	 * Snippet Parameters
	 *
	 * @var		array
	 */	
	public $parameters = array('editor_type');
	
	/**
	 * Form Input
	 *
	 * @param	string 	$value 	form value
	 * @param 	array 	$params
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

	/**
	 * Editor Type
	 *
	 * Choose the type of WYSIWYG editor.
	 *
	 * @param	string
	 * @return	string
	 */
	public function param_editor_type($value = null)
	{
		$types = array(
				'wysiwyg-simple' 	=> lang('streams:wysiwyg.simple'),
				'wysiwyg-advanced' 	=> lang('streams:wysiwyg.advanced')
			);

		return form_dropdown('editor_type', $types, $value);
	}

	/**
	 * Event
	 *
	 * Used to add WYSIWYG items to the header
	 *
	 * @return	void
	 */	
	public function event()
	{
		$this->ci->template->append_metadata(get_instance()->load->view('fragments/wysiwyg', array(), true));
	}

}