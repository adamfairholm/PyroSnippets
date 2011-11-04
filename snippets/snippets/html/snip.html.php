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
			'name'        => $this->input_name,
			'id'          => $this->input_name,
			'value'       => htmlspecialchars_decode($value)
		);

		return form_textarea($form_data);
	}

	// --------------------------------------------------------------------------

	/**
	 * Pre Saving to Database
	 *
	 * @access	public
	 * @param	string - form value
	 * @return 	string
	 */
	public function pre_save($value)
	{
		return htmlspecialchars($value);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Form Input
	 *
	 * @access	public
	 * @param	string - form value
	 * @Param	[array] - option parameters
	 * @return 	string
	 */
	public function pre_output($value, $params = array())
	{
		return htmlspecialchars_decode($value);
	}

}