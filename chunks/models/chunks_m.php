<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroChunks Chunks Model
 *
 * @package  	PyroCMS
 * @subpackage  PyroChunks
 * @category  	Models
 * @author  	Adam Fairholm
 */ 
class Chunks_m extends MY_Model {

    // --------------------------------------------------------------------------
    
    /**
     * Get some chunks
     *
     * @param	int limit
     * @param	int offset
     * @return	obj
     */
    function get_chunks($limit = FALSE, $offset = FALSE)
	{
		$this->db->order_by('name', 'desc');
	
		if($limit) $this->db->limit($limit);
		if($offset) $this->db->offset($offset);
		     
		$obj = $this->db->get('chunks');
    	
    	return $obj->result();
	}

    // --------------------------------------------------------------------------
    
    /**
     * Get a chunk
     *
     * @param	int
     * @return	obj
     */
    function get_chunk( $chunk_id )
	{     
		$obj = $this->db->where('id', $chunk_id)->limit(1)->get('chunks');
    	
    	return $obj->row();
	}

    // --------------------------------------------------------------------------
    
    /**
     * Count chunks
     *
     * @return	int
     */
    function count_all()
	{     
		return $this->db->count_all('chunks');
	}
     
	// --------------------------------------------------------------------------
     
    /**
     * Insert a chunk
     *
     * @param	array
     * @param	int
     * @return 	bool
     */
    function insert_new_chunk( $data, $user_id )
    {
    	$insert_data = (array)$data;
	
       	$insert_data['content'] = $this->process_type( $this->input->post('type'), $this->input->post('content') );
    	
    	$now = date('Y-m-d H:i:s');
    	
    	$insert_data['when_added'] 		= $now;
    	$insert_data['last_updated'] 	= $now;
    	$insert_data['added_by']		= $user_id;
    	
    	return $this->db->insert('chunks', $insert_data);
    }

	// --------------------------------------------------------------------------
     
    /**
     * Update a chunk
     *
     * @param	array
     * @param	int
     * @return 	bool
     */
    function update_chunk( $data, $chunk_id )
    {
    	$update_data = (array)$data;
    		
       	$update_data['content'] = $this->process_type( $this->input->post('type'), $this->input->post('content') );
 		
    	$update_data['last_updated'] 	= date('Y-m-d H:i:s');
    	
    	$this->db->where('id', $chunk_id);
    	
    	return $this->db->update('chunks', $update_data);
    }

	// --------------------------------------------------------------------------
     
    /**
     * Delete a chunk
     *
     * @param	int
     * @return 	bool
     */    
    function delete_chunk( $chunk_id )
    {
    	$this->db->where('id', $chunk_id);
    	
    	return $this->db->delete('chunks');
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
	function process_type( $type, $string, $mode = 'incoming' )
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
		
		else:
		
			return $string;
		
		endif;
	
	}
}

/* End of file chunks_m.php */