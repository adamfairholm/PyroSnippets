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

	/**
	 * Section
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $section = 'setup';

	// --------------------------------------------------------------------------

	/**
	 * Snippet Validation Rules
	 *
	 * @access	protected
	 * @var		array
	 */
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
	
	// --------------------------------------------------------------------------

	/**
	 * Valid Snippet Types
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $snippet_types = array(
		'wysiwyg' 	=> 'WYSIWYG',
		'text' 		=> 'Text',
		'html'		=> 'HTML',
		'image'		=> 'Image'
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

	/**
	 * List Snippets
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$this->list_snippets();
	}

	// --------------------------------------------------------------------------
	
	/**
	 * List snippets
	 *
	 * @access	public
	 * @return	void
	 */
	public function list_snippets($offset = 0)
	{	
		// If you can't admin snippets, you can't create them
		role_or_die('snippets', 'admin_snippets');

		// -------------------------------------
		// Get snippets
		// -------------------------------------
		
		$this->template->snippets = $this->snippets_m->get_snippets( Settings::get('records_per_page'), $offset );

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
	 * Create Snippet
	 *
	 * @access	public
	 * @return	void
	 */
	public function create_snippet()
	{
		// If you can't admin snippets, you can't create them
		role_or_die('snippets', 'admin_snippets');

 		$this->template->append_js('module::new_snippet.js');

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');

		$this->snippet_rules[1]['rules'] .= '|callback__check_slug[insert]';

		$this->form_validation->set_rules( $this->snippet_rules );

		$snippet = new stdClass();
		
		foreach ($this->snippet_rules as $key => $rule)
		{
			$snippet->{$rule['field']} = $this->input->post($rule['field'], true);
		}

		// -------------------------------------
		// Process Data
		// -------------------------------------

		if ($this->form_validation->run())
		{
			if ( ! $this->snippets_m->insert_new_snippet( $snippet, $this->session->userdata('user_id')))
			{
				$this->session->set_flashdata('notice', lang('snippets.new_snippet_error'));	
			}
			else
			{
				$this->session->set_flashdata('success', lang('snippets.new_snippet_success'));
				
				Events::trigger('post_snippet_setup_create', $snippet);
			}
	
			redirect('admin/snippets/setup');
		}
		
		// -------------------------------------
		
		$this->template
					->set('mode', 'create')
					->set('snippet', $snippet)
					->build('admin/setup/form');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Edit a snippet
	 *
	 * @access	public
	 * @param 	int [$snippet_id]
	 * @return	void
	 */
	public function edit_snippet($snippet_id = null)
	{		
 		$this->template->append_metadata(js('new_snippet.js', 'snippets'));

		// -------------------------------------
		// Validation & Setup
		// -------------------------------------
	
		$this->load->library('form_validation');

		$this->snippet_rules[1]['rules'] .= '|callback__check_slug[update]';
		
		$this->form_validation->set_rules( $this->snippet_rules );

		// -------------------------------------
		// Get snippet data
		// -------------------------------------

		$snippet = $this->snippets_m->get_snippet( $snippet_id );

		// -------------------------------------
		// Process Data
		// -------------------------------------
		
		if ($this->form_validation->run())
		{
			foreach ($this->snippet_rules as $key => $rule)
			{
				$snippet->{$rule['field']} = $this->input->post($rule['field'], true);
			}
			
			if ( ! $this->snippets_m->update_snippet($snippet, true))
			{
				$this->session->set_flashdata('notice', lang('snippets.update_snippet_error'));	
			}
			else
			{
				$this->session->set_flashdata('success', lang('snippets.update_snippet_success'));
			
				Events::trigger('post_snippet_setup_edit', $snippet_id);
			}
	
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/snippets/setup') : redirect('admin/snippets/setup/edit_snippet/'.$snippet_id);
		}
		
		// -------------------------------------
		
		$this->template
				->set('mode', 'edit')
				->set('snippet', $snippet)
				->build('admin/setup/form');
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Delete Snippet
	 *
	 * @access	public
	 * @param 	int $snippet_id
	 * @return	void
	 */
	public function delete_snippet($snippet_id = null)
	{		
		// If you can't admin snippets, you can't delete them
		role_or_die('snippets', 'admin_snippets');

		if ( ! $this->snippets_m->delete_snippet($snippet_id))
		{
			$this->session->set_flashdata('notice', lang('snippets.delete_snippet_error'));	
		}
		else
		{
			$this->session->set_flashdata('success', lang('snippets.delete_snippet_success'));
			
			Events::trigger('post_snippet_setup_delete', $snippet_id);
		}

		redirect('admin/snippets/setup');
	}

	// --------------------------------------------------------------------------
	// Validation Callbacks
	// --------------------------------------------------------------------------

	/**
	 * Check slug to make sure it is 
	 *
	 * @access	public
	 * @param	string - slug to be tested
	 * @param	mode - update or insert
	 * @return	bool
	 */
	public function _check_slug($slug, $mode)
	{
		$obj = $this->db->where('slug', $slug)->get('snippets');
		
		if ($mode == 'update')
		{
			$threshold = 1;
		}
		else
		{
			$threshold = 0;
		}
		
		if ($obj->num_rows > $threshold)
		{
			$this->form_validation->set_message('_check_slug', lang('snippets.slug_unique'));
		
			return false;
		}
		else
		{
			return true;
		}
	}

	// --------------------------------------------------------------------------
	// AJAX
	// --------------------------------------------------------------------------

	/**
	 * Return the parameters for a type
	 *
	 * @access	public
	 * @return	void
	 */
	public function snippet_parameters()
	{
		// Check for AJAX
		if ( ! $this->input->is_ajax_request())
		{
			show_error(lang('general_error_label'));
		}

		$this->load->language('snippets');
		
		// Check for data
		$snippet_slug 	= $this->input->post('snippet_slug');
		$snippet_id		= $this->input->post('snippet_id');
		
		$snippet = null;
		$html = '';
		
		// Get the snippet if need be
		if ($snippet_id)
		{
			$snippet = $this->snippets_m->get_snippet($snippet_id);
		}

		// Return the snippet parameters as table rows
		if (isset($this->snippets_m->snippets->{$snippet_slug}->parameters))
		{
			foreach ($this->snippets_m->snippets->{$snippet_slug}->parameters as $param)
			{
				$html .= '<li class="snip_parameters"><label for="'.$param.'">'.$this->lang->line('snippets.param.'.$param).'</label>';
								
				isset($snippet->params[$param]) ? $val = $snippet->params[$param] : $val = null;
			
				$html .= '<div class="input">'.$this->snippets_m->snippets->{$snippet_slug}->{'param_'.$param}($val).'</div>';
				$html .= '</li>';
			}
		}
				
		exit($html);
	}

}