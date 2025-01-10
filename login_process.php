<?php
// Connect to the database
require_once('settings.php');

// Handle login data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    // Check if required fields are filled
    if(empty($username) || empty($password)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Prepare and execute SQL statement to check login information
    $sql = "SELECT user_id, role FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    // Check if the statement preparation is successful
    if(!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Save user information into session
        session_start();
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['role'] = $row['role'];

        // Redirect user to the homepage
        header("Location: index.php?pg=manage");
        exit;
    } else {
        // Display login error message
        echo "Incorrect username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
