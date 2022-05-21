<?php


/**
 * Base Controller that extends other controller classes. Loads views.
 *
 */
class Controller
{
    /**
     * Loads view
     * @param string $view Name of view file without extension
     * @param array $data Data passed to the view
     * @return void
     */
    public function view($view, $data = [])
    {
        // Check for view file
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // View file does not exist
            die('Error: view does not exist');
        }
    }
}