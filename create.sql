CREATE TABLE `product`
(
    `id`    int(11)                                 NOT NULL AUTO_INCREMENT,
    `sku`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `name`  varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `price` decimal(8, 2)                           NOT NULL,
    `type`  varchar(50) COLLATE utf8mb4_unicode_ci  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `sku` (`sku`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 32
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `attribute`
(
    `id`         int(11)                                 NOT NULL AUTO_INCREMENT,
    `product_id` int(11)                                 NOT NULL,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `value`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `attribute_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 32
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

