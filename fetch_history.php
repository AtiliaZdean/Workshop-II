<?php
session_start();
include 'database.php'; // Include your database connection for both PostgreSQL and MariaDB

if (isset($_GET['volunteerID'])) {
    $volunteerID = $_GET['volunteerID'];
    $managerID = $_SESSION['VolunteerID']; // Get the current manager's ID

    // Step 1: Check if the volunteer is managed by the current manager in MariaDB
    $volunteerQuery = "SELECT VolunteerID FROM volunteer WHERE VolunteerID = ? AND ManagerID = ?";
    $stmt = $conn->prepare($volunteerQuery);
    
    if ($stmt) {
        $stmt->bind_param("ii", $volunteerID, $managerID);
        $stmt->execute();
        $volunteerResult = $stmt->get_result();

        if ($volunteerResult->num_rows > 0) {
            // Step 2: If the volunteer is managed by the current manager, fetch the sponsor data from PostgreSQL
            $sponsorQuery = "SELECT sponsorid, name, sponsortype 
                             FROM sponsor 
                             WHERE verificationstatus = 'verified' 
                             AND volunteerID = $1";

            // Prepare the statement for PostgreSQL
            $pgResult = pg_prepare($conn1, "fetch_sponsor_history", $sponsorQuery);
            
            // Execute the prepared statement
            $pgResult = pg_execute($conn1, "fetch_sponsor_history", array($volunteerID));

            // Check if the query was successful
            if ($pgResult) {
                // Check if any rows were returned
                if (pg_num_rows($pgResult) > 0) {
                    // Fetch and output the data
                    $j = 1;
                    while ($row = pg_fetch_assoc($pgResult)) {
                        echo "<tr>
                                <td style='text-align: center;'>{$j}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['sponsortype']}</td>
                              </tr>";
                        $j++;
                    }
                } else {
                    // No rows found in the sponsor table
                    echo "<tr><td colspan='3' class='text-center'>No history found for this sponsorship manager.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>Error executing PostgreSQL query: " . pg_last_error($conn1) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='text-center'>No history found for this sponsorship manager.</td></tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>Error preparing statement: " . $conn->error . "</td></tr>";
    }
}
?>