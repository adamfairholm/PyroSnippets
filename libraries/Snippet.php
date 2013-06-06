<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroSnippets Library
 *
 * @package  	PyroCMS
 * @subpackage  PyroSnippets
 * @author  	Adam Fairholm
 */ 
class Snippet {

	/**
	 * CI Object
	 *
	 * @var 	obj
	 */
	protected $ci;

	/**
	 * Value of the snippet
	 *
	 * @var 	string
	 */
	public $value 			= null;
	
	/**
	 * Name of the input. Should always
	 * be "content" but safe to keep it here
	 * in case it ever needs to change
	 *
	 * @var 	string
	 */
	public $input_name		= 'content';
	
	public function __construct()
	{
		$this->ci = get_instance();

		$this->ci->lang->load('snippets/snippets');

		$this->ci->load->helper('form');
	}

}