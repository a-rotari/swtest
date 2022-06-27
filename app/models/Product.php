<?php

/**
 * Product model has methods that are used to perform database queries related to Product objects.
 * It also has a number of 'format...' methods for formatting query results before displaying the product on a page.
 * Each 'format...' method formats an element of the product array based on various criteria, and is called dynamically.
 *
 * 'format...' methods receive argument in the form of array containing elements:
 * ['id'=>'...', 'sku'=>'...', 'productName'=>'...', 'productType'=>'...', 'productPrice'=>'...',
 *  'attributes'=>[ <secondary attribute(s)> => '...', (...) ]  ]
 *
 * It also has a number of 'validate...' methods for validating the product attribute values before they are inserted
 * into database. 'validate...' methods receive argument in the form of array containing elements:
 * ['sku'=>'...', 'name'=>'...', 'price'=>'...', 'productType'=>'...', 'sku_err'=>'...', 'name_err'=>'...',
 *  'price_err'=>'...', <secondary attribute 1>=>'...', <secondary attribute 2>=>'...', <...>=><...>]
 *
 */
class Product
{
    // for Database object through which database is accessed via PDO; instantiated using constructor method
    private $db;

    /**
     * Instantiate new Database object used for working with database
     */
    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Prepares 'height', 'width', 'length' attributes of 'furniture' product for display on main page in correct format
     * @param array $product array containing product attributes
     * @return array array containing product attributes formatted for display on main page
     */
    public function formatFurniture(array $product): array
    {
        $h = $product['attributes']['height'] ?? 'N/A ';
        $w = $product['attributes']['width'] ?? 'N/A ';
        $l = $product['attributes']['length'] ?? 'N/A ';
        $product['attributes'] += [
            'attributeName1' => 'Dimensions: ',
            'attributeValue1' => $h . 'x' . $w . 'x' . $l
        ];
        unset($product['attributes']['height']);
        unset($product['attributes']['width']);
        unset($product['attributes']['length']);
        return $product;
    }

    /**
     * Validates 'height', 'length', 'width' fields of 'furniture' product form before inserting into database
     * @param array $fields fields submitted for validation
     * @return array adds empty or populated error fields to the returned array before attributes are inserted into DB
     */
    public function validateFurniture(array $fields): array
    {
        $data = [
            'secondary_err' => ['height_err' => '', 'length_err' => '', 'width_err' => '']
        ];
        if (empty($fields['height'])) {
            $data['secondary_err']['height_err'] = 'Please enter height in centimeters';
        } else {
            $fields['height'] = trim($fields['height']);
            if (!filter_var($fields['height'], FILTER_VALIDATE_INT)) {
                $data['secondary_err']['height_err'] = 'Height: please enter valid height in centimeters';
            }
            if (strlen($fields['height']) > 4) {
                $data['secondary_err']['height_err'] = 'Height: too high';
            }
        }

        if (empty($fields['length'])) {
            $data['secondary_err']['length_err'] = 'Please enter length in centimeters';
        } else {
            $fields['length'] = trim($fields['length']);
            if (!filter_var($fields['length'], FILTER_VALIDATE_INT)) {
                $data['secondary_err']['length_err'] = 'Length: please enter valid length in centimeters';
            }
            if (strlen($fields['length']) > 4) {
                $data['secondary_err']['length_err'] = 'Length: too long';
            }
        }

        if (empty($fields['width'])) {
            $data['secondary_err']['width_err'] = 'Please enter width in centimeters';
        } else {
            $fields['width'] = trim($fields['width']);
            if (!filter_var($fields['width'], FILTER_VALIDATE_INT)) {
                $data['secondary_err']['width_err'] = 'Width: please enter valid width in centimeters';
            }
            if (strlen($fields['width']) > 4) {
                $data['secondary_err']['width_err'] = 'Width: too wide';
            }
        }
        return $fields + $data;
    }

    /**
     * Prepares 'weight' attribute of 'book' product for display on main page in correct format
     * @param array $product array containing product attributes
     * @return array array containing product attributes formatted for display on main page
     */
    public function formatBook(array $product): array
    {
        $weight = $product['attributes']['weight'] ?? 'N/A ';
        unset($product['attributes']['weight']);
        if (floatval($weight)) {
            $weight = strval(round(floatval($weight), 3));
        }
        $product['attributes'] += ['attributeName1' => 'Weight: ', 'attributeValue1' => $weight . ' KG'];
        return $product;
    }

    /**
     * Validates 'weight' field of 'book' product form before inserting into database
     * @param array $fields fields submitted for validation
     * @return array adds empty or populated error field to the returned array before attributes are inserted into DB
     */
    public function validateBook(array $fields): array
    {
        $data = [
            'secondary_err' => ['weight_err' => '']
        ];
        if (empty($fields['weight'])) {
            $data['secondary_err']['weight_err'] = 'Weight: please enter weight in kilograms';
        } else {
            $fields['weight'] = trim($fields['weight']);
            if (!filter_var($fields['weight'], FILTER_VALIDATE_FLOAT)) {
                $data['secondary_err']['weight_err'] = 'Weight: enter a valid weight in kilograms in decimal format';
            }
            if (floatval($fields['weight']) < 0 || floatval($fields['weight']) > 1000) {
                $data['secondary_err']['weight_err'] = 'Weight: invalid weight';
            }
        }
        return $fields + $data;
    }

    /**
     * Prepares 'size' attribute of 'dvd' product for display on main page in correct format
     * @param array $product array containing product attributes
     * @return array array containing product attributes formatted for display on main page
     */
    public function formatDvd(array $product): array
    {
        $size = $product['attributes']['size'] ?? 'N/A ';
        unset($product['attributes']['size']);
        $product['attributes'] += ['attributeName1' => 'Size: ', 'attributeValue1' => $size . ' MB'];
        return $product;
    }

    /**
     * Validates 'size' field of 'dvd' product form before inserting into database
     * @param array $fields fields submitted for validation
     * @return array adds empty or populated error field to the returned array before attributes are inserted into DB
     */
    public function validateDvd($fields)
    {
        $data = [
            'secondary_err' => ['size_err' => '']
        ];
        if (empty($fields['size'])) {
            $data['secondary_err']['size_err'] = 'Please enter size in megabytes';
        } else {
            $fields['size'] = trim($fields['size']);
            if (!filter_var($fields['size'], FILTER_VALIDATE_INT)) {
                $data['secondary_err']['size_err'] = 'Size: enter a valid size in megabytes';
            }
            if (intval($fields['size']) > 1000000) {
                $data['secondary_err']['size_err'] = 'Size: size too large';
            }
        }
        return $fields + $data;
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
     * @param array $product An array containing product attributes to be formatted for display
     * @return array
     */
    public function formatProduct(array $product): array
    {
        // Make sure name is not empty and prepare its shortened form for displaying on main page
        $name = $product['productName'] ?? 'N/A ';
        if (strlen($name) > 20) {
            $product['productName'] = substr($name, 0, 17) . '...';
        }

        // Make sure price is not empty and append $ sign for displaying on main page
        $price = $product['productPrice'] ?? 'N/A ';
        unset($product['productPrice']);
        $product['productPrice'] = $price . ' $';
        return $product;
    }

    /**
     * Validates main fields ('sku', 'name', 'price') of product form before validation of secondary fields
     * @param array $fields fields submitted for validation
     * @return array adds empty or populated error field to returned array
     */
    public function validateProduct($fields)
    {
        $data = [
            'sku_err' => '',
            'name_err' => '',
            'price_err' => ''
        ];
        if (empty($fields['sku'])) {
            $data['sku_err'] = 'Please enter SKU';
        } else {
            $fields['sku'] = strtoupper(trim($fields['sku']));
            $regexp = '/^[a-zA-Z0-9]+$/';
            if (!filter_var(
                    $fields['sku'],
                    FILTER_VALIDATE_REGEXP,
                    array('options' => array('regexp' => $regexp))
                ) or strlen($fields['sku']) < 8 or strlen($fields['sku']) > 12) {
                $data['sku_err'] = 'SKU must be 8-12 characters long. Only English letters and numbers permitted.';
            }
            $checkSku = $this->getProductSku($fields['sku']);
            if ($checkSku) {
                $data['sku_err'] = 'Product with this SKU already exists';
            }
        }

        if (empty($fields['name'])) {
            $data['name_err'] = 'Please enter product name';
        }
        if (strlen($fields['name']) > 255) {
            $data['name_err'] = 'Name too long';
        } else {
            $fields['name'] = (trim($fields['name']));
        }

        if (empty($fields['price'])) {
            $data['price_err'] = 'Please enter price';
        } else {
            $fields['price'] = trim($fields['price']);
            if (strlen($fields['price']) > 9) {
                $data['price_err'] = 'Invalid price';
            }
            if (!filter_var($fields['price'], FILTER_VALIDATE_FLOAT)) {
                $data['price_err'] = 'Enter a valid price in decimal format';
            } else if (floatval($fields['price']) > 999999.99 ) {
                $data['price_err'] = 'The maximum allowed price is 999,999.99 $';
            }
        }

        return $fields + $data;
    }

    /**
     * Attempts to get from database the main product attributes of the product with sku matching the argument
     * @param string $value product sku
     * @return array array that is either empty or contains main product attributes of product whose ID matches argument
     */
    public function getProductSku(string $value): array
    {
        $this->db->query(
            "SELECT
                    product.id as 'id',
                    product.sku as 'sku',
                    product.name as 'productName',
                    product.price as 'productPrice',
                    product.type as 'productType'
                FROM
                    product
                WHERE
                    sku = :value
                ORDER BY
                    product.id;"
        );
        $this->db->bind(':value', $value);
        return $this->db->resultSet();
    }

    /**
     * This method is called after form fields have been successfully validated.
     * This method inserts values from array '$product' into database in two steps. First, main attributes of product
     * are inserted into DB table 'post', then secondary attributes of product are inserted into DB table 'attribute'.
     * @param array $product array containing product attributes, which has passed validation
     * @return bool returns 'true' if posting was successful, 'false' if not
     */
    public function postProduct($product)
    {
        $this->db->query(
            "INSERT INTO product (sku, name, price, type)
                 VALUES (:sku, :name, :price, :type)"
        );
        $this->db->bind(':sku', $product['sku']);
        $this->db->bind(':name', $product['name']);
        $this->db->bind(':price', $product['price']);
        $this->db->bind(':type', $product['productType']);
        if ($this->db->execute()) {
            $removeKeys = [
                'name',
                'name_err',
                'sku',
                'sku_err',
                'price',
                'price_err',
                'productType',
                'secondary_err'
            ];
            foreach ($removeKeys as $key) {
                unset($product[$key]);
            }
            $id = $this->db->getId();
            foreach ($product as $paramKey => $paramValue) {
                $this->db->query(
                    "INSERT INTO attribute (product_id, name, value)
                         VALUES (:product_id, :name, :value)"
                );
                $this->db->bind(':product_id', $id);
                $this->db->bind(':name', $paramKey);
                $this->db->bind(':value', $paramValue);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes from database the products whose IDs match the values provided in '$ids'
     * @param array $ids IDs of products to be deleted
     * @return void
     */
    public function deleteProducts(array $ids)
    {
        $paramName = [];
        $paramValue = [];

        // Prepares the query containing the placeholders for all submitted IDs
        foreach ($ids as $id) {
            $params[':' . $id] = $id;
        }
        $inString = implode(', ', array_keys($params));
        $this->db->query(
            "DELETE FROM product WHERE id IN (" . $inString . ")"
        );
        // Binds the IDs to the query replacing the named PDO placeholders
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        // Executes the DELETE query
        $this->db->execute();
    }
}
