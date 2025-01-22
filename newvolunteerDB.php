<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $volunteerID = $_POST['volunteerID'];
    $action = $_POST['action'];

    if ($action === 'assign') {
        $managerID = $_POST['managerID'];

        // Update the volunteer's ManagerID
        $updateQuery = "UPDATE volunteer SET ManagerID = ? WHERE VolunteerID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $managerID, $volunteerID);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = 'Manager assigned successfully.';
        header("Location: newvolunteer.php");
        exit();
    }

    if ($action === 'approve') {
        // Check if the volunteer has a ManagerID assigned
        $checkQuery = "SELECT ManagerID FROM volunteer WHERE VolunteerID = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $volunteerID);
        $stmt->execute();
        $stmt->bind_result($managerID);
        $stmt->fetch();
        $stmt->close();

        if (empty($managerID)) {
            $_SESSION['message'] = 'Please assign a manager first.';
            header("Location: newvolunteer.php");
            exit();
        }

        // Update the volunteer's status to 'Active'
        $updateQuery = "UPDATE volunteer SET Status = 'Active' WHERE VolunteerID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $volunteerID);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = 'Volunteer approved successfully.';
        header("Location: newvolunteer.php");
        exit();
    }
}
?>
