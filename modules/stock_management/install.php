<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The file is responsible for handing the database installation and configuration
 */

if (!$CI->db->table_exists(db_prefix() . '_sm_products')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_products`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NULL DEFAULT NULL,
        `category` VARCHAR(255) NULL DEFAULT NULL,
        `image` VARCHAR(255) NULL DEFAULT NULL,
        `image_name` VARCHAR(255) NULL DEFAULT NULL,
        `code` VARCHAR(255) NULL DEFAULT NULL,
        `wholesale_price` DECIMAL(10,2) NULL DEFAULT NULL,
        `tax` VARCHAR(255) NULL DEFAULT NULL,
        `discount` DECIMAL(10,2) NULL DEFAULT NULL,
        `qty_alert` INT(11) NULL DEFAULT NULL,
        `price` DECIMAL(10,2) NULL DEFAULT NULL,
        `description` TEXT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}


if (!$CI->db->table_exists(db_prefix() . '_sm_categories')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_categories`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NULL DEFAULT NULL,
        `description` TEXT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_at` DATETIME NOT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_at` DATETIME NOT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}


if (!$CI->db->table_exists(db_prefix() . '_sm_warehouses')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_warehouses`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NULL DEFAULT NULL,
        `description` TEXT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_at` DATETIME NOT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_at` DATETIME NOT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

if (!$CI->db->table_exists(db_prefix() . '_sm_checkins')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_checkins`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `item` INT(11) NULL DEFAULT NULL,
        `reference` VARCHAR(50) NULL DEFAULT NULL,
        `date` DATE NULL DEFAULT NULL,
        `supplier` INT(11) NULL DEFAULT NULL,
        `warehouse` INT(11) NULL DEFAULT NULL,
        `units` INT(11) NULL DEFAULT NULL,
        `note` TEXT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}


if (!$CI->db->table_exists(db_prefix() . '_sm_checkouts')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_checkouts`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `item` VARCHAR(50) NULL DEFAULT NULL,
        `reference` VARCHAR(50) NULL DEFAULT NULL,
        `date` DATE NULL DEFAULT NULL,
        `customer` VARCHAR(50) NULL DEFAULT NULL,
        `warehouse` INT(11) NULL DEFAULT NULL,
        `units` INT(11) NULL DEFAULT NULL,
        `note` TEXT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

if (!$CI->db->table_exists(db_prefix() . '_sm_suppliers')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_suppliers`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `company` VARCHAR(255) NULL DEFAULT NULL,
        `address` TEXT NULL,
        `vat_no` VARCHAR(255) NULL DEFAULT NULL,
        `city` VARCHAR(255) NULL DEFAULT NULL,
        `state` VARCHAR(255) NULL DEFAULT NULL,
        `address2` TEXT NULL,
        `website` VARCHAR(255) NULL DEFAULT NULL,
        `phone` VARCHAR(255) NULL DEFAULT NULL,
        `country` INT(11) NULL DEFAULT NULL,
        `currency` INT(11) NULL DEFAULT NULL,
        `postal_code` VARCHAR(255) NULL DEFAULT NULL,
        `other_info` TEXT NULL,
        `is_deleted` TINYINT(1) NULL DEFAULT 0,
        `is_draft` TINYINT(1) NULL DEFAULT 0,
        `created_on` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
        `modified_on` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `created_by` INT(11) NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    
    $CI->db->insert(db_prefix() . '_sm_suppliers', [
        'company' => 'No Supplier',
        'is_deleted' => 0,
        'is_draft' => 0,
    ]);
}
if (!$CI->db->table_exists(db_prefix() . '_sm_warehouse_products')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_warehouse_products`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `warehouse_id` INT(11) NULL DEFAULT NULL,
        `product_id` INT(11) NULL DEFAULT NULL,
        `quantity` INT(11) NULL DEFAULT NULL,
        
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

if (!$CI->db->field_exists('purchase_price', '_sm_checkins')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_checkins`  ADD COLUMN `purchase_price` DECIMAL(15,2) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('balance', '_sm_suppliers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_suppliers`  ADD COLUMN `balance` DECIMAL(15,2) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('address', '_sm_warehouses')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_warehouses`  ADD COLUMN `address` TEXT NULL DEFAULT NULL');
}

if ($CI->db->field_exists('tax', '_sm_products')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_products`  CHANGE COLUMN `tax` `tax` INT(11) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('tax_rate', '_sm_products')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_products`  ADD COLUMN `tax_rate` DECIMAL(15,2) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('shelf', '_sm_checkins')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_checkins`  ADD COLUMN `shelf` VARCHAR(255) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('shelf', '_sm_checkouts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_checkouts`  ADD COLUMN `shelf` VARCHAR(255) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('product_code', '_sm_products')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_products`  ADD COLUMN `product_code` VARCHAR(255) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('is_returned_manually', '_sm_checkins')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_sm_checkins`  ADD COLUMN `is_returned_manually` TINYINT(2) NULL DEFAULT 0');
}

if (!$CI->db->table_exists(db_prefix() . '_sm_adjustments')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_adjustments`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `warehouse` INT(11) NULL DEFAULT NULL,
        `product` INT(11) NULL DEFAULT NULL,
        `quantity` INT(11) NULL DEFAULT NULL,
        `date` DATE NULL DEFAULT NULL,
        `reason` TEXT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,
        
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

if (!$CI->db->table_exists(db_prefix() . '_sm_transfers')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_sm_transfers`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `to_warehouse` INT(11) NULL DEFAULT NULL,
        `from_warehouse` INT(11) NULL DEFAULT NULL,
        `product` INT(11) NULL DEFAULT NULL,
        `quantity` INT(11) NULL DEFAULT NULL,
        `date` DATE NULL DEFAULT NULL,
        `reason` TEXT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,
        
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

add_option('sm_low_stock', 10);

if (!$CI->db->field_exists('stock_supplier', 'estimates')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'estimates`  ADD COLUMN `stock_supplier` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('stock_supplier', 'invoices')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoices`  ADD COLUMN `stock_supplier` INT(11) NULL DEFAULT NULL');
}