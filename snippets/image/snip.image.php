<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Image Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Adam Fairholm
 */ 
class Snippet_image extends Snippet {

	/**
	 * Name of the Snippet
	 *
	 * @access	public
	 * @var		string
	 */
	public $name = 'Image';
	
    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Slug
	 *
	 * @access	public
	 * @var		string
	 */
	public $slug = 'image';

	// --------------------------------------------------------------------------

	/**
	 * Snippet Parameters
	 *
	 * @access	public
	 * @var		array
	 */	
	public $parameters = array('directory', 'allowed_types');

	// --------------------------------------------------------------------------
	
	/**
	 * Form Input
	 *
	 * @access	public
	 * @param	string [$value] form value
	 * @return 	string
	 */
	public function form_output($value = null)
	{		
		$html = br().br();

		if ($value)
		{
			$html .= '<p><a href="'.site_url('files/large/'.$value).'"><img src="'.site_url('files/thumb/'.$value).'" alt="Image Thumb" /></a></p>';
		}
		
		// Hidden numerical value (if there is one)
		$html .= form_hidden('snippet_file_id', $value);
		
		// Actual upload input
		$upload_input_data = array(
			'name'		=> 'snippet_file',
			'id'		=> 'snippet_file'
		);

		return $html .= form_upload($upload_input_data);
	}

	// --------------------------------------------------------------------------

	/**
	 * Get the file and upload it to the correct
	 * directory + add it to the files DB.
	 * Return the insert ID
	 *
	 * @access	public
	 * @param	string
	 */
	public function pre_save($value, $params)
	{	
		// If we do not have a file that is being submitted. If we do not,
		// it could be the case that we already have one, in which case just
		// return the numeric file record value.
		if ( ! isset($_FILES['snippet_file']['name']) or ! $_FILES['snippet_file']['name'])
		{
			if (isset($_POST['snippet_file_id']) and is_numeric($_POST['snippet_file_id']))
			{
				return $_POST['snippet_file_id'];
			}
			else
			{
				return null;
			}
		}

		$this->ci->load->library('files/files');

		// If you don't set allowed types, we'll set it to allow all.
		$allowed_types 	= (isset($params['allowed_types']) and $params['allowed_types']) ? $params['allowed_types'] : '*';

		$return = Files::upload($params['directory'], null, 'snippet_file', null, null, null, $allowed_types);

		if ( ! $return['status'])
		{
			$this->ci->session->set_flashdata('notice', $return['message']);	
			return false;
		}
		else
		{
			// Return the ID of the file DB entry
			return $return['data']['id'];
		}	
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Output the image URL
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function pre_output($value)
	{
		return $value;
	}

	// --------------------------------------------------------------------------

	/**
	 * Directory Parameter
	 *
	 * Choose the directory to upload image to
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function param_directory($value)
	{
		// Get the folders
		$this->ci->load->model('files/file_folders_m');
		
		$tree = $this->ci->file_folders_m->get_folders();
		
		$tree = (array)$tree;
		
		if ( ! $tree)
		{
			// @todo - languagize this
			return '<em>You need to set an upload folder before you can upload files.</em>';
		}
		
		$choices = array();
		
		foreach ($tree as $tree_item)
		{
			$choices[$tree_item->id] = $tree_item->name;
		}
	
		return form_dropdown('directory', $choices, $value, 'id="directory"');
	}

	// --------------------------------------------------------------------------

	/**
	 * Param Allowed Types
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function param_allowed_types($value = null)
	{
		return form_input('allowed_types', $value, 'id="allowed_types"');
	}

}