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

function generateShortCode($length = 6) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $original_url = $_POST['original_url'];

    // Basic URL validation
    if (filter_var($original_url, FILTER_VALIDATE_URL)) {
        // Generate unique short code
        $short_code = generateShortCode();

        // Prepare and execute SQL query to insert the URL
        $stmt = $conn->prepare("INSERT INTO urls (original_url, short_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $original_url, $short_code);

        if ($stmt->execute()) {
            $shortened_url = "http://localhost/newlink/" . $short_code;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Invalid URL format!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { margin-top: 100px; }
        input[type="text"] { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px; }
        input[type="submit"] { padding: 10px 20px; background-color: #5cb85c; color: white; border: none; border-radius: 5px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #4cae4c; }
        .shortened-url { margin-top: 20px; font-size: 1.2em; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>URL Shortener</h1>
        <form method="POST" action="">
            <input type="text" name="original_url" placeholder="Enter URL" required>
            <input type="submit" value="Shorten">
        </form>

        <?php if (isset($shortened_url)): ?>
            <div class="shortened-url">
                Shortened URL: <a href="<?= $shortened_url ?>" target="_blank"><?= $shortened_url ?></a>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
