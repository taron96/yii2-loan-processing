<?php

return [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'pgsql:host=db;port=5432;dbname=loans', // Внутри Docker используем имя сервиса 'db'
    'username' => 'user',
    'password' => 'password',
    'charset'  => 'utf8',
];
