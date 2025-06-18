<?php
/**
 * Database Configuration Example
 * Change this filename to database.php and update the values with your actual database credentials.
 * 
 */

return [
    'host' => 'postgres',        // Database host -- leave postgres if using docker
    'dbname' => 'meetspace',      // Database name
    'username' => 'your_username', // Database username
    'password' => 'your_password', // Database password
    'port' => '5432',             // Database port (default for PostgreSQL)
    'charset' => 'utf8'           // Character set
]; 