<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroChunks Admin Controller Class
 *
 * @package  PyroCMS
 * @subpackage  PyroChunks
 * @category  Controller
 * @author  Adam Fairholm
 */ 
class Admin extends Admin_Controller {

	protected $chunk_rules = array(
								array(
									'field' => 'name',
									'label' => 'lang:chunks.chunk_name',
									'rules' => 'trim|required|max_length[60]'
								),
								array(
									'field' => 'slug',
									'label' => 'lang:chunks.chunk_slug',
									'rules' => 'trim|required|strtolower|max_length[60]'
								),
								array(
									'field' => 'type',
									'label' => 'lang:chunks.chunk_type',
									'rules' => 'trim'
								),
								array(
									'field' => 'content',
									'label' => 'lang:chunks.chunk_content',
									'rules' => 'trim|required'
								)
	);
	protected $chunk_types = array(
								'wysiwyg' 	=> 'WYSIWYG',
								'text' 		=> 'Text',
								'html'		=> 'HTML'
							);

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::Admin_Controller();
		
		$this->load->model('chunks_m');
		
		$this->load->language('chunks');
		
		$this->template->chunk_types = $this->chunk_types;
		
		$this->template->set_partial('shortcuts', 'admin/shortcuts');
	}

	// --------------------------------------------------------------------------
	// CRUD Functions
	// --------------------------------------------------------------------------

	public function index()
	{
		$this->list_chunks();
	}

	// --------------------------------------------------------------------------
	
	/**
	 * List chunks
	 *
	 */
	public function list_chunks( $offset = 0 )
	{	
		// -------------------------------------
		// Get chunks
		// -------------------------------------
		
		$this->template->chunks = $this->chunks_m->get_chunks( $this->settings->item('records_per_page'), $offset );

		// -------------------------------------
		// Pagination
		// -------------------------------------

		$total_rows = $this->chunks_m->count_all();
		
		$this->template->pagination = create_pagination('admin/chunks/list_chunks', $total_rows);
		
		// -------------------------------------

		$this->template->build('admin/list_chunks');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Create a new chunk
	 *
	 */
	function create_chunk()
	{		
		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');

		$this->chunk_rules[1]['rules'] .= '|callback__check_slug[insert]';

		$this->form_validation->set_rules( $this->chunk_rules );
		
		foreach($this->chunk_rules as $key => $rule)
		{
			$chunk->{$rule['field']} = $this->input->post($rule['field'], TRUE);
		}

		// -------------------------------------
		// Process Data
		// -------------------------------------

		if ($this->form_validation->run())
		{
			if( ! $this->chunks_m->insert_new_chunk( $chunk, $this->user->id ) ):
			{
				$this->session->set_flashdata('notice', lang('chunks.new_chunk_error'));	
			}
			else:
			{
				$this->session->set_flashdata('success', lang('chunks.new_chunk_success'));	
			}
			endif;
	
			redirect('admin/chunks');
		}
		
		// -------------------------------------
		
		$this->template
					->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
					->set('chunk', $chunk)
					->build('admin/form');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Edit a chunk
	 *
	 */
	public function edit_chunk( $chunk_id = 0 )
	{		
		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');

		$this->chunk_rules[1]['rules'] .= '|callback__check_slug[update]';

		$this->form_validation->set_rules( $this->chunk_rules );

		// -------------------------------------
		// Get chunk data
		// -------------------------------------
		
		$chunk = $this->chunks_m->get_chunk( $chunk_id );
	
		$chunk->content = $this->chunks_m->process_type( $chunk->type, $chunk->content, 'outgoing' );
		
		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run())
		{
			foreach($this->chunk_rules as $key => $rule)
			{
				$chunk->{$rule['field']} = $this->input->post($rule['field'], TRUE);
			}
			if( ! $this->chunks_m->update_chunk( $chunk, $chunk_id ) ):
			{
				$this->session->set_flashdata('notice', lang('chunks.update_chunk_error'));	
			}
			else:
			{
				$this->session->set_flashdata('success', lang('chunks.update_chunk_success'));	
			}
			endif;
	
			redirect('admin/chunks');
		}

		// -------------------------------------
		
		$this->template
					->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
					->set('chunk', $chunk)
					->build('admin/form');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete a chunk
	 *
	 */
	function delete_chunk( $chunk_id = 0 )
	{		
		if( ! $this->chunks_m->delete_chunk( $chunk_id ) ):
		{
			$this->session->set_flashdata('notice', lang('chunks.delete_chunk_error'));	
		}
		else:
		{
			$this->session->set_flashdata('success', lang('chunks.delete_chunk_success'));	
		}
		endif;

		redirect('admin/chunks');
	}

	// --------------------------------------------------------------------------
	// Validation Callbacks
	// --------------------------------------------------------------------------

	/**
	 * Check slug to make sure it is 
	 *
	 * @param	string - slug to be tested
	 * @param	mode - update or insert
	 * @return	bool
	 */
	function _check_slug( $slug, $mode )
	{
		$obj = $this->db->query("SELECT slug FROM chunks WHERE slug='$slug'");
		
		if( $mode == 'update' ):
		
			$threshold = 1;
		
		else:
		
			$threshold = 0;
		
		endif;
		
		if( $obj->num_rows > $threshold ):

			$this->form_validation->set_message('_check_slug', lang('chunks.slug_unique'));
		
			return FALSE;
		
		else:
		
			return TRUE;
		
		endif;
	}
}

/* End of file admin.php */
/* Location: ./third_party/modules/chunks/controllers/admin.php */