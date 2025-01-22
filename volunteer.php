<?php
// In your login script
session_start(); // Start session at the beginning of the file
include 'database.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request_resign') {
    $reason = $_POST['resignationReason'];
    $volunteerID = $_SESSION['VolunteerID'];

    // Update the volunteer's status to 'Requesting Resign'
    $updateQuery = "UPDATE volunteer SET Status = 'Requesting Resign' WHERE VolunteerID = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $volunteerID);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $stmt->close();

    // Log the resignation request in volunteerlog
    $logQuery = "INSERT INTO volunteerlog (VolunteerID, Name, ContactNo, YearOfStudy, RoleID, ActionType, Note, DoneAt) 
                  SELECT VolunteerID, Name, ContactNo, YearOfStudy, RoleID, 'RESIGN', ?, NOW() 
                  FROM volunteer WHERE VolunteerID = ?";
    $stmtLog = $conn->prepare($logQuery);
    if (!$stmtLog) {
        die("Error preparing log statement: " . $conn->error);
    }
    $stmtLog->bind_param("ss", $reason, $volunteerID);
    if (!$stmtLog->execute()) {
        die("Error executing log statement: " . $stmtLog->error);
    }
    $stmtLog->close();

    // Log out the user
    session_destroy();
    // Show an alert message and redirect to volunteer.php
    echo "<script>alert('You have requested to resign. Please wait for approval.'); window.location.href='volunteer.php';</script>";
    exit();
}

if (isset($_SESSION['VolunteerID'])) {
    $volunteerID = $_SESSION['VolunteerID'];

    // Query to get the user's role
    $roleQuery = "SELECT RoleID FROM volunteer WHERE VolunteerID = ?";
    $stmtRole = $conn->prepare($roleQuery);
    $stmtRole->bind_param("s", $volunteerID);
    $stmtRole->execute();
    $roleResult = $stmtRole->get_result();
    $userRole = $roleResult->fetch_assoc();
    $stmtRole->close();

    // Store RoleID in session
    if ($userRole) {
        $_SESSION['RoleID'] = $userRole['RoleID'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer</title>
    <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body,
        html {
            font-family: 'Overpass', sans-serif;
            /* Change to Arial for most elements */
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: calc(100vh - 60px);
            /* Adjust height to fill the viewport minus the navbar height */
            width: 250px;
            position: fixed;
            top: 60px;
            /* Set top to the height of the navbar */
            left: 0;
            background: #252525;
            color: #fff;
            padding: 15px;
            overflow-y: auto;
            /* Enable scroll inside sidebar if content exceeds viewport height */
            z-index: 100;
            /* Ensure sidebar stays behind the header but on top of other content */
        }

        .sidebar-brand {
            font-family: Arial, sans-serif;
            /* Keep the font for FoodShare */
        }

        .sidebar h3 {
            color: #fff;
            font-size: 21px;
            font-weight: bold;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background-color: #343a40;
            /* Yellow background for hover and active states */
            color: #fff;
            /* Optional: black text for better contrast */
        }

        .sidebar a.active {
            background-color: #fab702;
            /* Yellow background for hover and active states */
            color: #000;
            /* Optional: black text for better contrast */
        }

        .content {
            margin-left: 250px;
            /* Keep the left margin for the sidebar */
            margin-top: 60px;
            /* Adjust this value based on the height of your navbar */
            padding: 15px;
            overflow-y: auto;
            /* Enable scrolling for content */
            min-height: calc(100vh - 60px);
            /* Adjust this value based on the height of your navbar */
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 200;
            /* Ensure navbar stays above the sidebar */
            background-color: #252525 !important;
            color: white;
            margin-bottom: 0;
        }

        .navbar-nav .nav-item .nav-link {
            color: #ddd;
            padding: 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: #fff;
        }

        .navbar-nav .nav-item.active .nav-link {
            color: #fab702;
            /* Yellow color for hover and active links */
        }

        .navbar-nav .nav-item.active .nav-link:hover {
            color: #fff;
            /* Same as hover over inactive */
        }

        .profile-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-form input[type="text"] {
            width: 100%;
            max-width: 700px;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            background-color: #fab702;
            color: #000;
            text-align: center;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #252525;
            color: #fff
        }

        .btn1 {
            display: inline-block;
            background-color: #fab702;
            /* Yellow background for hover and active states */
            color: #000;
            text-align: center;
            padding: 3px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            font-size: 15px;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red background for the logout button */
            color: white;
            /* Text color */
        }
    </style>
</head>

<body>

    <!-- Top navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">FoodShare</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="dashboard_final.php" class="nav-link">Home</a></li>
                    <li class="nav-item active"><a href="volunteer.php#profile" class="nav-link">Volunteer</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>
            <?php if (isset($_SESSION['Username'])): ?>
                Welcome, <?= htmlspecialchars($_SESSION['Username']); ?>!
            <?php else: ?>
                Welcome!
            <?php endif; ?>
        </h3>
        <a href="#profile" class="active">Profile</a>
        <a href="programme-search.php">Programme Search</a>
        <?php if (isset($_SESSION['VolunteerID'])): ?>
            <?php if (isset($_SESSION['RoleID']) && $_SESSION['RoleID'] == 5): ?>
                <a href="manager_monitor.php">Manager Monitor</a>
                <a href="resignrequest.php">Resign Requests</a>
                <a href="analysis.php">Volunteer Analysis</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['RoleID']) && $_SESSION['RoleID'] == 8): ?>
                <a href="newvolunteer.php">Registration Approval</a>
                <a href="account-access.php">Account Access</a>
                <a href="maintenance.php">Maintenance</a>
            <?php endif; ?>
            <a href="logout.php" class="btn1 btn-danger">Log Out</a>
        <?php endif; ?>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <section id="profile" class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section">
                        <h2 class="mb-4">Volunteer Profile</h2>
                        <p>Your details as a volunteer at FoodShare.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!isset($_SESSION['VolunteerID'])) {
                            echo "<p>Please log in to view your profile. <a href='login.php'>Log in</a> Or register now. <a href='RegisterForm.php'>Register</a></p>";
                            exit();
                        }

                        $volunteerID = $_SESSION['VolunteerID'];
                        $query = "SELECT 
                                    v.MatricNo AS 'Matric Number',
                                    v.Name AS 'Full Name',
                                    v.ContactNo AS 'Contact Number',
                                    p.Description AS 'Programme',
                                    v.YearOfStudy AS 'Year Of Study',
                                    r.RoleName AS 'Role',
                                    r.Description AS 'Task',
                                    m.Name AS 'Manager Name',
                                    v.JoinDate AS 'Date Joined'
                                  FROM volunteer v
                                  JOIN programme p ON v.ProgrammeID = p.ProgrammeID
                                  JOIN role r ON v.RoleID = r.RoleID
                                  LEFT JOIN volunteer m ON v.ManagerID = m.VolunteerID
                                  WHERE v.VolunteerID = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $volunteerID);
                        $stmt->execute();
                        $user = $stmt->get_result()->fetch_assoc();

                        if ($user): ?>
                            <div class="profile-form">
                                <label>Matric Number</label>
                                <input type="text" value="<?= htmlspecialchars($user['Matric Number']); ?>" readonly>

                                <label>Full Name</label>
                                <input type="text" value="<?= htmlspecialchars($user['Full Name']); ?>" readonly>

                                <label>Contact Number</label>
                                <input type="text" value="<?= htmlspecialchars($user['Contact Number']); ?>" readonly>

                                <label>Programme</label>
                                <input type="text" value="<?= htmlspecialchars($user['Programme']); ?>" readonly>

                                <label>Year Of Study</label>
                                <input type="text" value="<?= htmlspecialchars($user['Year Of Study']); ?>" readonly>

                                <label>Role</label>
                                <input type="text" value="<?= htmlspecialchars($user['Role']); ?>" readonly>

                                <label>Task</label>
                                <p><?= htmlspecialchars($user['Task']); ?></p>

                                <label>Manager</label>
                                <p><?= htmlspecialchars($user['Manager Name']); ?></p>

                                <label>Date Joined</label>
                                <p><?= htmlspecialchars($user['Date Joined']); ?></p>

                                <a href="UpdateForm.php" class="btn">Update Profile</a>

                                <!-- Hidden text area and submit button -->
                                <?php if (isset($_SESSION['RoleID']) && in_array($_SESSION['RoleID'], [1, 2, 4, 6, 7])): ?>
                                    <a href="#" class="btn btn-danger" id="requestResignBtn">Request Resign</a>
                                    <div id="resignationForm" style="display: none; margin-top: 20px;">
                                        <label for="resignationReason">Reason for resignation:</label>
                                        <textarea id="resignationReason" name="resignationReason" rows="4" maxlength="199" style="width: 100%;"></textarea>
                                        <input type="hidden" name="action" value="request_resign">
                                        <button class="btn btn-danger" id="submitResignationBtn">Submit</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <script>
                                document.getElementById('requestResignBtn').addEventListener('click', function(event) {
                                    event.preventDefault(); // Prevent the default anchor behavior
                                    document.getElementById('resignationForm').style.display = 'block'; // Show the resignation form
                                });

                                document.getElementById('submitResignationBtn').addEventListener('click', function() {
                                    const reason = document.getElementById('resignationReason').value;
                                    if (reason.trim() === '') {
                                        alert('Please provide a reason for your resignation.');
                                        return;
                                    }

                                    const confirmation = confirm('Are you sure you want to submit your resignation?');
                                    if (confirmation) {
                                        // Create a form programmatically to submit the data
                                        const form = document.createElement('form');
                                        form.method = 'POST';
                                        form.action = ''; // Submit to the same page

                                        // Create a hidden input for the resignation reason
                                        const inputReason = document.createElement('input');
                                        inputReason.type = 'hidden';
                                        inputReason.name = 'resignationReason';
                                        inputReason.value = reason;
                                        form.appendChild(inputReason);

                                        // Create a hidden input for the action
                                        const inputAction = document.createElement('input');
                                        inputAction.type = 'hidden';
                                        inputAction.name = 'action';
                                        inputAction.value = 'request_resign';
                                        form.appendChild(inputAction);

                                        // Append the form to the body and submit it
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                });
                            </script>
                        <?php else: ?>
                            <p>No profile data found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer Section -->
    <footer class="ftco-footer ftco-section img">
        <div class="container">
            <!-- Footer content here -->
        </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
