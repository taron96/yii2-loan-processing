<?php

/**
 * Database configuration
 * Host is set to 'db' for Docker internal networking.
 * Accessible via 'localhost:5432' from the host machine.
 */
return [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'pgsql:host=db;port=5432;dbname=loans',
    'username' => 'user',
    'password' => 'password',
    'charset'  => 'utf8',
];
