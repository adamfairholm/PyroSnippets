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
    	
    	return $obj->row();
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
	
       	$insert_data['content'] = $this->process_type( $this->input->post('type'), $this->input->post('content') );
    	
    	$now = date('Y-m-d H:i:s');
    	
    	$insert_data['when_added'] 		= $now;
    	$insert_data['last_updated'] 	= $now;
    	$insert_data['added_by']		= $user_id;
    	
    	return $this->db->insert('snippets', $insert_data);
    }

	// --------------------------------------------------------------------------
     
    /**
     * Update a snippet
     *
     * @param	array
     * @param	int
     * @return 	bool
     */
    function update_snippet($data, $snippet_id)
    {
    	$update_data = (array)$data;
    		
       	$update_data['content'] = $this->process_type( $this->input->post('type'), $this->input->post('content') );
 		
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