<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $volunteerID = $_POST['volunteerID'];
    $action = $_POST['action'];

    if ($action === 'approve') {
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