<?php
session_start();
include 'database.php';

if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
    $volunteerID = $_POST['volunteerID'];
    $action = $_POST['action'];
    $managerID = $_SESSION['VolunteerID'];

    if ($action === 'approve') {
        // Update the volunteer's status to 'Resign Approved'
        $updateQuery = "UPDATE volunteer SET Status = 'Resign Approved' WHERE VolunteerID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $volunteerID);
        $stmt->execute();
        $stmt->close();

        // Log the approval in volunteerlog
        $logQuery = "UPDATE volunteerlog SET ManagerID = ?, ApprovedAt = NOW() WHERE VolunteerID = ? AND ActionType = 'RESIGN'";
        $managerID = $_SESSION['VolunteerID']; // Assuming the manager's ID is stored in session
        $stmtLog = $conn->prepare($logQuery);
        $stmtLog->bind_param("ss", $managerID, $volunteerID);
        $stmtLog->execute();
        $stmtLog->close();

        $_SESSION['message'] = 'Resignation approved.';
        header("Location: resignrequest.php");
        exit();
    } elseif ($action === 'deny') {
        // Update the volunteer's status to 'Resign Denied'
        $updateQuery = "UPDATE volunteer SET Status = 'Resign Denied' WHERE VolunteerID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $volunteerID);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = 'Resignation denied.';
        header("Location: resignrequest.php");
        exit();
    } elseif ($action === 'activate') {
        // Update the volunteer's status to 'Active' back
        $updateQuery = "UPDATE volunteer SET Status = 'Active' WHERE VolunteerID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $volunteerID);
        $stmt->execute();
        $stmt->close();

        // Log the withdrawn in volunteerlog
        $logQuery = "UPDATE volunteerlog SET ManagerID = ?, ApprovedAt = NOW() WHERE VolunteerID = ? AND ActionType = 'RESIGNATION WITHDRAWN'";
        $managerID = $_SESSION['VolunteerID']; // Assuming the manager's ID is stored in session
        $stmtLog = $conn->prepare($logQuery);
        $stmtLog->bind_param("ss", $managerID, $volunteerID);
        $stmtLog->execute();
        $stmtLog->close();

        $_SESSION['message'] = 'Account Reactivation is successfull';
        header("Location: resignrequest.php");
        exit();
    }
}
?>