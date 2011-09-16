<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * snippets  Event Class - this class loads the snippets library when the public
 * controller is called.  Keeps from users having to add to config/autoload
 * 
 * @package		PyroCMS
 * @subpackage	Pyrosnippets
 * @category	events
 * @author		Stephen Cozart - <stephen.cozart [a][t] gmail [dt] com>
 */
class Events_Snippets {
    
    protected $ci;
    
    public function __construct()
    {
        $this->ci =& get_instance();
        
        //register the public controller event
        Events::register('public_controller', array($this, 'load_snippets'));
    }
    
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

		foreach( $snippets as $snippet ):
		
				$this->var_snippets[$snippet->slug] = $this->ci->snippets_m->process_type( $snippet->type, $snippet->content, 'outgoing' );
			
		endforeach;
		
		// -------------------------------------
		// Commit snippets to ci vars
		// -------------------------------------
		
		$this->ci->load->vars('snippet', $this->var_snippets);
		
		// Legacy
		$this->ci->load->vars('chunk', $this->var_snippets);
    }
}
/* End of file addons/modules/snippets/events.php */