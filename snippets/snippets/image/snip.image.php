<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Image Snippet
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Snippets
 * @author  	Parse19
 */ 
class Snippet_image extends Snippet {

	/**
	 * Name of the Snippet
	 *
	 * @access	public
	 * @var		string
	 */
	public $name			= 'Image';
	
    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Slug
	 *
	 * @access	public
	 * @var		string
	 */
	public $slug			= 'image';
	
	public $parameters 		= array('directory', 'allowed_types');

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
		$img = $this->get_image_url($value, true);
	
		$html = '';
		
		if($img) $html .= '<p><img src="'.$img.'" alt="Image Thumb" /></p>';
		
		// Hidden numerical value (if there is one)
		$html .= form_hidden($this->input_name, $value);
		
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
		// Only go through the pre_save upload if there is a file ready to go
		if( isset($_FILES['snippet_file']['name']) and $_FILES['snippet_file']['name'] != '' ):
		
			// Do nothing
			
		else:
		
			// If we have a file already just return that value
			if( is_numeric($this->ci->input->post($this->input_name)) ):
		
				return $this->ci->input->post($this->input_name);
		
			else:
			
				return null;
			
			endif;
				
		endif;
	
		$this->ci->load->model('files/file_m');
		$this->ci->load->config('files/files');

		// Set upload data
		$upload_config['upload_path'] 		= FCPATH.$this->ci->config->item('files_folder').'/';
		
		// Set allowed types to all if there is none
		if(!isset($params['allowed_types']) or trim($params['allowed_types']) == ''):

			$upload_config['allowed_types'] 	= '*';
		
		else:
		
			$upload_config['allowed_types'] 	= $params['allowed_types'];
		
		endif;

		// Do the upload
		$this->ci->load->library('upload', $upload_config);

		if( ! $this->ci->upload->do_upload('snippet_file') ):
		
			$this->ci->session->set_flashdata('notice', 'The following errors occurred when adding your file: '.$this->ci->upload->display_errors());	
			
			return;
		
		else:
		
			$image = $this->ci->upload->data();
			
			// We are going to use the PyroCMS way here.
			$this->ci->load->library('image_lib');
			
			$img_config = array();
			
			// -------------------------------------
			// No matter what, we make a thumb
			// -------------------------------------
			
			$img_config['source_image']		= FCPATH.$this->ci->config->item('files_folder').'/'.$image['file_name'];
			$img_config['create_thumb'] 	= true;
			$img_config['maintain_ratio'] 	= true;
			$img_config['width']	 		= 150;
			$img_config['height']	 		= 1;
			$img_config['master_dim']	 	= 'width';
			
			$this->ci->image_lib->initialize($img_config);
			$this->ci->image_lib->resize();						
			$this->ci->image_lib->clear();
						
			// Use resized numbers for the files module.
			if( isset($img_config['width']) and is_numeric($img_config['width']) ):
			
				$image['image_width'] = $img_config['width'];
			
			endif;

			if( isset($img_config['height']) and is_numeric($img_config['height']) ):
			
				$image['image_height'] = $img_config['height'];
			
			endif;
			
			// Insert the data
			$this->ci->file_m->insert(array(
				'folder_id' 		=> $params['directory'],
				'user_id' 			=> $this->ci->current_user->id,
				'type' 				=> 'i',
				'name' 				=> $image['file_name'],
				'description' 		=> '',
				'filename' 			=> $image['file_name'],
				'extension' 		=> $image['file_ext'],
				'mimetype' 			=> $image['file_type'],
				'filesize' 			=> $image['file_size'],
				'width' 			=> (int) $image['image_width'],
				'height' 			=> (int) $image['image_height'],
				'date_added' 		=> time(),
			));
		
			return $this->ci->db->insert_id();
			
		endif;			
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
		return $this->get_image_url($value);
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
		
		if( !$tree ):
		
			// @todo - languagize this
			return '<em>You need to set an upload folder before you can upload files.</em>';
		
		endif;
		
		$choices = array();
		
		foreach( $tree as $tree_item ):
		
			$choices[$tree_item->id] = $tree_item->name;
		
		endforeach;
	
		return form_dropdown('directory', $choices, $value);
	}

	// --------------------------------------------------------------------------

	/**
	 * Param Allowed Types
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function param_allowed_types($value = '')
	{
		return form_input('allowed_types', $value);
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get the image URL
	 *
	 * @access	private
	 * @param	int - id
	 * @param	bool - should we grab the thumb
	 * @return	string
	 */
	private function get_image_url($id, $thumb = false)
	{
		if(!$id or !is_numeric($id)) return null;
	
		$obj = $this->ci->db->limit(1)->where('id', $id)->get('files');
		
		if($obj->num_rows() == 0) return null;
		
		$image = $obj->row();
		
		$image_filename = $image->filename;
		
		if($thumb):
		
			$pieces = explode('.', $image->filename);
			
			$end = array_pop($pieces);
			
			$image_filename = implode('.', $pieces).'_thumb.'.$end;
		
		endif;
		
		$this->ci->load->config('files/files');

		return $this->ci->config->item('files_folder').$image_filename;
	}

}