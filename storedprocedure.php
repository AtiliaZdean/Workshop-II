<?php
// Include database connection
include 'database.php';

// Check if the procedure exists before dropping it
$checkProcedure1 = "SHOW PROCEDURE STATUS LIKE 'GetVolunteersCountByProgrammeByYear';";
$result1 = $conn->query($checkProcedure1);

if ($result1->num_rows > 0) {
    // Procedure exists, drop it
    $dropProcedure1 = "DROP PROCEDURE GetVolunteersCountByProgrammeByYear;";
    if ($conn->query($dropProcedure1) === TRUE) {
        echo "Procedure GetVolunteersCountByProgrammeByYear dropped successfully.<br>";
    } else {
        echo "Error dropping procedure: " . $conn->error . "<br>";
    }
}

// Check if the second procedure exists before dropping it
$checkProcedure2 = "SHOW PROCEDURE STATUS LIKE 'GetVolunteersCountByYearOfStudyByYear';";
$result2 = $conn->query($checkProcedure2);

if ($result2->num_rows > 0) {
    // Procedure exists, drop it
    $dropProcedure2 = "DROP PROCEDURE GetVolunteersCountByYearOfStudyByYear;";
    if ($conn->query($dropProcedure2) === TRUE) {
        echo "Procedure GetVolunteersCountByYearOfStudyByYear dropped successfully.<br>";
    } else {
        echo "Error dropping procedure: " . $conn->error . "<br>";
    }
}

// Create the stored procedure to get the count of volunteers by programme for a specific year
$procedure1 = "
CREATE PROCEDURE GetVolunteersCountByProgrammeByYear(IN selectedYear INT)
BEGIN
    SELECT p.ProgrammeName AS Programme, p.Description AS Description, 
           COUNT(v.MatricNo) AS Volunteer_Count 
    FROM programme p 
    LEFT JOIN volunteer v ON v.ProgrammeID = p.ProgrammeID AND YEAR(v.JoinDate) = selectedYear
    GROUP BY p.ProgrammeName, p.Description;
END
";

// Create the stored procedure to get the count of volunteers by year of study for a specific year
$procedure2 = "
CREATE PROCEDURE GetVolunteersCountByYearOfStudyByYear(IN selectedYear INT)
BEGIN
    SELECT y.YearOfStudy, COUNT(v.VolunteerID) AS Volunteer_Count
    FROM (SELECT 1 AS YearOfStudy UNION SELECT 2 UNION SELECT 3) AS y
    LEFT JOIN volunteer v ON v.YearOfStudy = y.YearOfStudy AND YEAR(v.JoinDate) = selectedYear
    GROUP BY y.YearOfStudy;
END
";

// Execute the procedures
if ($conn->query($procedure1) === TRUE) {
    echo "Procedure GetVolunteersCountByProgrammeByYear created successfully.<br>";
} else {
    echo "Error creating procedure: " . $conn->error . "<br>";
}

if ($conn->query($procedure2) === TRUE) {
    echo "Procedure GetVolunteersCountByYearOfStudyByYear created successfully.<br>";
} else {
    echo "Error creating procedure: " . $conn->error . "<br>";
}
?>