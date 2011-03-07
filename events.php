<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Chunks  Event Class - this class loads the chunks library when the public
 * controller is called.  Keeps from users having to add to config/autoload
 * 
 * @package		PyroCMS
 * @subpackage	PyroChunks
 * @category	events
 * @author		Stephen Cozart - <stephen.cozart [a][t] gmail [dt] com>
 */
class Events_Chunks {
    
    protected $ci;
    
    public function __construct()
    {
        $this->ci =& get_instance();
        
        //register the public controller event
        Events::register('public_controller', array($this, 'load_chunks'));
    }
    
    public function load_chunks()
    {
        $this->ci->load->library('chunks/chunks');
    }
}
/* End of file addons/modules/chunks/events.php */