<?php
// WARNING: This is intentionally vulnerable code for educational purposes only.
// Do NOT use in production!

// No CORS headers for restricted access
header('Content-Type: application/json');

// Connect to database without prepared statements or proper error handling
$conn = mysqli_connect("localhost", "root", "", "test_db");

// No input validation or sanitization
$action = $_GET['action'];

if ($action == 'get_user') {
    // SQL Injection vulnerability
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    
    // Exposing sensitive data
    $user = mysqli_fetch_assoc($result);
    echo json_encode($user); // Outputs all user data, including passwords
}

if ($action == 'add_user') {
    // No CSRF protection, no input sanitization
    $username = $_POST['username'];
    $password = $_POST['password']; // Stored in plaintext
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    mysqli_query($conn, $query);
    
    echo json_encode(["status" => "User added"]);
}

if ($action == 'delete_user') {
    $user_id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $query);
    
    echo json_encode(["status" => "User deleted"]);
}

// Dangerous eval() usage allowing arbitrary code execution
if ($action == 'execute') {
    $code = $_POST['code']; // No sanitization of input
    eval($code); // Executes arbitrary PHP code provided by the user
    echo json_encode(["status" => "Code executed"]);
}

// Expose database errors directly to the client
if (mysqli_error($conn)) {
    echo json_encode(["error" => mysqli_error($conn)]);
}

mysqli_close($conn);
?>