<?php

/**
 * Controller of the main page of the app. Displays the products.
 */
class Products extends Controller
{
    public function index()
    {
        $this->view('index');
    }
}