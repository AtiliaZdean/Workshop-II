<?php
$servername = "localhost";
$username = "shira";
$password = "";
$dbname = "FSvolunteer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} /*else {
    echo "Database connection successful!";
} */

/*$host = "192.168.192.222";
$port = "5432";
$dbname = "FoodShare";
$user = "postgres";
$password1 = "postgres";

// Establish connection
$conn1 = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password1");

// Check connection
if (!$conn1) {
    die("Error: Unable to connect to PostgreSQL." . pg_last_error());
} 

/* $servername2 = "192.168.192.118";
$username2 = "syaf";
$password2 = "syaf0908";
$dbname2 = "module_budget";

// Create connection
$conn2 = new mysqli($servername2, $username2, $password2, $dbname2);

// Check connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}*/
?>