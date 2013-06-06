<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Snippets Plugin
 *
 * Display snippets.
 *
 * @author   PyroCMS Dev Team
 * @package  PyroCMS\Core\Modules\Variables\Plugins
 */
class Plugin_Snippets extends Plugin
{
    public $version = '1.0.0';

    public $name = array(
        'en' => 'Variables',
    );
    
    public $description = array(
        'en' => 'Set and retrieve variable data.',
    );

    public $snippets = array();

    /**
     * Load a snip
     *
     * Magic method to get the variable.
     *
     * @param string $name
     * @param string $arguments
     * @return string
     */
    public function __construct()
    {
        // -------------------------------------
        // Get snippets
        // -------------------------------------
        
        $this->load->model('snippets/snippets_m');
        
        $snippets = $this->snippets_m->get_snippets();
        
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
                if ( ! isset($this->current_user->id))
                {
                    continue;
                }
            }

            if (method_exists($this->snippets_m->snippets->{$snippet->type}, 'pre_output'))
            {
                // Run through pre_output
                $this->snippets[$snippet->slug] = $this->snippets_m
                        ->snippets->{$snippet->type}->pre_output($snippet->content, $snippet->params);
            }
            else
            {
                // Don't do anything to the content
                $this->snippets[$snippet->slug] = $snippet->content;
            }
        }
    }

    /**
     * Load a snip
     *
     * Magic method to get the variable.
     *
     * @param string $name
     * @param string $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
        return (isset($this->snippets[$name])) ? $this->snippets[$name] : null;
    }

}