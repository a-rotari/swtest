<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Add</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php
    echo URLROOT; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php
    echo URLROOT; ?>/css/style.css">
</head>
<body class="page-body">
<div class="container">
    <header class="main-header">
        <h1>Products List</h1>
        <div class="main-header-controls">
            <input type="submit" value="Save" form="product_form" class="header-button">
            <a class="header-button" href="<?php
            echo URLROOT; ?>">Cancel</a>
        </div>
    </header>
    <main class="main-content">
        <form id="product_form" method="post" action="<?php
        echo URLROOT; ?>/add-product" class="product-form">
            <fieldset class="product-form-main">
                <p>
                    <label for="sku" form="product_form">SKU</label>
                    <input type="text" id="sku" name="sku" placeholder="F1234567" value="<?php
                    echo $data['sku']; ?>">
                    <span class="invalid-feedback"><?php
                        echo $data['sku_err']; ?></span>
                </p>
                <p>
                    <label for="name" form="product_form">Name</label>
                    <input type="text" id="name" name="name" placeholder="Product Name" value="<?php
                    echo $data['name']; ?>">
                    <span class="invalid-feedback"><?php
                        echo $data['name_err']; ?></span>
                </p>
                <p>
                    <label for="price" form="product_form">Price (&#36)</label>
                    <input type="text" id="price" name="price" placeholder="7.75" value="<?php
                    echo $data['price']; ?>">
                    <span class="invalid-feedback"><?php
                        echo $data['price_err']; ?></span>
                </p>
            </fieldset>
            <fieldset class="product-form-switcher">
                <label class="product-switcher" for="productType" form="product_form">Type Switcher</label>
                <select name="productType" id="productType">
                    <?php
                    foreach (PRODUCTTYPES as $ptype => $pvalue) : ?>
                        <option id="<?php
                        echo $ptype; ?>" value="<?php
                        echo $ptype; ?>" <?php
                        if ($_SESSION['productType'] && ($_SESSION['productType'] == $ptype)) {
                            echo 'selected';
                        } ?>><?php
                            echo $pvalue; ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="product-form-secondary" id="product-form-secondary">
            </fieldset>
            <?php
            foreach ($data['secondary_err'] as $error_message) : ?>
                <span class="invalid-feedback"><?php
                    echo $error_message; ?></span>
            <?php
            endforeach; ?>
        </form>
    </main>
    <footer class="main-footer">
        <p>Scandiweb Test assignment</p>
    </footer>
</div>
<script src="<?php
echo URLROOT; ?>/js/script.js"></script>
</body>
</html>