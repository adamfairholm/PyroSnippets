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
	 * Value of the snippet
	 *
	 * @access 	public
	 * @var 	string
	 */
	public $value 			= null;
	
	/**
	 * Name of the input. Should always
	 * be "content" but safe to keep it here
	 * in case it ever needs to change
	 *
	 * @access 	public
	 * @var 	string
	 */
	public $input_name		= 'content';
	
	public function __construct()
	{
		get_instance()->load->helper('form');
	}

}