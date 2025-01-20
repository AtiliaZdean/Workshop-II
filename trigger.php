<?php
include 'database.php';

// Drop existing triggers if they exist
$conn->query("DROP TRIGGER IF EXISTS BeforeVolunteerUpdate");
$conn->query("DROP TRIGGER IF EXISTS BeforeAccountUpdate");
$conn->query("DROP TRIGGER IF EXISTS BeforeMatricNoInsert");

// Create trigger for updates on Volunteer table
$createVolunteerTrigger = "
CREATE TRIGGER BeforeVolunteerUpdate
BEFORE UPDATE ON Volunteer
FOR EACH ROW
BEGIN
    -- Check if the Status column is being updated
    IF OLD.Status = NEW.Status THEN
        -- Log the update for other columns
        INSERT INTO volunteerlog (VolunteerID, Name, ContactNo, YearOfStudy, RoleID, ActionType, DoneAt)
        VALUES (OLD.VolunteerID, OLD.Name, OLD.ContactNo, OLD.YearOfStudy, OLD.RoleID, 'UPDATE', NOW());
    END IF;
END;
";

if ($conn->query($createVolunteerTrigger) === FALSE) {
    echo "Error creating BeforeVolunteerUpdate trigger: " . $conn->error;
} else {
    echo "BeforeVolunteerUpdate trigger created successfully.\n";
}

// Create trigger for updates on Account table
$createAccountTrigger = "
CREATE TRIGGER BeforeAccountUpdate
BEFORE UPDATE ON Account
FOR EACH ROW
BEGIN
    INSERT INTO accountlog (AccountID, VolunteerID, ActionType, DoneAt)
    VALUES (OLD.AccountID, OLD.VolunteerID, 'UPDATE', NOW());
END;
";

if ($conn->query($createAccountTrigger) === FALSE) {
    echo "Error creating BeforeAccountUpdate trigger: " . $conn->error;
} else {
    echo "BeforeAccountUpdate trigger created successfully.\n";
}

// Create the trigger to ensure unique Matric Number
$triggerMatricNoSQL = "
CREATE TRIGGER BeforeMatricNoInsert 
BEFORE INSERT ON volunteer
FOR EACH ROW
BEGIN
    DECLARE managerName VARCHAR(255);
    DECLARE contactNo VARCHAR(255);
    DECLARE errorMessage VARCHAR(512);  -- Declare a variable for the error message
    
    -- Check if the MatricNo already exists and Status is not NULL
    IF EXISTS (SELECT 1 
               FROM volunteer 
               WHERE MatricNo = NEW.MatricNo AND Status IN ('Requesting Resign','Resiqn Approved','Resign Denied') THEN
        -- Retrieve manager's name and contact number using the ManagerID
        SELECT v2.Name, v2.ContactNo 
        INTO managerName, contactNo
        FROM volunteer v1
        JOIN volunteer v2 ON v1.ManagerID = v2.VolunteerID
        WHERE v1.MatricNo = NEW.MatricNo;

        -- Construct the error message
        SET errorMessage = CONCAT('You already resigned. Do consult ', managerName, ' via ', contactNo, ' for any further assistance.');

        -- Raise an error with the constructed message
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = errorMessage;
    END IF;

    -- Check if the MatricNo already exists (without checking Status)
    IF EXISTS (SELECT 1 FROM volunteer WHERE MatricNo = NEW.MatricNo) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Matric Number already exists.';
    END IF;
END;
";

// Execute the trigger for unique Matric Number
if ($conn->query($triggerMatricNoSQL) === FALSE) {
    // Handle error if trigger creation fails
    $_SESSION['EmailMessage'] = 'Error creating BeforeMatricNoInsert trigger: ' . $conn->error;
    header("Location: RegisterForm.php");
    exit;
} else {
    echo "BeforeMatricNoInsert trigger created successfully.\n";
}

?>