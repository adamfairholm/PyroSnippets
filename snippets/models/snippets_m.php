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

	public $snippets_dir;

	public $snippets;
	
	public $snippet_array = array();

    // --------------------------------------------------------------------------

	function __construct()
	{
		parent::__construct();
		
		// Find the location
		if(is_dir(ADDONPATH.'modules/snippets')):
		
			$this->snippets_dir = ADDONPATH.'modules/snippets';
			
		else:

			$this->snippets_dir = SHARED_ADDONPATH.'modules/snippets';
		
		endif;
		
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
		require_once($this->snippets_dir.'/libraries/Snippet.php');
		
		$this->load->helper('directory');
		
		$dir = directory_map($this->snippets_dir.'/snippets/', 1);
		
		foreach($dir as $folder):
		
			// Attempt to load the snippet file.
			if(file_exists($this->snippets_dir.'/snippets/'.$folder.'/snip.'.$folder.'.php')):
			
				require_once($this->snippets_dir.'/snippets/'.$folder.'/snip.'.$folder.'.php');
			
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
    	/*if(method_exists($this->snippets->{$snippet->type}, 'pre_output')):
    	
    		$snippet->content = $this->snippets->{$snippet->type}->pre_output($snippet->content, $snippet->params);
    	
    	endif;*/
    	
    	// Format the snippet parameters
    	($snippet->params != '') ? $snippet->params = unserialize($snippet->params) : $snippet->params = array();
	
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
    function insert_new_snippet( $snippet, $user_id )
    {
    	$now = date('Y-m-d H:i:s');

    	// Save param data
    	$params = array();
    	if(isset($this->snippets->{$snippet->type}->parameters)):
    	
    		foreach($this->snippets->{$snippet->type}->parameters as $param):
    		
    			$params[$param] = $this->input->post($param);
    		
    		endforeach;
    	
    	endif;
    	$insert_data['params'] = serialize($params);
    	
    	$insert_data['content'] 		= $this->_pre_save($this->input->post('type'), $this->input->post('content'), $params);
     	$insert_data['name']			= $this->input->post('name');
     	$insert_data['slug']			= $this->input->post('slug');
     	$insert_data['type']			= $this->input->post('type');
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
    function update_snippet($snippet, $setup = false, $data = array())
    {
    	$update_data = (array)$data;
    		 		
    	$update_data['last_updated'] 	= date('Y-m-d H:i:s');
    	
    	// Save param data
    	$params = $snippet->params;
    	
    	/**
    	 * We are sharing this function with the content-only update,
    	 * so if we need to update the snippet on the setup side,
    	 * we have a few more considerations
    	 */
    	if($setup):
	    	
	    	// Update params
	    	if(isset($this->snippets->{$snippet->type}->parameters)):
	    	
	    		foreach($this->snippets->{$snippet->type}->parameters as $param):
	    		
	    			$params[$param] = $this->input->post($param);
	    		
	    		endforeach;
	    	
	    	endif;
	    	$update_data['params'] = serialize($params);

	     	$update_data['name']			= $this->input->post('name');
	     	$update_data['slug']			= $this->input->post('slug');
	     	$update_data['type']			= $this->input->post('type');
    	
    	endif;
  
     	$update_data['content'] 		= $this->_pre_save($snippet->type, $this->input->post('content'), $params);

    	return $this->db->where('id', $snippet->id)->update('snippets', $update_data);
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
	 * @param	string - the snippet type
	 * @param	string - the content
	 * @param	array - the params
	 * @return	string - the pre_saved content
	 */ 
	private function _pre_save($type, $content, $params = array())
	{
    	// Process content based on snippet type
    	if(method_exists($this->snippets->{$type}, 'pre_save')):
    	
    		return $this->snippets->{$type}->pre_save($content, $params);
    	
    	endif;
	
		// Default is to just return the content
		return $content;
	}

}