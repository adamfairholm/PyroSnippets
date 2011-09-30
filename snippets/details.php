<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroChunks Details File
 *
 * @package  	PyroCMS
 * @subpackage  PyroChunks
 * @category  	Details
 * @author  	Adam Fairholm & Stephen Cozart
 */ 
class Module_Snippets extends Module {

	public $version = '2.0.1';
	
	public $db_pre;

 	// --------------------------------------------------------------------------

	public function __construct()
	{	
		if(CMS_VERSION >= 1.3) $this->db_pre = SITE_REF.'_';
	}

	// --------------------------------------------------------------------------
	
 	public function info()
	{
		return array(
		    'name' => array(
		        'en' => 'Snippets',
		        'ar' => 'القصاصات',
		        'sl' => 'Delčki strani'
		    ),
		    'description' => array(
		        'en' => 'Create and manage small bits of text or HTML content.',
		        'ar' => 'أنشئ وعدّل بعض النصوص أو أكواد HTML.',
   		        'sl' => 'Ustvari in uredi male delčke strani z vsebino iz teksta ali HTML-ja.',
		    ),
		    'frontend' => false,
			'backend' => true,
			'skip_xss' => true,
			'menu' => 'content',
			'author' => 'Parse19',
			'roles' => array('admin_snippets')
		);
	}

	// --------------------------------------------------------------------------

	public function install()
	{
		// Either this is a new install or upgrading from
		// a previous version of PyroChunks.

		// First, check and see if PyroChunks is listed in the modules
		$obj = $this->db->where('slug', 'chunks')->get('modules');
		if($obj->num_rows() > 0):

			// Delete our modules entry for chunks
			$this->db->where('slug', 'chunks');
			$this->db->delete('modules');
			
		endif;

		// Do we have a chunks table with our precious chunks data
		// or do we have a new install?		
		if($this->db->table_exists($this->db_pre.'chunks')):
		
			$this->load->dbforge();
			if(!$this->dbforge->rename_table($this->db_pre.'chunks', $this->db_pre.'snippets')): return false; endif;
			
			return true;
		
		else:

			// New install
			$sql = "
	            CREATE TABLE IF NOT EXISTS `{$this->db_pre}snippets` (
	                `id` int(11) NOT NULL AUTO_INCREMENT,
	                `name` varchar(60) NOT NULL,
	                `slug` varchar(60) NOT NULL,
	                `type` varchar(10) NOT NULL,
	                `content` text,
	                `when_added` datetime DEFAULT NULL,
	                `last_updated` datetime DEFAULT NULL,
	                `added_by` int(11) DEFAULT NULL,
	                PRIMARY KEY (`id`)
	              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";	

			return $this->db->query($sql);
		
		endif;
	}

	// --------------------------------------------------------------------------

	public function uninstall()
	{
		$this->load->dbforge();
		
		// Get rid of the snippets table
		return $this->dbforge->drop_table('snippets');
	}

	// --------------------------------------------------------------------------

	public function upgrade($old_version)
	{
		return true;
	}

	// --------------------------------------------------------------------------

	public function help()
	{
		return "No documentation has been added for this module.<br/>Contact the module developer for assistance.";
	}
}
/* End of file details.php */