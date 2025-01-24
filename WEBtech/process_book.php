<?php

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $bookName = sanitize_input($_POST['book_name']);
    $authorName = sanitize_input($_POST['author_name']);
    $isbn = sanitize_input($_POST['isbn']);
    $count = sanitize_input($_POST['count']);
    $category = sanitize_input($_POST['category']);

    // Validation logic
    if (!preg_match("/^[a-zA-Z\s]+$/", $bookName)) {
        die("Error: Invalid book name. Only letters and spaces are allowed.");
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $authorName)) {
        die("Error: Invalid author name. Only letters and spaces are allowed.");
    }

    if (!preg_match("/^\d+$/", $isbn)) {
        die("Error: Invalid ISBN. Only numbers are allowed.");
    }

    if (!is_numeric($count) || $count <= 0) {
        die("Error: Invalid book count. Must be a positive number.");
    }

    if (empty($category)) {
        die("Error: Book category must be selected.");
    }

    // Check for duplicate book entries using cookies
    if (isset($_COOKIE['isbn']) && $_COOKIE['isbn'] == $isbn) {
        die("Error: A book with ISBN '$isbn' has already been added within 10 days.");
    }

    // Display receipt for the added book
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Receipt</title>";
    echo "<style>";
    echo "body { font-family: Arial, sans-serif; margin: 20px; }";
    echo ".receipt { border: 1px solid #333; padding: 20px; max-width: 500px; margin: auto; }";
    echo "h2 { text-align: center; }";
    echo "p { margin: 5px 0; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<div class='receipt'>";
    echo "<h2>Book Addition Receipt</h2>";
    echo "<p><strong>Book Name:</strong> $bookName</p>";
    echo "<p><strong>Author Name:</strong> $authorName</p>";
    echo "<p><strong>ISBN Number:</strong> $isbn</p>";
    echo "<p><strong>Book Count:</strong> $count</p>";
    echo "<p><strong>Category:</strong> $category</p>";
    echo "<hr>";
    echo "<p>Thank you for adding a new book to our library!</p>";
    echo "</div>";
    echo "</body>";
    echo "</html>";

    // Set cookies to persist book data for 10 days
    setcookie('isbn', $isbn, time() + (10 * 24 * 60 * 60), '/');
} else {
    echo "No form data received.";
}
?>
