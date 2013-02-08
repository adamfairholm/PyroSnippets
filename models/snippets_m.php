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

	/**
	 * The directory where the snippets
	 * are in. Set in the construct().
	 *
	 * @access	public
	 * @var		string
	 */
	public $snippets_dir;

    // --------------------------------------------------------------------------

	/**
	 * Snippets
	 *
	 * Contains all of our snippet
	 * objects.
	 *
	 * @access	public
	 * @var		obj
	 */
	public $snippets;

    // --------------------------------------------------------------------------
	
	/**
	 * Snippet Array
	 *
	 * Array of snippet slug => snippet name
	 * for various places.
	 *
	 * @access	public
	 * @var		array
	 */
	public $snippet_array = array();

    // --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		
		// Find the location
		if (is_dir(ADDONPATH.'modules/snippets'))
		{
			$this->snippets_dir = ADDONPATH.'modules/snippets';
		}	
		else
        { 
			$this->snippets_dir = SHARED_ADDONPATH.'modules/snippets';
		}
		
		$this->load_snippets();
	}

    // --------------------------------------------------------------------------

    /**
     * Load snippets into a snippet obj
     *
     * @access	public
     * @return	obj
     */
    public function load_snippets()
	{
		$this->module_details['path'];
	
		// Load up the snippet library
		require_once($this->snippets_dir.'/libraries/Snippet.php');
		
		$this->load->helper('directory');
		
		$dir = directory_map($this->snippets_dir.'/snippets/', 1);

        $this->snippets = new stdClass();

		foreach ($dir as $folder)
        {
            if ($folder != 'index.html')
            {	
    			// Attempt to load the snippet file.
    			if (file_exists($this->snippets_dir.'/snippets/'.$folder.'/snip.'.$folder.'.php'))
    			{
    				require_once($this->snippets_dir.'/snippets/'.$folder.'/snip.'.$folder.'.php');
    			
    				$class_name = 'Snippet_'.$folder;
    				$this->snippets->$folder = new $class_name();
    			}
            }
		}
				
		// Create a snippet array for convenience
		foreach ($this->snippets as $snip)
		{
			$this->snippet_array[$snip->slug] = $snip->name;
		}
	}

    // --------------------------------------------------------------------------
    
    /**
     * Get some snippets
     *
     * @access	public
     * @param	int $limit
     * @param	int $offset
     * @return	obj
     */
    public function get_snippets($limit = false, $offset = 0)
	{
		$this->db->order_by('name', 'desc');
	
		if ($limit)
        {
            $this->db->limit($limit);
		}

        if ($offset)
        {
            $this->db->offset($offset);
		}    

		return $this->db->get('snippets')->result();
	}

    // --------------------------------------------------------------------------
    
    /**
     * Get Snippet
     *
     * @access	public
     * @param	int $snippet_id
     * @return	obj
     */
    public function get_snippet($snippet_id)
	{     
		$snippet = $this->db->where('id', $snippet_id)->limit(1)->get('snippets')->row();
    	
    	if ( ! $snippet) return null;
    	    	
    	// Format the snippet parameters
    	($snippet->params != '') ? $snippet->params = unserialize($snippet->params) : $snippet->params = array();
	
		return $snippet;
	}

    // --------------------------------------------------------------------------
    
    /**
     * Count Snippets
     *
     * @access	public
     * @return	int
     */
    public function count_all()
	{     
		return $this->db->count_all('snippets');
	}
     
	// --------------------------------------------------------------------------
     
    /**
     * Insert New Snippet
     *
     * @access	public
     * @param	array $snippet
     * @param	int $user_id
     * @return 	bool
     */
	public function insert_new_snippet($snippet, $user_id)
    {
    	$now = date('Y-m-d H:i:s');

    	// Save param data
    	$params = array();

    	if (isset($this->snippets->{$snippet->type}->parameters))
    	{
    		foreach ($this->snippets->{$snippet->type}->parameters as $param)
    		{
    			$params[$param] = $this->input->post($param);
    		}
    	}

        $insert_data = array();
        $insert_data['params']          = serialize($params);
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
     * Update Snippet
     *
     * @access	public
     * @param	obj   $snippet
     * @param   bool  $setup   Do we want to set up the actual snippet
     *                              or just update the content?
     * @param   array $data    Data for the snippet.
     * @return 	bool
     */
	public function update_snippet($snippet, $setup = false, $data = array())
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
    	if ($setup)
	    {
	    	// Update params
	    	if (isset($this->snippets->{$snippet->type}->parameters))
	    	{
	    		foreach ($this->snippets->{$snippet->type}->parameters as $param)
	    		{
	    			$params[$param] = $this->input->post($param);
	    		}
	    	}

	    	$update_data['params']    = serialize($params);

	     	$update_data['name']	  = $this->input->post('name');
	     	$update_data['slug']	  = $this->input->post('slug');
	     	$update_data['type']	  = $this->input->post('type');
    	}
    	else
    	{
			$update_data['content']   = $this->_pre_save($snippet->type, $this->input->post('content'), $params);
    	}

    	return $this->db->where('id', $snippet->id)->update('snippets', $update_data);
    }

	// --------------------------------------------------------------------------
     
    /**
     * Delete Snippet
     *
     * @access	public
     * @param	int   $snippet_id
     * @return 	bool
     */    
	public function delete_snippet($snippet_id)
    {    	
    	return $this->db->limit(1)->where('id', $snippet_id)->delete('snippets');
    }

	// --------------------------------------------------------------------------

	/**
	 * Run input data through a pre_save process
	 * if necessary.
	 *
	 * @access	private
	 * @param	string   $type       the snippet type
	 * @param	string   $content    the content
	 * @param	array    $params     the params
	 * @return	string   the pre_saved content
	 */ 
	private function _pre_save($type, $content, $params = array())
	{
    	// Process content based on snippet type
    	if (method_exists($this->snippets->{$type}, 'pre_save'))
    	{
    		return $this->snippets->{$type}->pre_save($content, $params);
    	}
    	
    	// If there is no content, let's make the null value
    	// explicit
    	if ( ! $content)
        {
            return null;
        }

		// Default is to just return the content
		return $content;
	}

}