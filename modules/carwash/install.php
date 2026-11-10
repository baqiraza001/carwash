<?php
// VERSION: 2026-11-10 07:47 PKT - Insurance information: insurance_policy_number + insurance_customer_number columns

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . '_client_vehicles')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_client_vehicles`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `invoice_id` INT(11) NOT NULL,
        `vehicle` VARCHAR(255) NULL DEFAULT NULL,
        `vin` VARCHAR(255) NULL DEFAULT NULL,
        `clientid` INT(11) NULL DEFAULT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

if (!$CI->db->field_exists('plate_no', '_client_vehicles')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_client_vehicles`  ADD COLUMN `plate_no` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('client_id', 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoicepaymentrecords`  ADD COLUMN `client_id` INT(11) NULL DEFAULT NULL');
}


if (!$CI->db->field_exists('estimate_id', 'invoices')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoices`  ADD COLUMN `estimate_id` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('credit_amount', 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`  ADD COLUMN `credit_amount` DOUBLE(15, 2) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('client_taxes', 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`  ADD COLUMN `client_taxes` VARCHAR(500) NULL DEFAULT NULL');
}


if (!$CI->db->field_exists('insurance_policy_number', 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`  ADD COLUMN `insurance_policy_number` VARCHAR(255) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('insurance_customer_number', 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients`  ADD COLUMN `insurance_customer_number` VARCHAR(255) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('item_id', 'invoices')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoices`  ADD COLUMN `item_id` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('item_id', 'itemable')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'itemable`  ADD COLUMN `item_id` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('credit_amount_applied', 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoicepaymentrecords`  ADD COLUMN `credit_amount_applied` TINYINT(4) NULL DEFAULT NULL');
}


if (!$CI->db->table_exists(db_prefix() . '_carwash_payers')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_carwash_payers`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `invoice_id` INT(11) NOT NULL,
        `name` VARCHAR(500) NULL DEFAULT NULL,
        `email` VARCHAR(500) NULL DEFAULT NULL,
        `phone` VARCHAR(500) NULL DEFAULT NULL,
        `policy_number` VARCHAR(500) NULL DEFAULT NULL,
        `authorization_number` VARCHAR(500) NULL DEFAULT NULL,
        `date_of_loss` DATE NULL DEFAULT NULL,
        `purchase` VARCHAR(500) NULL DEFAULT NULL,
        `order_number` VARCHAR(500) NULL DEFAULT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

if (!$CI->db->field_exists('estimate_id', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `estimate_id` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('active', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `active` TINYINT(4) NULL DEFAULT 0');
}

if (!$CI->db->field_exists('fleet_or_unit', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `fleet_or_unit` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('vehicle_make', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `vehicle_make` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('model', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `model` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('vin', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `vin` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('plate_no', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `plate_no` VARCHAR(500) NULL DEFAULT NULL');
}



if (!$CI->db->field_exists('client_id', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `client_id` INT(11) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('carwash_payer_options', 'invoices')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'invoices`  ADD COLUMN `carwash_payer_options` VARCHAR(500) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('carwash_payer_options', 'estimates')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'estimates`  ADD COLUMN `carwash_payer_options` VARCHAR(500) NULL DEFAULT NULL');
}


if (!$CI->db->field_exists('carwash_payer_options', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `carwash_payer_options` VARCHAR(700) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('amount', '_carwash_payers')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . '_carwash_payers`  ADD COLUMN `amount` DECIMAL(15,2) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('sale_price', 'items')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'items`  ADD COLUMN `sale_price` DECIMAL(15,2) NULL DEFAULT NULL');
}

if (!$CI->db->field_exists('purchase_price', 'items')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'items`  ADD COLUMN `purchase_price` DECIMAL(15,2) NULL DEFAULT NULL');
}


if (!$CI->db->field_exists('name', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `name` VARCHAR(500) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('phone', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `phone` VARCHAR(30) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('email', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `email` VARCHAR(250) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('supplier', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `supplier` VARCHAR(300) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('policy_number', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `policy_number` VARCHAR(100) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('vin', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `vin` VARCHAR(200) NULL DEFAULT NULL');
}
if (!$CI->db->field_exists('car_details', 'events')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'events`  ADD COLUMN `car_details` TEXT NULL DEFAULT NULL');
}

if (!$CI->db->table_exists(db_prefix() . '_carwash_terms_conditions')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_carwash_terms_conditions`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `nature` VARCHAR(255) NULL DEFAULT NULL,
        `description` TEXT NULL DEFAULT NULL,
        `created_by` INT(11) NULL DEFAULT NULL,
        `created_on` DATETIME NULL DEFAULT NULL,
        `modified_by` INT(11) NULL DEFAULT NULL,
        `modified_on` DATETIME NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

/**
 * Add index only if an existing index does not already cover
 * the required columns in the same order.
 */
if (!function_exists('carwash_add_index_if_missing')) {
    function carwash_add_index_if_missing($table, $indexName, $columns)
    {
        $CI = &get_instance();

        // Check table exists
        if (!$CI->db->table_exists($table)) {
            return;
        }

        // Check all required columns exist
        foreach ($columns as $column) {
            $columnExists = $CI->db->query(
                'SHOW COLUMNS FROM `' . $table . '` LIKE ' .
                $CI->db->escape($column)
            )->num_rows();

            if (!$columnExists) {
                return;
            }
        }

        // Get existing indexes
        $indexes = $CI->db->query(
            'SHOW INDEX FROM `' . $table . '`'
        )->result_array();

        $existingIndexes = [];

        foreach ($indexes as $index) {
            $existingIndexes[$index['Key_name']][(int) $index['Seq_in_index']] =
                $index['Column_name'];
        }

        // Check whether an existing index already covers these columns
        foreach ($existingIndexes as $existingColumns) {
            ksort($existingColumns);

            $existingColumns = array_values($existingColumns);

            if (
                array_slice($existingColumns, 0, count($columns)) === $columns
            ) {
                return;
            }
        }

        // Add index
        $quotedColumns = array_map(function ($column) {
            return '`' . $column . '`';
        }, $columns);

        $CI->db->query(
            'ALTER TABLE `' . $table . '` ADD INDEX `' . $indexName .
            '` (' . implode(', ', $quotedColumns) . ')'
        );
    }
}

// Client vehicles
carwash_add_index_if_missing(
    db_prefix() . '_client_vehicles',
    'idx_clientid',
    ['clientid']
);

// Customer groups
carwash_add_index_if_missing(
    db_prefix() . 'customers_groups',
    'idx_customer_group',
    ['customer_id', 'id']
);

// Custom fields values
carwash_add_index_if_missing(
    db_prefix() . 'customfieldsvalues',
    'idx_customer_customfield',
    ['relid', 'fieldto', 'fieldid']
);