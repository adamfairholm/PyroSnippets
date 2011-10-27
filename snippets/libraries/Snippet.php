<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Model
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @category  	Models
 * @author  	Parse19
 */ 
class Snippet {

	/**
	 * Value of the snippet
	 */
	public $value 			= '';
	
	/**
	 * Name of the input. Should always
	 * be "content" but safe to keep it here
	 * in case it ever needs to change
	 */
	public $input_name		= 'content';
	
	/**
	 * CI Instance
	 */
	public $ci;
	
	function __construct()
	{
		$this->ci = get_instance();
		
		$this->ci->load->helper('form');
	}

} 