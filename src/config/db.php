<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => $_ENV['DB_DSN'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
    'enableSchemaCache' => YII_ENV_PROD,
    'schemaCacheDuration' => 3600,
];