<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $volunteerID = $_POST['volunteerID'];
    $managerID = $_POST['managerID'];

    // Update the volunteer's ManagerID
    $updateQuery = "UPDATE volunteer SET ManagerID = ? WHERE VolunteerID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $managerID, $volunteerID);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = 'Manager assigned successfully.';
    header("Location: assignation.php");
    exit();
}