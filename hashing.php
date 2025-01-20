<?php
include 'database.php'; // Database connection

// Retrieve all accounts
$sql = "SELECT AccountID, Password FROM Account";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $accountID = $row['AccountID'];
        $plainPassword = $row['Password']; // Assuming this is the original password

        // Hash the password
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Update the database with the new hashed password
        $updateSql = "UPDATE Account SET Password = ? WHERE AccountID = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $hashedPassword, $accountID);
        $stmt->execute();
    }
    echo "All passwords have been rehashed and updated successfully.";
} else {
    echo "No accounts found.";
}

$conn->close();
?>