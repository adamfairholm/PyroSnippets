<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Admin Controller Class
 *
 * @package  	PyroCMS
 * @subpackage  Pyrosnippets
 * @category  	Controller
 * @author  	Adam Fairholm @adamfairholm
 */ 
class Admin extends Admin_Controller {

	protected $section = 'content';
	
	protected $snippet_types = array(
		'wysiwyg' 	=> 'WYSIWYG',
		'text' 		=> 'Text',
		'html'		=> 'HTML',
		'image'		=>	'Image'
	);

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('snippets/snippets_m');
		
		$this->load->language('snippets');
		
		$this->template->snippet_types = $this->snippet_types;	
	}

	// --------------------------------------------------------------------------
	// CRUD Functions
	// --------------------------------------------------------------------------

	public function index()
	{
		$this->list_snippets();
	}

	// --------------------------------------------------------------------------
	
	/**
	 * List snippets
	 *
	 */
	public function list_snippets($offset = 0)
	{	
		// -------------------------------------
		// Get snippets
		// -------------------------------------
		
		$this->template->snippets = $this->snippets_m->get_snippets( $this->settings->item('records_per_page'), $offset );

		// -------------------------------------
		// Pagination
		// -------------------------------------

		$total_rows = $this->snippets_m->count_all();
		
		$this->template->pagination = create_pagination('admin/snippets/list_snippets', $total_rows);
		
		// -------------------------------------

		$this->template->build('admin/index');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Edit a snippet
	 *
	 */
	public function edit_snippet($snippet_id = null)
	{			
		if(is_null($snippet_id)) show_error('Invalid snippet ID.');
	
		// -------------------------------------
		// Get snippet data
		// -------------------------------------

		$snippet = $this->snippets_m->get_snippet( $snippet_id );

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('content', 'snippets.snippet_content', 'trim');

		$config[0] = array(
			array(
			     'field'   => 'content', 
			     'label'   => 'snippets.snippet_content', 
			     'rules'   => 'trim'
			  )
		);
		
		// Is this required?
		// @todo - make this an option
		$config[0][0]['rules'] .= '|required';

		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if($this->form_validation->run()):
				
			if( !$this->snippets_m->update_snippet($snippet) ):
			
				$this->session->set_flashdata('notice', lang('snippets.update_snippet_error'));	
			
			else:
			
				$this->session->set_flashdata('success', lang('snippets.update_snippet_success'));	
			
			endif;
	
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/snippets') : redirect('admin/snippets/edit_snippet/'.$snippet_id);
		
		endif;

		// -------------------------------------
		
		//$this->template->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE));
		
		$this->template->set('snippet', $snippet)->build('admin/edit');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete a snippet
	 *
	 */
	function delete_snippet( $snippet_id = 0 )
	{		
		// If you can't admin snippets, you can't delete them
		role_or_die('snippets', 'admin_snippets');

		if( ! $this->snippets_m->delete_snippet( $snippet_id ) ):
		
			$this->session->set_flashdata('notice', lang('snippets.delete_snippet_error'));	
		
		else:
		
			$this->session->set_flashdata('success', lang('snippets.delete_snippet_success'));	
		
		endif;

		redirect('admin/snippets');
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
		$obj = $this->db->where('slug', $slug)->get('snippets');
		
		if( $mode == 'update' ):
		
			$threshold = 1;
		
		else:
		
			$threshold = 0;
		
		endif;
		
		if( $obj->num_rows > $threshold ):

			$this->form_validation->set_message('_check_slug', lang('snippets.slug_unique'));
		
			return FALSE;
		
		else:
		
			return TRUE;
		
		endif;
	}
}

/* End of file admin.php */
/* Location: ./addons/modules/snippets/controllers/admin.php */