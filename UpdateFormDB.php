<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['VolunteerID'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$volunteerID = $_SESSION['VolunteerID']; // Get VolunteerID from session

// Fetch current volunteer details
$stmtVolunteerSelect = $conn->prepare("SELECT v.*, a.Password 
                                        FROM Volunteer v 
                                        JOIN Account a ON v.VolunteerID = a.VolunteerID 
                                        WHERE v.VolunteerID = ?");
$stmtVolunteerSelect->bind_param("i", $volunteerID);
$stmtVolunteerSelect->execute();
$resultVolunteer = $stmtVolunteerSelect->get_result();
$currentData = $resultVolunteer->fetch_assoc();
$stmtVolunteerSelect->close();

// SQL Trigger for update and delete
/*$triggerVolunteerAccountAction = "
-- Drop existing triggers if they exist
DROP TRIGGER IF EXISTS BeforeVolunteerUpdate;
DROP TRIGGER IF EXISTS BeforeVolunteerDelete;
DROP TRIGGER IF EXISTS BeforeAccountUpdate;
DROP TRIGGER IF EXISTS BeforeAccountDelete;

-- Create trigger for updates on Volunteer table
CREATE TRIGGER BeforeVolunteerUpdate
BEFORE UPDATE ON Volunteer
FOR EACH ROW
BEGIN
    INSERT INTO volunteerlog (VolunteerID, Name, ContactNo, YearOfStudy, RoleID, ActionType)
    VALUES (OLD.VolunteerID, OLD.Name, OLD.ContactNo, OLD.YearOfStudy, OLD.RoleID, 'UPDATE');
END;

-- Create trigger for updates on Account table
CREATE TRIGGER BeforeAccountUpdate
BEFORE UPDATE ON Account
FOR EACH ROW
BEGIN
    INSERT INTO accountlog (AccountID, VolunteerID, ActionType)
    VALUES (OLD.AccountID, OLD.VolunteerID, 'UPDATE');
END;    ";

// Execute the SQL to create triggers
if ($conn->multi_query($triggerVolunteerAccountAction)) {
    do {
        // Clear results from the queries
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
} else {
    echo "Error creating triggers: " . $conn->error;
} */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateProfile'])) {
        // Retrieve form data from the user
        $name = $_POST['Name'];
        $contactNo1 = $_POST['ContactNo1'];
        $contactNo2 = $_POST['ContactNo2'];
        $contactNo = $contactNo1 . '-' . $contactNo2;
        $yearOfStudy = $_POST['YearOfStudy'];
        $password = $_POST['Password'];
        $confirmPassword = $_POST['ConfirmPassword'];

        // Validate password strength and confirmation
        if (!empty($password)) {
            // Validate password strength
            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
                $_SESSION['EmailMessage'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
                header("Location: UpdateForm.php");
                exit;
            } elseif ($password !== $confirmPassword) {
                $_SESSION['EmailMessage'] = "Passwords do not match.";
                header("Location: UpdateForm.php");
                exit();
            } elseif (password_verify($password, $currentData['Password'])) {
                // Check if the new password is the same as the old password
                $_SESSION['EmailMessage'] = "New password cannot be the same as the old password.";
                header("Location: UpdateForm.php");
                exit();
            }
        }

        // Update Volunteer details
        $stmtVolunteerUpdate = $conn->prepare("UPDATE Volunteer 
                                                SET Name = ?, ContactNo = ?, YearOfStudy = ? 
                                                WHERE VolunteerID = ?");
        $stmtVolunteerUpdate->bind_param("ssii", $name, $contactNo, $yearOfStudy, $volunteerID);
        $stmtVolunteerUpdate->execute();
        $stmtVolunteerUpdate->close();

        // Update Password if provided
        if (!empty($password)) {
            // Hash the password before updating
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update the Account table with the hashed password
            $stmtPasswordUpdate = $conn->prepare("UPDATE Account SET Password = ? WHERE VolunteerID = ?");
            $stmtPasswordUpdate->bind_param("si", $hashedPassword, $volunteerID);
            $stmtPasswordUpdate->execute();
            $stmtPasswordUpdate->close();
        }

        $_SESSION['status'] = "Profile updated successfully!";
        header("Location: UpdateForm.php");
        exit();
    } /*elseif (isset($_POST['deleteProfile'])) {
        // Delete Volunteer and Account records
        try {
            $conn->begin_transaction();

            // Delete from Account table
            $stmtAccountDelete = $conn->prepare("DELETE FROM Account WHERE VolunteerID = ?");
            $stmtAccountDelete->bind_param("i", $volunteerID);
            $stmtAccountDelete->execute();
            $stmtAccountDelete->close();

            // Delete from Volunteer table
            $stmtVolunteerDelete = $conn->prepare("DELETE FROM Volunteer WHERE VolunteerID = ?");
            $stmtVolunteerDelete->bind_param("i", $volunteerID);
            $stmtVolunteerDelete->execute();
            $stmtVolunteerDelete->close();

            $conn->commit();

            // End session and redirect to login page
            session_destroy();
            header("Location: volunteer.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['EmailMessage'] = "Error deleting profile: " . $e->getMessage();
            header("Location: UpdateForm.php");
            exit();
        }
    } */
}

$conn->close();
