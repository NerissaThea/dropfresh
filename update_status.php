<?php
require_once('./object/settings.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST["car_id"];
    $new_status = $_POST["new_status"];

    if ($new_status == "New" || $new_status == "Current" || $new_status == "Final") {
        $query = "UPDATE cars SET status = ? WHERE car_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_status, $car_id);

        if ($stmt->execute()) {
            echo "<p class=\"ok\">Status updated successfully</p>";
        } else {
            echo "<p class=\"wrong\">Error updating status: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class=\"wrong\">Invalid status value</p>";
    }
}

// Redirect back to the view_all page or any other desired page
header("Location: index.php?pg=enhanhcement");
exit();
?>