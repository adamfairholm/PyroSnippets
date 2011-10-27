<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Chunks Model
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Models
 * @author  	Adam Fairholm @adamfairholm
 */ 
class Snippets_m extends MY_Model {

	public $snippets;
	
	public $snippet_array = array();

    // --------------------------------------------------------------------------

	function __construct()
	{
		parent::__construct();
		
		$this->load_snippets();
	}

    // --------------------------------------------------------------------------

    /**
     * Load snippets into a snippet obj
     *
     * @param	int limit
     * @param	int offset
     * @return	obj
     */
    function load_snippets()
	{
		$this->module_details['path'];
	
		// Load up the snippet library
		require_once($this->module_details['path'].'/libraries/Snippet.php');
		
		$this->load->helper('directory');
		
		$dir = directory_map($this->module_details['path'].'/snippets/', 1);
		
		foreach($dir as $folder):
		
			// Attempt to load the snippet file.
			if(file_exists($this->module_details['path'].'/snippets/'.$folder.'/snip.'.$folder.'.php')):
			
				require_once($this->module_details['path'].'/snippets/'.$folder.'/snip.'.$folder.'.php');
			
				//$this->snippets->$folder = new Snippet();
				$class_name = 'Snippet_'.$folder;
				$this->snippets->$folder = new $class_name();
			
			endif;
		
		endforeach;
				
		// Create a snippet array for convenience
		foreach($this->snippets as $snip):
		
			$this->snippet_array[$snip->slug] = $snip->name;
		
		endforeach;
	}

    // --------------------------------------------------------------------------
    
    /**
     * Get some snippets
     *
     * @param	int limit
     * @param	int offset
     * @return	obj
     */
    function get_snippets($limit = FALSE, $offset = FALSE)
	{
		$this->db->order_by('name', 'desc');
	
		if($limit) $this->db->limit($limit);
		if($offset) $this->db->offset($offset);
		     
		$obj = $this->db->get('snippets');
    	
    	return $obj->result();
	}

    // --------------------------------------------------------------------------
    
    /**
     * Get a snippet
     *
     * @param	int
     * @return	obj
     */
    function get_snippet($snippet_id)
	{     
		$obj = $this->db->where('id', $snippet_id)->limit(1)->get('snippets');
    	
    	$snippet = $obj->row();
    	
    	// Use pre_output if necessary
    	if(method_exists($this->snippets->{$snippet->type}, 'pre_output')):
    	
    		$snippet->content = $this->snippets->{$snippet->type}->pre_save($snippet->content);
    	
    	endif;    	
	
		return $snippet;
	}

    // --------------------------------------------------------------------------
    
    /**
     * Count snippets
     *
     * @return	int
     */
    function count_all()
	{     
		return $this->db->count_all('snippets');
	}
     
	// --------------------------------------------------------------------------
     
    /**
     * Insert a snippet
     *
     * @param	array
     * @param	int
     * @return 	bool
     */
    function insert_new_snippet( $data, $user_id )
    {
    	$insert_data = (array)$data;
    	
    	$now = date('Y-m-d H:i:s');
    	
    	$insert_data['content'] 		= $this->_pre_save($this->input->post('type'), $this->input->post('content'));
    	$insert_data['when_added'] 		= $now;
    	$insert_data['last_updated'] 	= $now;
    	$insert_data['added_by']		= $user_id;
    	
    	return $this->db->insert('snippets', $insert_data);
    }

	// --------------------------------------------------------------------------
     
    /**
     * Update a snippet
     *
     * @param	int
     * @param	[array] - extra data items
     * @return 	bool
     */
    function update_snippet($type, $snippet_id, $data = array())
    {
    	$update_data = (array)$data;
    		
    	$update_data['content'] 		= $this->_pre_save($type, $this->input->post('content'));
 		
    	$update_data['last_updated'] 	= date('Y-m-d H:i:s');
    	
    	$this->db->where('id', $snippet_id);
    	
    	return $this->db->update('snippets', $update_data);
    }

	// --------------------------------------------------------------------------
     
    /**
     * Delete a snippet
     *
     * @param	int
     * @return 	bool
     */    
    function delete_snippet($snippet_id)
    {
    	$this->db->where('id', $snippet_id);
    	
    	return $this->db->delete('snippets');
    }

	// --------------------------------------------------------------------------

	/**
	 * Run input data through a pre_save process
	 * if necessary.
	 *
	 * @access	private
	 * @param	string - the snippet type slug
	 * @param	string - the content
	 */ 
	private function _pre_save($type, $content)
	{
    	// Process content based on snippet type
    	if(method_exists($this->snippets->{$type}, 'pre_save')):
    	
    		$this->snippets->{$type}->pre_save($content);
    	
    	endif;
	
		// Default is to just return the contetn
		return $content;
	}

	// --------------------------------------------------------------------------

	/**
	 * Process a type
	 *
	 * @param	string
	 * @param	string
	 * @param	string - incoming or outgoing
	 * @return 	string
	 */
	function process_type($type, $string, $mode = 'incoming')
	{
		if(trim($string) == ''):
		
			return '';
		
		endif;
	
		if( $type == 'html' ):
		
			if( $mode == 'incoming' ):
			
				return htmlspecialchars( $string );
			
			else:
			
				return htmlspecialchars_decode( $string );
			
			endif;
			
		elseif ( $type == 'image' ):
		
			if( $mode == 'incoming' ):
		
				return $string;
		
			else:

				$this->load->model('files/file_m');
				$this->load->config('files/files');
				if ($this->file_m->exists($string)):
				
					$image = $this->file_m->get($string);
					
					return '<img src="/'. $this->config->item('files_folder') . '/' . $image->filename . '" alt="' . $image->name . '" width="' . $image->width . '" height="' . $image->height . '" >';
				else:
					
					return '';
				
				endif;
		
			endif;
		
		else:
		
			return $string;
		
		endif;
	
	}
}

/* End of file snippets_m.php */