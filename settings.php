<?php
$host = "feenix-mariadb.swin.edu.au";
$user = "s104977535"; // your user name
$pwd = "Sakiotruongnee2311@"; // your password (date of birth ddmmyy unless changed)
$sql_db = "s104977535_db"; // your database

// Create a new mysqli connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set and collation for the connection
$conn->set_charset("utf8mb4");

// Form processing function
function processForm()
{
    global $conn;
    // Check if the database connection is established
    if (!$conn) {
        echo "<p>Database connection error</p>";
        return;
    }
    // Function to convert date format
    function convertDateFormat($dateString)
    {
        if (empty($dateString)) {
            return null;
        }
        $parts = explode('-', $dateString);
        if (count($parts) == 3) {
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];
            if (checkdate($month, $day, $year)) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }
        return null;
    }

    // Get form data
    $jobref = trim($_POST["jobref"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $date = trim($_POST["date"]);
    $gender = trim($_POST["gender"]);
    $address = trim($_POST["address"]);
    $suburb = trim($_POST["suburb"]);
    $state = trim($_POST["state"]);
    $postcode = trim($_POST["postcode"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $skills = isset($_POST["skills"]) ? $_POST["skills"] : [];
    $otherSkills = isset($_POST["otherskills"]) ? trim($_POST["otherskills"]) : "";

    // Check and convert date format
    $date = convertDateFormat($date);
    if ($date === null) {
        echo "Invalid date format";
        return;
    }

    // Check if required fields are empty
    if (empty($jobref) || empty($firstname) || empty($lastname) || empty($date) || empty($gender) || empty($address) || empty($suburb) || empty($state) || empty($postcode) || empty($email) || empty($phone)) {
        echo "<p class=\"wrong\">Please fill in all required fields.</p>";
        return;
    }

    // Hash password
    $password = trim($_POST["password"]);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Save data to the `jobs` table
    $query = "INSERT INTO jobs (jobref, firstname, lastname, date, gender, address, suburb, state, postcode, email, phone, password, skills, other_skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssss", $jobref, $firstname, $lastname, $date, $gender, $address, $suburb, $state, $postcode, $email, $phone, $hashedPassword, $skillsString, $otherSkills);

    if ($stmt->execute()) {
        $eoi_number = $stmt->insert_id;
        echo "<p class=\"ok\">New job record added successfully</p>";
        header("Location: index.php?pg=processEOI"); // Redirect to display.php
    } else {
        echo "<p class=\"wrong\">Error: " . $stmt->error . "</p>";
    }

    // Update the status of records after a certain period of time
    $query = "UPDATE jobs SET status = 'Uploaded' WHERE status = 'Pending' AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 1";
    $result = $conn->query($query);

    $stmt->close();
}
?>
