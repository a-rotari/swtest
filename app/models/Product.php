<?php

/**
 * Product model has methods that are used to perform database queries related to Product objects.
 * It also has a number of 'format...' methods for formatting query results before displaying the product on a page.
 * Each 'format...' method formats an element of the product array based on various criteria, and is called dynamically.
 */
class Product
{
    // for PDO object
    private $db;

    /**
     * Instantiate new PDO object
     */
    public function __construct()
    {
        $this->db = new Database;
    }

    public function formatFurniture($product)
    {
        $h = $product['attributes']['height'] ?? 'N/A ';
        $w = $product['attributes']['width'] ?? 'N/A ';
        $l = $product['attributes']['length'] ?? 'N/A ';
        $product['attributes'] += ['attributeName1' => 'Dimensions: ', 'attributeValue1' => $h . 'x' . $w . 'x' . $l];
        unset($product['attributes']['height']);
        unset($product['attributes']['width']);
        unset($product['attributes']['length']);
        return $product;
    }

    public function formatBook($product)
    {
        $weight = $product['attributes']['weight'] ?? 'N/A ';
        unset($product['attributes']['weight']);
        $product['attributes'] += ['attributeName1' => 'Weight: ', 'attributeValue1' => $weight . ' KG'];
        return $product;
    }

    public function formatDvd($product)
    {
        $size = $product['attributes']['size'] ?? 'N/A ';
        unset($product['attributes']['size']);
        $product['attributes'] += ['attributeName1' => 'Size: ', 'attributeValue1' => $size . ' MB'];
        return $product;
    }

    /**
     * Returns an array of products from the database.
     * @return array
     */
    public function getProducts()
    {
        $this->db->query(
            "SELECT
                    product.id as 'id',
                    product.sku as 'sku',
                    product.name as 'productName',
                    product.price as 'productPrice',
                    product.type as 'productType',
                    attribute.name as 'attributeName',
                    attribute.value as 'attributeValue'
                 FROM
                    product LEFT JOIN attribute ON product.id = attribute.product_id 
                 ORDER BY
                    product.id;"
        );
        $rawProducts = $this->db->resultSet();

        $products = [];

        foreach ($rawProducts as $rawProduct) {
            $productId = $rawProduct['id'];
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'id' => $rawProduct['id'],
                    'sku' => $rawProduct['sku'],
                    'productName' => $rawProduct['productName'],
                    'productPrice' => $rawProduct['productPrice'],
                    'productType' => $rawProduct['productType'],
                    'attributes' => [$rawProduct['attributeName'] => $rawProduct['attributeValue']]
                ];
            } else {
                $products[$productId]['attributes'] += [$rawProduct['attributeName'] => $rawProduct['attributeValue']];
            }
        }

        foreach ($products as $id => $product) {
            $products[$id] = $this->formatProduct($products[$id]);

            $formatMethod = 'format' . ucwords($product['productType']);
            $products[$id] = $this->$formatMethod($products[$id]);
        }
        return $products;
    }

    /**
     * Formats main attributes of product by formatting them inside the array with products, preparing them for display.
     * @param $product
     * @return mixed
     */
    public function formatProduct($product)
    {
        $price = $product['productPrice'] ?? 'N/A ';
        unset($product['productPrice']);
        $product['productPrice'] = $price . ' &#36';
        return $product;
    }
}
