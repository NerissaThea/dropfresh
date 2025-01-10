<?php
// Import settings from settings.php file
require_once('settings.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    // If not logged in, redirect to the login page
    header("Location: index.php?pg=login");
    exit;
}
// Get the current time
$currentDateTime = date('Y-m-d H:i:s');

// Update status from "Pending" to "Uploaded" if it has been more than 1 minute
$queryPendingToUpload = "UPDATE jobs SET status = 'Uploaded' WHERE status = 'Pending' AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 1";
$resultPendingToUpload = $conn->query($queryPendingToUpload);

// Update status of previously uploaded records
$queryPreviousUploads = "UPDATE jobs SET status = CONCAT('Previously uploaded at ', created_at) WHERE status = 'Uploaded'";
$resultPreviousUploads = $conn->query($queryPreviousUploads);

// Check and handle the results of the queries
if ($resultPendingToUpload && $resultPreviousUploads) {
} else {
    echo "Error updating status: " . $conn->error;
}

// Get the list of job reference codes
$sqlJobRef = "SELECT DISTINCT jobref FROM jobs";
$stmtJobRef = $conn->prepare($sqlJobRef);
$stmtJobRef->execute();
$resultJobRef = $stmtJobRef->get_result();
$jobrefOptions = array();
while ($row = $resultJobRef->fetch_assoc()) {
    $jobrefOptions[] = $row['jobref'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle status update form submission
    if (isset($_POST["update_eoi_number"]) && !empty($_POST["update_eoi_number"])) {
        $update_eoi_number = $_POST["update_eoi_number"];
        $new_status = $_POST["new_status"];
        $custom_status = isset($_POST["custom_status"]) ? $_POST["custom_status"] : null;

        if ($new_status === "Custom" && empty($custom_status)) {
            echo "<p class=\"wrong\">Custom status cannot be empty.</p>";
        } else {
            // Prepare and execute SQL statement to update status
            $queryUpdateStatus = "UPDATE jobs SET status = ? WHERE eoi_number = ?";
            $stmtUpdateStatus = $conn->prepare($queryUpdateStatus);
            $stmtUpdateStatus->bind_param("si", $status_to_update, $update_eoi_number);

            if ($new_status === "Custom") {
                $status_to_update = $custom_status;
            } else {
                $status_to_update = $new_status;
            }

            if ($stmtUpdateStatus->execute()) {
                echo "<p class=\"ok\">Status updated successfully</p>";
                // Redirect to avoid resubmission on page refresh
                header("Location: index.php?pg=manage");
                exit();
            } else {
                echo "<p class=\"wrong\">Error updating status: " . $stmtUpdateStatus->error . "</p>";
            }

            $stmtUpdateStatus->close();
        }
    } else {
        echo "<p class=\"wrong\">Invalid Job ID to update status</p>";
    }

    // Handle job deletion form submission
    if (isset($_POST["delete_jobref"]) && !empty($_POST["delete_jobref"])) {
        $delete_jobref = $_POST["delete_jobref"];

        // Prepare and execute SQL statement to delete the job
        $queryDeleteJob = "DELETE FROM jobs WHERE jobref = ?";
        $stmtDeleteJob = $conn->prepare($queryDeleteJob);
        $stmtDeleteJob->bind_param("s", $delete_jobref);

        if ($stmtDeleteJob->execute()) {
            echo "<p class=\"ok\">Record deleted successfully</p>";
            header("Location: index.php?pg=manage");
            exit(); // End the script after redirection
        } else {
            echo "<p class=\"wrong\">Error deleting record: " . $stmtDeleteJob->error . "</p>";
        }

        $stmtDeleteJob->close();
    } else {
        echo "<p class=\"wrong\">Invalid Job ID to delete</p>";
    }
}

// Get the filter parameter from URL (if any)
$filterJobref = isset($_GET['jobref']) ? $_GET['jobref'] : '';
$filterFirstName = isset($_GET['firstname']) ? $_GET['firstname'] : '';
$filterLastName = isset($_GET['lastname']) ? $_GET['lastname'] : '';

// Get the sort parameters from the URL (if any)
$sortBy = isset($_GET['sort-by']) ? $_GET['sort-by'] : 'eoi_number';
$sortOrder = isset($_GET['sort-order']) ? $_GET['sort-order'] : 'ASC';

// Prepare and execute SQL statement to get the list of records
$sql = "SELECT c.*, TIMESTAMPDIFF(SECOND, c.created_at, NOW()) AS time_diff
        FROM jobs c
        WHERE c.jobref LIKE ?
        AND (c.firstname LIKE ? OR ? = '')
        AND (c.lastname LIKE ? OR ? = '')
        ORDER BY c.$sortBy $sortOrder";
$stmt = $conn->prepare($sql);
$filterJobrefValue = "%" . $filterJobref . "%";
$filterFirstNameValue = "%" . $filterFirstName . "%";
$filterLastNameValue = "%" . $filterLastName . "%";
$stmt->bind_param("sssss", $filterJobrefValue, $filterFirstNameValue, $filterFirstName, $filterLastNameValue, $filterLastName);
$stmt->execute();
$result = $stmt->get_result();

function calculateAge($birthdate)
{
    $today = date('Y-m-d');
    $diff = date_diff(date_create($birthdate), date_create($today));
    return $diff->format('%y');
}

?>
<div>
    <h1 class="cssh1">View All Jobs Records</h1>
    <form method="get" action="index.php">
        <input type="hidden" name="pg" value="manage">
        <button type="submit">Show All</button>
    </form>
    <div class="filter-container">
        <form method="post" action="index.php?pg=manage">
            <label for="update_eoi_number">Job ID to update status:</label>
            <input type="text" id="update_eoi_number" name="update_eoi_number" required>

            <select name="new_status">
                <option value="New">New</option>
                <option value="Current">Current</option>
                <option value="Final">Final</option>
            </select>
            <input type="submit" value="Update">
        </form>
        <form method="post" action="index.php?pg=manage">
            <label for="delete_jobref">Job Reference number to delete:</label>
            <input type="text" id="delete_jobref" name="delete_jobref" required>

            <input type="submit" value="Delete">
        </form>
        <form method="get" action="index.php">
            <input type="hidden" name="pg" value="manage">
            <label for="jobref-filter">Job Reference:</label>
            <input type="text" id="jobref-filter" name="jobref" placeholder="Enter job reference code">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" placeholder="Enter first name">
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" placeholder="Enter last name">
            <button type="submit">Search</button>
        </form>
        <form method="get" action="index.php">
            <input type="hidden" name="pg" value="manage">
            <label for="sort-by">Sort by:</label>
            <select id="sort-by" name="sort-by">
                <option value="eoi_number">Job ID</option>
                <option value="firstname">First Name</option>
                <option value="lastname">Last Name</option>
                <option value="status">Status</option>
            </select>
            <label for="sort-order">Sort order:</label>
            <select id="sort-order" name="sort-order">
                <option value="ASC">Ascending</option>
                <option value="DESC">Descending</option>
            </select>
            <button type="submit">Sort</button>
        </form>

    </div>

    <table class="viewtable">
        <thead>
            <tr>
                <th>Job ID</th>
                <th>Job Reference Code</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Age group</th>
                <th>Gender</th>
                <th>Suburb</th>
                <th>State</th>
                <th>Postcode</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Calculate age and age group for each record
                    $date = $row["date"];
                    $age = calculateAge($date);
                    if ($age >= 20 && $age <= 29) {
                        $age_group = "20-29";
                    } elseif ($age >= 30 && $age <= 39) {
                        $age_group = "30-39";
                    } elseif ($age > 39) {
                        $age_group = "40+";
                    } else {
                        $age_group = "-";
                    }
                    echo "<tr>";
                    echo "<td>" . ($row["eoi_number"] ? $row["eoi_number"] : "-") . "</td>";
                    echo "<td>" . ($row["jobref"] ? $row["jobref"] : "-") . "</td>";
                    echo "<td>" . ($row["firstname"] ? $row["firstname"] : "-") . "</td>";
                    echo "<td>" . ($row["lastname"] ? $row["lastname"] : "-") . "</td>";
                    echo "<td>" . (isset($row["date"]) ? calculateAge($row["date"]) : "-") . "</td>";
                    echo "<td>" . $age_group . "</td>";
                    echo "<td>" . ($row["gender"] ? $row["gender"] : "-") . "</td>";
                    echo "<td>" . ($row["suburb"] ? $row["suburb"] : "-") . "</td>";
                    echo "<td>" . ($row["state"] ? $row["state"] : "-") . "</td>";
                    echo "<td>" . ($row["postcode"] ? $row["postcode"] : "-") . "</td>";
                    echo "<td>" . ($row["email"] ? $row["email"] : "-") . "</td>";
                    echo "<td>" . ($row["phone"] ? $row["phone"] : "-") . "</td>";
                    echo "<td>" . ($row["address"] ? $row["address"] : "-") . "</td>";
                    echo "<td>" . ($row["status"] ? $row["status"] : "-") . "</td>";
                    echo "<td><form method=\"post\" action=\"index.php?pg=manage\"><input type=\"hidden\" name=\"delete_jobref\" value=\"" . $row['jobref'] . "\"><input type=\"submit\" value=\"Delete\"></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="index.php?pg=logout" class="btn-lgout">Logout</a>
</div>

<?php
$stmt->close();
$conn->close();
?>