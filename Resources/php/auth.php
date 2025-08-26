<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

include "db.inc.php";
include "validation.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
        $fname = setup_input($_POST["firstname"]);
        $lname = setup_input($_POST["lastname"]);
        $email = setup_input($_POST["email"]);
        $password = $_POST["password"];
        $confirm = $_POST["confirm"];

        // Validate passwords match
        if ($password !== $confirm) {
            header("Location: ../html/usersignup.php?error=password_mismatch");
            exit();
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../html/usersignup.php?error=invalid_email");
            exit();
        }

        // Check if email is already used
        if (isUsed("email", $email, "user")) {
            echo '<div class="message error">Email already in use</div>';
            exit();
        }

        // Hash password
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user with prepared statement
        $stmt = $conn->prepare("INSERT INTO user (u_firstname, u_lastname, u_email, u_password, u_age, u_occupation, u_gender, u_profit, taken_survey) VALUES (?, ?, ?, ?, '', '', '', '0', '0')");
        $stmt->bind_param("ssss", $fname, $lname, $email, $password);
        if ($stmt->execute()) {
            if (isset($_POST['deleting'])) {
                header("Location: ../html/admin.php?table=" . $_POST['deleting']);
            } else {
                header("Location: ../html/login.php?signup=success");
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if (isset($_POST["email"]) && isset($_POST["delete"])) {
        $email = setup_input($_POST["email"]);
        deleteutable('user', $email);
        header("Location: ../html/admin.php");
        exit();
    }
}

ob_end_flush();
?>
