<?php
include 'database.php';

// Query the Department table
$query = "SELECT * FROM Department";
$result = pg_query($conn1, $query); 

if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>DeptID</th><th>DeptName</th><th>Location</th><th>OfficeNum</th><th>Email</th></tr>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['deptid'] . "</td>";
        echo "<td>" . $row['deptname'] . "</td>";
        echo "<td>" . $row['location'] . "</td>";
        echo "<td>" . $row['officenum'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error in query: " . pg_last_error();
} 



/* $sql = "SELECT * FROM Budget";
$result1 = $conn2->query($sql);

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "BudgetID: " . $row["BudgetID"] . " - Amount: " . $row["AmountAllocated"] . "<br>";
    }
} else {
    echo "No records found";
}
$conn2->close(); */

// Close connection
pg_close($conn1);
?>