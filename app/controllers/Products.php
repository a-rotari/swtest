<?php

/**
 * Controller of the main page of the app. Displays the products.
 */
class Products extends Controller
{
    public function __construct()
    {
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        $data = [
            'products' => $products
        ];
        $this->view('index', $data);
    }

    public function addproduct() {
        $this->view('add-product');
}
}