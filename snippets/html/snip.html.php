<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets HTML Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Adam Fairholm
 */ 
class Snippet_html extends Snippet {

	/**
	 * Name of the Snippet
	 *
	 * @access	public
	 * @var		string
	 */
	public $name = 'HTML';
	
    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Slug
	 *
	 * @access	public
	 * @var		string
	 */
	public $slug = 'html';

	// --------------------------------------------------------------------------
	
	/**
	 * Form Input
	 *
	 * @access	public
	 * @param	string $value the form value
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
	 * We are going to save this using htmlspecialchars
	 * and then reverse it before we display it.
	 *
	 * @access	public
	 * @param	string $value
	 * @return 	string
	 */
	public function pre_save($value)
	{
		return htmlspecialchars($value);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Pre Output
	 *
	 * @access	public
	 * @param	string $value form value
	 * @param	array $params option parameters
	 * @return 	string
	 */
	public function pre_output($value, $params = array())
	{
		return htmlspecialchars_decode($value);
	}

}