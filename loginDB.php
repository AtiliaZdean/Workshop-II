<?php
session_start(); // Start session at the beginning

include 'database.php'; // Database connection

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute the query
$sql = "SELECT a.AccountID, a.VolunteerID, a.username, a.password, v.status, v.RoleID, v.ManagerID FROM account a JOIN volunteer v ON a.volunteerID = v.volunteerID WHERE a.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

// Check if a matching row was found
if ($stmt->num_rows == 0) {
    $_SESSION['login_error'] = "Invalid Username or Password.";
    header("Location: login.php"); // Redirect to login page
    exit();
} else {
    // Bind result variables
    $stmt->bind_result($accountID, $volunteerID, $storedUsername, $storedPassword, $status, $roleID, $managerID); // No password verification needed
    $stmt->fetch();

    // Check if the provided password matches the stored plain text password
    if (password_verify($password, $storedPassword)) {
        // Check resignation status
        if ($status === 'Resign Approved') {
            // Fetch manager's contact information if needed
            $managerQuery = "SELECT Name FROM volunteer WHERE VolunteerID = (SELECT ManagerID FROM volunteer WHERE VolunteerID = ?)";
            $managerStmt = $conn->prepare($managerQuery);
            $managerStmt->bind_param("i", $volunteerID);
            $managerStmt->execute();
            $managerStmt->bind_result($managerName);
            $managerStmt->fetch();
            $managerStmt->close(); 

            // Log the failed login attempt
            $stmt = $conn->prepare("INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt) VALUES (?, ?, 'FAILED LOGIN', NOW())");
            $stmt->bind_param("ii", $accountID, $volunteerID);
            $stmt->execute();

            $_SESSION['login_error'] = "Your resignation request is approved by $managerName. You can no longer log in.";
            header("Location: login.php"); // Redirect to login page
            exit();
        } elseif ($status === 'Resign Denied') {
            // Fetch manager's contact information if needed
            $managerQuery = "SELECT Name, ContactNo FROM volunteer WHERE VolunteerID = (SELECT ManagerID FROM volunteer WHERE VolunteerID = ?)";
            $managerStmt = $conn->prepare($managerQuery);
            $managerStmt->bind_param("i", $volunteerID);
            $managerStmt->execute();
            $managerStmt->bind_result($managerName, $managerContact);
            $managerStmt->fetch();
            $managerStmt->close();

            // Log the failed login attempt
            $stmt = $conn->prepare("INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt) VALUES (?, ?, 'FAILED LOGIN', NOW())");
            $stmt->bind_param("ii", $accountID, $volunteerID);
            $stmt->execute();

            $_SESSION['login_error'] = "Your resignation request is denied. You may contact $managerName via $managerContact for consultation. You can no longer log in.";
            header("Location: login.php"); // Redirect to login page
            exit();
        } elseif ($status === 'Requesting Resign') {
            // Fetch manager's contact information if needed
            $managerQuery = "SELECT Name, ContactNo FROM volunteer WHERE VolunteerID = (SELECT ManagerID FROM volunteer WHERE VolunteerID = ?)";
            $managerStmt = $conn->prepare($managerQuery);
            $managerStmt->bind_param("i", $volunteerID);
            $managerStmt->execute();
            $managerStmt->bind_result($managerName, $managerContact);
            $managerStmt->fetch();
            $managerStmt->close();

            // Log the failed login attempt
            $stmt = $conn->prepare("INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt) VALUES (?, ?, 'FAILED LOGIN', NOW())");
            $stmt->bind_param("ii", $accountID, $volunteerID);
            $stmt->execute();

            $_SESSION['login_error'] = "Your resignation request is pending. You may contact $managerName via $managerContact for consultation. You can no longer log in.";
            header("Location: login.php"); // Redirect to login page
            exit();
        } elseif (in_array($role, [1, 2, 4, 6, 7]) && ($status === 'Pending' || $managerID === null)) {
            // Log the failed login attempt
            $stmt = $conn->prepare("INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt) VALUES (?, ?, 'FAILED LOGIN', NOW())");
            $stmt->bind_param("ii", $accountID, $volunteerID);
            $stmt->execute();
        
            // Set appropriate error message based on the condition
            if ($status === 'Pending') {
                $_SESSION['login_error'] = "Your registration is not approved yet.";
            } elseif ($managerID === null) {
                $_SESSION['login_error'] = "No manager assigned to you.";
            }
        
            // Redirect to login page
            header("Location: login.php");
            exit();
        }
         

        $_SESSION['loggedin'] = true;
        $_SESSION['VolunteerID'] = $volunteerID; // Use the correct session variable
        $_SESSION['Username'] = $storedUsername;

        // Log the successful login
        $stmt = $conn->prepare("INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt) VALUES (?, ?, 'LOGIN', NOW())");
        $stmt->bind_param("ii", $accountID, $volunteerID);
        $stmt->execute();

        header("Location: volunteer.php"); // Redirect to volunteer page
        exit();
    } else {
        // Log the failed login attempt
        $stmt = $conn->prepare("INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt) VALUES (?, ?, 'FAILED LOGIN', NOW())");
        $stmt->bind_param("ii", $accountID, $volunteerID);
        $stmt->execute();

        $_SESSION['login_error'] = "Invalid Username or Password.";
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
