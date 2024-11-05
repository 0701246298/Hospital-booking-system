<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "message";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO contact (Firstname, Lastname, Email, Contact, Message) VALUES (?, ?, ?, ?, ?)");

        // Check if prepare() succeeded
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $contact, $message);

        // Execute the query
        if ($stmt->execute() === false) {
            die('Execute failed: ' . $stmt->error);
        }

        // Success message
        echo '<div id="success" style="display: block; position: fixed; top: 0; left: 50%; transform: translateX(-50%); z-index: 1000; background-color: green; padding: 10px; width: 20%; text-align: center;">
            <p style="color: white;">Thank you for your message!</p>
        </div>';

        // Redirect after 2 seconds
        echo '<script>
                setTimeout(function() {
                    document.getElementById("success").style.display = "none";
                    window.location.href = "contact.html";
                }, 2000);
            </script>';

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid email format.";
    }
}
?>