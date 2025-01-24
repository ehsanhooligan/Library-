<?php
$host = 'localhost'; // Change as needed
$user = 'root';      // Database username
$password = '';      // Database password
$dbname = 'library'; // Database name

// Establish connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch books
$sql = "SELECT name, author, category, quantity FROM books";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Return as JSON
echo json_encode(['books' => $books]);

$conn->close();
?>
