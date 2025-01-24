<?php

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = sanitize_input($_POST['username']);
    $studentId = sanitize_input($_POST['id']);
    $bookTitle = sanitize_input($_POST['book_title']);
    $borrowDate = sanitize_input($_POST['bdate']);
    $token = sanitize_input($_POST['token']);
    $returnDate = sanitize_input($_POST['rdate']);
    $paid = isset($_POST['paid']) ? sanitize_input($_POST['paid']) : '2';

    // Validation logic
    if (!preg_match("/^[a-zA-Z\s]+$/", $username)) {
        die("Error: Invalid student name. Only letters and spaces are allowed.");
    }

    if (!preg_match("/^\d{2}-\d{5}-[1-3]$/", $studentId)) {
        die("Error: Invalid student ID format. Expected format: 00-00000-1/2/3.");
    }

    if (empty($bookTitle)) {
        die("Error: Book title cannot be empty.");
    }

    $borrowDateObj = DateTime::createFromFormat('Y-m-d', $borrowDate);
    $returnDateObj = DateTime::createFromFormat('Y-m-d', $returnDate);

    if (!$borrowDateObj || !$returnDateObj || $borrowDateObj > $returnDateObj || $borrowDateObj->diff($returnDateObj)->days > 10) {
        die("Error: Return date must be within 10 days of borrow date and after borrow date.");
    }

    // Check for duplicate borrow actions using cookies
    $borrowHistory = isset($_COOKIE['borrowHistory']) ? json_decode($_COOKIE['borrowHistory'], true) : [];

    foreach ($borrowHistory as $entry) {
        if ($entry['username'] === $username && $entry['bookTitle'] === $bookTitle) {
            die("Error: The book '$bookTitle' has already been borrowed by '$username'.");
        }
    }

    // Add the new borrow information to the history
    $borrowHistory[] = [
        'username' => $username,
        'studentId' => $studentId,
        'bookTitle' => $bookTitle,
        'borrowDate' => $borrowDate,
        'returnDate' => $returnDate,
        'token' => $token,
        'paid' => $paid
    ];

    // Store the updated history in cookies
    setcookie('borrowHistory', json_encode($borrowHistory), time() + (10 * 24 * 60 * 60), '/');

    // Display receipt
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
    echo "<h2>Library Borrow Receipt</h2>";
    echo "<p><strong>Student Name:</strong> $username</p>";
    echo "<p><strong>Student ID:</strong> $studentId</p>";
    echo "<p><strong>Book Title:</strong> $bookTitle</p>";
    echo "<p><strong>Borrow Date:</strong> $borrowDate</p>";
    echo "<p><strong>Return Date:</strong> $returnDate</p>";
    echo "<p><strong>Paid:</strong> " . ($paid == '1' ? 'Yes' : 'No') . "</p>";
    echo "<p><strong>Token:</strong> $token</p>";
    echo "<hr>";
    echo "<p>Thank you for borrowing from our library!</p>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
} else {
    echo "No form data received.";
}
?>
