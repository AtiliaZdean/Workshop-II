<?php
session_start();
include('database.php');

if (isset($_POST['submit'])) {
    // Retrieve form data
    $matricNo = mysqli_real_escape_string($conn, $_POST['MatricNo']);
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $contactNo1 = mysqli_real_escape_string($conn, $_POST['ContactNo1']);
    $contactNo2 = mysqli_real_escape_string($conn, $_POST['ContactNo2']);
    $programmeID = mysqli_real_escape_string($conn, $_POST['ProgrammeID']);
    $yearOfStudy = mysqli_real_escape_string($conn, $_POST['YearOfStudy']);
    $roleID = mysqli_real_escape_string($conn, $_POST['RoleID']);
    $joinDate = date('Y-m-d'); // Automatically set current date
    $password = trim($_POST['Password']);
    $confirmPassword = trim($_POST['ConfirmPassword']);

    $contactNo = $contactNo1 . '-' . $contactNo2;

    // Input validation
    if ($password !== $confirmPassword) {
        $_SESSION['EmailMessage'] = 'Passwords do not match.';
        header("Location: RegisterForm.php");
        exit;
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
        $_SESSION['EmailMessage'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
        header("Location: RegisterForm.php");
        exit;
    }

    try {
        // Prepare Volunteer table insert with ManagerID
        $stmtVolunteer = $conn->prepare(
            "INSERT INTO Volunteer (MatricNo, Name, ContactNo, ProgrammeID, YearOfStudy, JoinDate, RoleID, Status, ManagerID)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NULL)"
        );
        $stmtVolunteer->bind_param("sssiisi", $matricNo, $name, $contactNo, $programmeID, $yearOfStudy, $joinDate, $roleID);

        if ($stmtVolunteer->execute()) {
            // Retrieve auto-generated VolunteerID
            $volunteerID = $conn->insert_id;

            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare Account table insert with hashed password
            $stmtAccount = $conn->prepare(
                "INSERT INTO Account (Username, Password, VolunteerID) VALUES (?, ?, ?)"
            );
            $stmtAccount->bind_param("ssi", $matricNo, $hashedPassword, $volunteerID);

            if ($stmtAccount->execute()) {
                $_SESSION['status'] = 'Your registration is successful. Please wait for approval.';
                header("Location: RegisterForm.php");
                exit;
            } else {
                $_SESSION['EmailMessage'] = 'Failed to create account.';
                header("Location: RegisterForm.php");
                exit;
            }
            $stmtAccount->close();
        } else {
            // Handle the case where the trigger raises an error
            if ($conn->errno == 45000) {
                $_SESSION['EmailMessage'] = 'Matric Number already exists.';
            } else {
                $_SESSION['EmailMessage'] = ' Failed to register volunteer.';
            }
            header("Location: RegisterForm.php");
            exit;
        }
        $stmtVolunteer->close();
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = ' Error: ' . $e->getMessage();
        header("Location: RegisterForm.php");
        exit;
    }
}
$conn->close();
