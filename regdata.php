<?php
$servername = "localhost"; // Replace with your database server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "demo_database"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully<br>";

$sql = "CREATE TABLE IF NOT EXISTS registerform_table (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    country VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    // echo "Table 'registerform_table' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


// ?>
