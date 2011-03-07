<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Chunks extends Module {

	public $version = '0.1-beta';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Chunks'
			),
			'description' => array(
				'en' => 'Create and manage small bits of text or HTML content.'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'menu' => 'content',
			'author' => 'Adam Fairholm'
		);
	}

	public function install()
	{
		$sql['chunks'] = "
            CREATE TABLE IF NOT EXISTS `chunks` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(60) NOT NULL,
                `slug` varchar(60) NOT NULL,
                `type` varchar(40) NOT NULL,
                `content` text,
                `when_added` datetime DEFAULT NULL,
                `last_updated` datetime DEFAULT NULL,
                `added_by` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
		return $this->db->query($sql['chunks']);
	}

	public function uninstall()
	{
		// Your Uninstall Logic
		return $this->dbforge->drop_table('chunks');
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br/>Contact the module developer for assistance.";
	}
}
/* End of file details.php */