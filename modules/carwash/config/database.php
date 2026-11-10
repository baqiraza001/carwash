<?php

/* connect second database*/
$db_encrypt = FALSE;

$config['carwash_db'] = array_merge([
    'dsn'          => '', // Not Supported
    'hostname'     => 'localhost',
    'username'     => 'pacarwas_glass',
    'password'     => 'tBrx9F$0PEtW',
    'database'     => 'pacarwas_glassview',
    'dbdriver'     => defined('APP_DB_DRIVER') ? APP_DB_DRIVER : 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => true,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => false,
    'cachedir'     => '',
    'char_set'     => defined('APP_DB_CHARSET') ? APP_DB_CHARSET : 'utf8',
    'dbcollat'     => defined('APP_DB_COLLATION') ? APP_DB_COLLATION : 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => $db_encrypt,
    'compress'     => false,
    'failover'     => [],
    'save_queries' => true,
], defined('APP_DB_STRICTON') && APP_DB_STRICTON || !defined('APP_DB_STRICTON') ? ['stricton' => false] : []);

