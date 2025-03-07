<?php
// Database connection
$host = "localhost";
$user = "root"; // default username for localhost
$pass = ""; // default password for localhost
$db = "newlink"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Handle connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'code' is set in the URL
if (isset($_GET['code'])) {
    $short_code = $_GET['code'];

    // Prepare and execute SQL query to fetch original URL based on short code
    $stmt = $conn->prepare("SELECT original_url FROM urls WHERE short_code = ?");
    $stmt->bind_param("s", $short_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the original URL
        $row = $result->fetch_assoc();
        $original_url = $row['original_url'];

        // Redirect to the original URL
        header("Location: " . $original_url);
        exit();
    } else {
        // Short code not found in the database
        echo "URL not found!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No short code provided!";
}
