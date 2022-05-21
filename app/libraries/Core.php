<?php
/*
 * Core class that, when constructed, parses the URL exploded into an array
 * and calls the controller with method and parameters
 * based on the results of parsing.
 */

class Core
{
    // Set default controller, method and params to be called if URL doesn't match
    protected $currentController = 'Products';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        // Get the URL in the form of an array
        $url = $this->getUrl();

        // Get controller matching the first element of the array
        if (isset($url[0]) &&
            file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        }

        // Require controller and instantiate controller class
        require_once '../app/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController;

        // Get controller's method matching the second element of the array
        if (isset($url[1]) &&
            method_exists($this->currentController, $url[1])
        ) {
            $this->currentMethod = $url[1];
            unset($url[1]);
        }

        // Get any parameters that match the rest of URL's elements
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}