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
    public function view(string $view, array $data = [])
    {
        // Check for view file
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // View file does not exist
            die('Error: view does not exist');
        }
    }

    /**
     * Loads model
     * @param string $model Name of model file without extension
     * @return object
     */
    public function model(string $model): object
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
}