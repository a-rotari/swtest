<?php

/**
 * Core class that, when constructed, parses the URL exploded into an array
 * and calls the controller with method and parameters
 * based on the results of parsing.
 */
class Core
{
    // Set default controller, method and params to be called if URL doesn't match
    protected $currentController = 'Products'; // default controller 'Products.php'
    protected $currentMethod = 'index'; // default method 'index()'
    protected $params = []; // no parameters as default

    /**
     * Based on the URL, selects a controller's method with parameters, and calls it.
     * If URL has a first element, and there is a matching controller file, it is selected as Controller
     * while the second element is selected as the method.
     * If there is no matching controller file, the first element is instead selected as default controller's method.
     * If the default controller has no such method then a default 'index' method is selected, while all remaining
     * elements are considered parameters.
     */
    public function __construct()
    {
        // Get the URL in the form of an array
        $url = $this->getUrl();

        if (isset($url[0])) {
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
                require_once '../app/controllers/' . $this->currentController . '.php';
                $this->currentController = new $this->currentController;
                if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            } else {
                require_once '../app/controllers/' . $this->currentController . '.php';
                $this->currentController = new $this->currentController;
                if (method_exists($this->currentController, $url[0])) {
                    $this->currentMethod = $url[0];
                    unset($url[0]);
                }
            }
        } else {
            require_once '../app/controllers/' . $this->currentController . '.php';
            $this->currentController = new $this->currentController;
        }
        // Get any parameters for the method from URL
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->currentController, $this->currentMethod], [$this->params]);
    }


    /**
     * Returns URL exploded into an array
     * @return array
     */
    public function getUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // Remove any hyphens from URL since they are not allowed in PHP method names
            $url = str_replace('-', '', $url);

            // Some internal logging
            $log = date("h:i:sa") . ' --- RECEIVED URL: ' . $url . "\r\n\r\n";
            file_put_contents('sitelog', $log, FILE_APPEND);

            return explode('/', $url);
        }
        return [];
    }
}