<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'giacinta_blog');

// Create connection
function getConnection() {
    $connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
    // Set charset to utf8
    $connection->set_charset("utf8");
    
    return $connection;
}

// Function to create database and table if not exists
function initializeDatabase() {
    // Create connection without database
    $connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);
    
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8 COLLATE utf8_general_ci";
    if ($connection->query($sql) === TRUE) {
        // Select the database
        $connection->select_db(DB_NAME);
        
        // Create comments table if not exists
        $createTable = "CREATE TABLE IF NOT EXISTS comments (
            id INT(11) NOT NULL AUTO_INCREMENT,
            section VARCHAR(50) NOT NULL,
            name VARCHAR(100) NOT NULL,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        
        if ($connection->query($createTable) === TRUE) {
            echo "Database and table created successfully";
        } else {
            echo "Error creating table: " . $connection->error;
        }
    } else {
        echo "Error creating database: " . $connection->error;
    }
    
    $connection->close();
}

// Initialize database on first run
// Uncomment the line below to create database and table
// initializeDatabase();
?>