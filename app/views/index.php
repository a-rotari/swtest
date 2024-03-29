<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product List</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php
    echo htmlspecialchars(URLROOT); ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php
    echo htmlspecialchars(URLROOT); ?>/css/style.css">
</head>
<body class="page-body">
<div class="container">
    <header class="main-header">
        <h1>Product List</h1>
        <div class="main-header-controls">
            <a class="header-button" href="<?php
            echo htmlspecialchars(URLROOT); ?>/add-product">ADD</a>
            <button class="header-button" id="delete-product-btn" type="button">DELETE<span class="hide-on-mobile"> SELECTED</span></button>
            <a class="header-button" href="https://arotari.com"><span class="hide-on-mobile">ANDREI'S </span>HOME<span class="hide-on-mobile">PAGE</span></a>
        </div>
    </header>
    <main class="main-content">
        <ul class="products-list">
            <?php
            foreach ($data['products'] as $id => $product) : ?>
                <li class="products-list-item">
                    <h2 class="list-item-name"><?php
                        echo htmlspecialchars($product['productName']); ?></h2>
                    <p class="list-item-sku"><?php
                        echo htmlspecialchars($product['sku']); ?></p>
                    <p class="list-item-price"><?php
                        echo htmlspecialchars($product['productPrice']); ?></p>
                    <p class="list-item-custom"><?php
                        echo htmlspecialchars(
                            $product['attributes']['attributeName1'] . $product['attributes']['attributeValue1']
                        ); ?></p>
                    <input aria-label="delete product" class="delete-checkbox" type="checkbox" value="<?php
                    echo htmlspecialchars($id); ?>">
                </li>
            <?php
            endforeach; ?>

        </ul>
    </main>
    <footer class="main-footer">
        <p>Scandiweb Test assignment</p>
    </footer>
</div>
<script src="<?php
echo htmlspecialchars(URLROOT); ?>/js/main.js"></script>
</body>
</html>