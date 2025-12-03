<?php
// Set the content type header for XML
header("Content-Type: application/xml");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");

$servername = "sql207.infinityfree.com";
$username = "if0_40571271";
$password = "GMB7snG9jLuD";
$dbname = "if0_40571271_dreamfit";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // XML Error response for DB connection failure
    echo "<response><status>error</status><message>".$conn->connect_error."</message></response>";
    exit;
}

// ================= GET USER DATA (WORKING PART) =================
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $email = $_GET["email"] ?? "";

    if ($email == "") {
        // XML Error response
        echo "<response><status>error</status><message>No email provided</message></response>";
        exit;
    }

    $stmt = $conn->prepare("
        SELECT age, weight, height, workouts_per_week, activity_level, goal, sex, date_of_birth, country
        FROM users
        WHERE user_email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // XML Error response
        echo "<response><status>error</status><message>User not found</message></response>";
        exit;
    }

    $row = $result->fetch_assoc();

    // XML Success response
    echo "<response><status>success</status><user>";
    foreach ($row as $key => $value) {
        echo "<$key>" . htmlspecialchars($value) . "</$key>";
    }
    echo "</user></response>";
    exit;
}

// ================= UPDATE USER DATA (MODIFIED TO OUTPUT XML) =================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Since the HTML/JS is expecting XML output, you may want to check if the input is JSON or XML
    // If your client-side JS is sending data as JSON (as implied by file_get_contents("php://input")), keep this part:
    $input = json_decode(file_get_contents("php://input"), true); 
    // If your client-side JS sends data as form-urlencoded, you should use $_POST

    if (!isset($input["email"]) || $input["email"] == "") {
        // XML Error response
        echo "<response><status>error</status><message>Email missing</message></response>";
        exit;
    }

    // Prepare update statement
    $stmt = $conn->prepare("
        UPDATE users SET 
            age = ?, 
            weight = ?, 
            height = ?, 
            workouts_per_week = ?, 
            activity_level = ?, 
            goal = ?, 
            sex = ?, 
            date_of_birth = ?, 
            country = ?
        WHERE user_email = ?
    ");

    $stmt->bind_param(
        "iddissssss",
        $input["age"],
        $input["weight"],
        $input["height"],
        $input["workouts"],
        $input["activity"],
        $input["goal"],
        $input["sex"],
        $input["dob"],
        $input["country"],
        $input["email"]
    );

    if ($stmt->execute()) {
        // *** MODIFIED FOR XML SUCCESS OUTPUT ***
        echo "<response><status>success</status></response>";
    } else {
        // *** MODIFIED FOR XML ERROR OUTPUT ***
        // Use htmlspecialchars to ensure the error message is safe for XML
        echo "<response><status>error</status><message>".htmlspecialchars($stmt->error)."</message></response>";
    }
    exit;
}

$conn->close();
?>