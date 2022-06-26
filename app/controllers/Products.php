<?php

/**
 * Controller of the main page of the app. Displays the products.
 */
class Products extends Controller
{
    /**
     * Loads model object using parent class method
     */
    public function __construct()
    {
        $this->productModel = $this->model('Product');
    }

    /**
     * Gets an array of products by calling Product model method 'getProducts'
     * Uses parent class method to display the main page with the products
     * @return void
     */
    public function index()
    {
        $products = $this->productModel->getProducts();
        $data = [
            'products' => $products
        ];
        $this->view('index', $data);
    }

    /**
     * If the request method is POST, validates the data and inserts the values into database,
     * otherwise prepares the context variable 'data' and displays the view with product submit form.
     * Session variable remembers the currently selected product type. This is to avoid resetting the product type
     * to default every time the page is reloaded after failed validation.
     * @return void
     */
    public function addproduct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            $_SESSION['productType'] = 'furniture';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->productModel->validateProduct($_POST);
            $validateMethod = 'validate' . ucwords($_POST['productType']);
            $data = $this->productModel->$validateMethod($data);
            if (!$data['sku_err']
                && !$data['name_err']
                && !$data['price_err']
                && !array_filter($data['secondary_err'])) {
                $this->productModel->postProduct($data);
                header('Location: ' . URLROOT);
            } else {
                $_SESSION['productType'] = $data['productType'];
                $this->view('add-product', $data);
            }
        } else {
            $data = [
                'sku' => '',
                'name' => '',
                'price' => '',
                'sku_err' => '',
                'name_err' => '',
                'price_err' => '',
                'secondary_err' => []
            ];
            $this->view('add-product', $data);
        }
    }


    /**
     * Deletes objects that have IDs specified in the argument by calling Product model method 'deleteProducts'
     * @param array $ids IDs of products to be deleted
     * @return void
     */
    public function deleteproducts(array $ids)
    {
        $this->productModel->deleteProducts($ids);
        header('Location: ' . URLROOT);
    }
}