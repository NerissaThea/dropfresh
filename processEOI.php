<?php
// Include the settings file for database connection details
require_once 'settings.php';
require_once 'header.inc';

$errors = []; // Initialize the $errors array
$confirmation = false; // Initialize confirmation state

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $jobref = sanitize($_POST['jobref']);
    $firstname = sanitize($_POST['firstname']);
    $lastname = sanitize($_POST['lastname']);
    $date = sanitize($_POST['date']);
    $gender = sanitize($_POST['gender']);
    $address = sanitize($_POST['address']);
    $suburb = sanitize($_POST['suburb']);
    $state = sanitize($_POST['state']);
    $postcode = sanitize($_POST['postcode']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    // Check if skills array is set
    $skills = isset($_POST['skills']) ? $_POST['skills'] : [];
    // Check if 'otherskills' key exists in $_POST
    $otherskills = isset($_POST['otherskills']) ? sanitize($_POST['otherskills']) : '';
    $requiredFields = array('jobref', 'firstname', 'lastname', 'date', 'gender', 'address', 'suburb', 'state', 'postcode', 'email', 'phone');
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "The field '$field' is required.";
        }
    }

    // Validate email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate phone number
    if (!empty($phone) && !preg_match('/^[0-9]{8,12}$/', $phone)) {
        $errors[] = "Invalid phone number format. Please enter a number between 8 and 12 digits.";
    }

    // Validate the "Other" skills field
    if (!empty($skills) && in_array('Other', $skills) && empty($otherskills)) {
        $errors[] = "Please enter your other skills.";
    }

    // Validate postcode based on state
    $postcodeValidationResult = validatePostcode($state, $postcode);
    if ($postcodeValidationResult !== true) {
        $errors[] = $postcodeValidationResult;
    }

    // If there are no errors, process the form data
    if (empty($errors)) {
        // Connect to the database
        $conn = new mysqli($host, $user, $pwd, $sql_db);

        // Check if the 'jobs' table exists, if not, create it
        if (!tableExists($conn, 'jobs')) {
            createJobsTable($conn);
        }

        $stmt = $conn->prepare("INSERT INTO jobs (jobref, firstname, lastname, date, gender, address, suburb, state, postcode, email, phone, skills, otherskills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $skillsString = implode(',', $skills);
        $stmt->bind_param("sssssssssssss", $jobref, $firstname, $lastname, $date, $gender, $address, $suburb, $state, $postcode, $email, $phone, $skillsString, $otherskills);

        // Execute the query
        if ($stmt->execute()) {
            // Get the auto-generated EOINumber
            $eoi_Number = $stmt->insert_id;
            $_SESSION['eoi_number'] = $eoi_Number;
            // Set confirmation state to true
            $confirmation = true;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}

// If confirmation is true, display confirmation message and stay on process page
if ($confirmation) {
    // Display the confirmation message with HTML
    echo '
    <div class="process">
        <div class="container">
        <h1>Job Application Confirmation</h1>
        <p class="container-p"><strong>Job Reference Number:</strong> ' . htmlspecialchars($jobref) . '</p>
        <p class="container-p"><strong>First Name:</strong> ' . htmlspecialchars($firstname) . '</p>
        <p class="container-p"><strong>Last Name:</strong> ' . htmlspecialchars($lastname) . '</p>
        <p class="container-p"><strong>Date of Birth:</strong> ' . htmlspecialchars($date) . '</p>
        <p class="container-p"><strong>Gender:</strong> ' . htmlspecialchars($gender) . '</p>
        <p class="container-p"><strong>Address:</strong> ' . htmlspecialchars($address) . '</p>
        <p class="container-p"><strong>Suburb/Town:</strong> ' . htmlspecialchars($suburb) . '</p>
        <p class="container-p"><strong>State:</strong> ' . htmlspecialchars($state) . '</p>
        <p class="container-p"><strong>Postcode:</strong> ' . htmlspecialchars($postcode) . '</p>
        <p class="container-p"><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p class="container-p"><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>
        <p class="container-p"><strong>Skills:</strong> ' . htmlspecialchars(implode(', ', $skills)) . '</p>
        <p class="container-p"><strong>Other Skills:</strong> ' . htmlspecialchars($otherskills) . '</p>
        </div>
    </div>
    ';
} else {
    // Display errors
    if (!empty($errors)) {
        echo "<h1 class='error'>Please fix the following errors:</h1>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo '<meta http-equiv="refresh" content="5;url=index.php?pg=apply">';
        exit;
    }
}

// Sanitize input function
function sanitize($input) {
    if (is_array($input)) {
        // If it's an array, use recursion to process each element
        foreach ($input as $key => $value) {
            $input[$key] = sanitize($value);
        }
        return $input;
    } else {
        // If it's a string, perform sanitation steps
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}

// Check if table exists function
function tableExists($conn, $tableName) {
    $sql = "SHOW TABLES LIKE '$tableName'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

// Function to create the jobs table if it doesn't exist
function createJobsTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS jobs (
        eoi_number INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        jobref VARCHAR(255),
        firstname VARCHAR(255) NOT NULL DEFAULT 'default_value',
        lastname VARCHAR(50) NOT NULL,
        date DATE,
        gender VARCHAR(50),
        suburb VARCHAR(255),
        state VARCHAR(50),
        postcode VARCHAR(50),
        email VARCHAR(255),
        phone INT(11),
        address VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) DEFAULT 'New',
        skills VARCHAR(255) NOT NULL,
        otherskills TEXT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($conn->query($sql) === FALSE) {
        echo "Error creating table: " . $conn->error;
    }
}

// Validate postcode function
function validatePostcode($state, $postcode) {
    // Define the valid postcode ranges for each state
    $postcodeRanges = array(
        'VIC' => array('3000', '3999'),
        'NSW' => array('2000', '2999'),
        'QLD' => array('4000', '4999'),
        'NT' => array('0800', '0899'),
        'WA' => array('6000', '6999'),
        'SA' => array('5000', '5999'),
        'TAS' => array('7000', '7999'),
        'ACT' => array('2600', '2699')
    );

    // Check if the postcode is within the valid range for the selected state
    if (array_key_exists($state, $postcodeRanges)) {
        $minPostcode = $postcodeRanges[$state][0];
        $maxPostcode = $postcodeRanges[$state][1];
        if (!(strlen($postcode) === 4 && $postcode >= $minPostcode && $postcode <= $maxPostcode)) {
            return "Invalid postcode for the selected state.";
        }
    } else {
        return "Invalid state selected.";
    }
    return true;
}
?>
