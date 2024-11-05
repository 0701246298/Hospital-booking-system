<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $firstname = htmlspecialchars(trim($_POST['FNAME']));
    $lastname = htmlspecialchars(trim($_POST['SURNAME']));
    $password = htmlspecialchars(trim($_POST['PASSWORD']));
    $email = htmlspecialchars(trim($_POST['EMAIL']));

    // Print form data for debugging
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "health";

    // Create a connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected successfully<br>";
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO admins (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New admin registered successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Preparation failed: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
