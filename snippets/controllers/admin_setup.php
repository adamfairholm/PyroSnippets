<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Admin Controller Class
 *
 * @package  	PyroCMS
 * @subpackage  Pyrosnippets
 * @category  	Controller
 * @author  	Adam Fairholm @adamfairholm
 */ 
class Admin_setup extends Admin_Controller {

	protected $section = 'setup';

	protected $snippet_rules = array(
		array(
			'field' => 'name',
			'label' => 'lang:snippets.snippet_name',
			'rules' => 'trim|required|max_length[60]'
		),
		array(
			'field' => 'slug',
			'label' => 'lang:snippets.snippet_slug',
			'rules' => 'trim|required|strtolower|max_length[60]'
		),
		array(
			'field' => 'type',
			'label' => 'lang:snippets.snippet_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'content',
			'label' => 'lang:snippets.snippet_content',
			'rules' => 'trim'
		)
	);
	
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

		$this->template->build('admin/setup/index');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Create a new snippet
	 *
	 */
	function create_snippet()
	{		
		// If you can't admin snippets, you can't create them
		role_or_die('snippets', 'admin_snippets');

        $this->template->append_metadata( js('debounce.js', 'snippets') );        
        $this->template->append_metadata( js('new_snippet.js', 'snippets') );        

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');

		$this->snippet_rules[1]['rules'] .= '|callback__check_slug[insert]';

		$this->form_validation->set_rules( $this->snippet_rules );
		
		foreach($this->snippet_rules as $key => $rule)
		{
			$snippet->{$rule['field']} = $this->input->post($rule['field'], TRUE);
		}

		// -------------------------------------
		// Process Data
		// -------------------------------------

		if ($this->form_validation->run())
		{
			if( ! $this->snippets_m->insert_new_snippet( $snippet, $this->session->userdata('user_id') ) ):
			{
				$this->session->set_flashdata('notice', lang('snippets.new_snippet_error'));	
			}
			else:
			{
				$this->session->set_flashdata('success', lang('snippets.new_snippet_success'));	
			}
			endif;
	
			redirect('admin/snippets/setup');
		}
		
		// -------------------------------------
		
		$this->template
					->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
					->set('snippet', $snippet)
					->build('admin/setup/form');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Edit a snippet
	 *
	 */
	public function edit_snippet($snippet_id = null)
	{			
        $this->template->append_metadata( js('debounce.js', 'snippets') );        
        $this->template->append_metadata( js('new_snippet.js', 'snippets') );        

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');

		$this->snippet_rules[1]['rules'] .= '|callback__check_slug[update]';
		
		// We need to edit the rules if we are a user that
		// can't access certain things.
		if(!group_has_role('snippets', 'admin_snippets')):
		
			unset($this->snippet_rules[0]);
			unset($this->snippet_rules[1]);
			unset($this->snippet_rules[2]);
		
		endif;

		$this->form_validation->set_rules( $this->snippet_rules );

		// -------------------------------------
		// Get snippet data
		// -------------------------------------

		$snippet = $this->snippets_m->get_snippet( $snippet_id );

		$mode = 'outgoing'; // Switch it up for images

		// -------------------------------------
		// Set WYSIWYG for snippet Type
		// -------------------------------------

		if($snippet->type == 'wysiwyg'):
		
			$this->template->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE));
			
		elseif ($snippet->type == 'image'):
		
			$this->load->model('files/file_m');
			$images = $this->file_m->order_by('name','ASC')->dropdown('name');
			$images[0] = '-- ' . lang('snippets.snippet_image') . ' --';
			$this->template->set('images', $images);
			$mode = 'incoming'; // Reset the mode to incoming because we only need the id & name

		endif;

		// -------------------------------------
		// Get snippet data
		// -------------------------------------
		
		$snippet->content = $this->snippets_m->process_type( $snippet->type, $snippet->content, $mode );

		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run())
		{
			foreach($this->snippet_rules as $key => $rule)
			{
				$snippet->{$rule['field']} = $this->input->post($rule['field'], TRUE);
			}
			if( ! $this->snippets_m->update_snippet( $snippet, $snippet_id ) ):
			{
				$this->session->set_flashdata('notice', lang('snippets.update_snippet_error'));	
			}
			else:
			{
				$this->session->set_flashdata('success', lang('snippets.update_snippet_success'));	
			}
			endif;
	
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/snippets/setup') : $this->template->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE));
		}

		// -------------------------------------
		
		$this->template->set('snippet', $snippet)->build('admin/setup/form');
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
		{
			$this->session->set_flashdata('notice', lang('snippets.delete_snippet_error'));	
		}
		else:
		{
			$this->session->set_flashdata('success', lang('snippets.delete_snippet_success'));	
		}
		endif;

		redirect('admin/snippets/setup');
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