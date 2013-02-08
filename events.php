<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Snippets Event Class
 *
 * This class loads the snippets library when the public
 * controller is called. Keeps from users having to add to config/autoload
 * 
 * @package		PyroCMS
 * @subpackage	Pyrosnippets
 * @category	events
 * @author		Stephen Cozart - <stephen.cozart [a][t] gmail [dt] com>
 */
class Events_Snippets {

    /**
     * CI Object
     *
     * @access  protected
     * @var     string
     */   
    protected $ci;
  
    // --------------------------------------------------------------------------
 
    /**
     * Array of snippet variables
     * and values.
     *
     * @access  public
     * @var     array
     */  
    public $var_snippets = array();
 
    // --------------------------------------------------------------------------
 
    /**
     * Construct
     *
     * @access  public
     * @var     void
     */  
    public function __construct()
    {
        $this->ci = get_instance();
        
        // Register the public controller event
        Events::register('public_controller', array($this, 'load_snippets'));
    }
  
    // --------------------------------------------------------------------------

    /**
     * Load Snippet
     *
     * Get all the snuppets and load them into the vars
     * array, making them accessible via tags.
     *
     * @access  public
     * @return  void
     */
    public function load_snippets()
    {
		// -------------------------------------
		// Get snippets
		// -------------------------------------
		
		$this->ci->load->model('snippets/snippets_m');
		
		$snippets = $this->ci->snippets_m->get_snippets();
		
		// -------------------------------------
		// Prep snippets
		// -------------------------------------

		foreach ($snippets as $snippet)
		{
            // Status check
            if ($snippet->status == 'h')
            {
                continue;
            }
            elseif ($snippet->status == 'l')
            {
                if ( ! isset($this->ci->current_user->id))
                {
                    continue;
                }
            }

			if (method_exists($this->ci->snippets_m->snippets->{$snippet->type}, 'pre_output'))
            {
				// Run through pre_output
				$this->var_snippets[$snippet->slug] = $this->ci->snippets_m
                        ->snippets->{$snippet->type}->pre_output($snippet->content, $snippet->params);
			}
			else
			{
				// Don't do anything to the content
				$this->var_snippets[$snippet->slug] = $snippet->content;
			}
		}
		
		// -------------------------------------
		// Commit snippets to ci vars
		// -------------------------------------
		
		$this->ci->load->vars('snippet', $this->var_snippets);
    }

}