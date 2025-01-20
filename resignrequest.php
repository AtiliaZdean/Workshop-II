<?php
session_start(); // Start the session at the top of the file
include 'database.php'; // Include database connection

$currentPage = 'resign_request'; // Set the current page for active tab highlighting

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

// Query for pending resignation requests
$queryPending = "SELECT v.VolunteerID, v.MatricNo, v.Name, vl.Note, r.RoleName
                 FROM volunteer v 
                 JOIN volunteerlog vl ON v.VolunteerID = vl.VolunteerID 
                 JOIN role r ON v.RoleID = r.RoleID
                 WHERE v.Status = 'Requesting Resign' AND v.ManagerID = ? AND vl.ActionType = 'RESIGN'";
$stmtPending = $conn->prepare($queryPending);
$stmtPending->bind_param("i", $_SESSION['VolunteerID']); // Assuming the manager's ID is stored in session
$stmtPending->execute();
$resultPending = $stmtPending->get_result();

// Query for approved and denied resignation requests
$queryApprovedDenied = "SELECT v.VolunteerID, v.MatricNo, v.Name, vl.Note, v.Status 
                        FROM volunteer v 
                        JOIN volunteerlog vl ON v.VolunteerID = vl.VolunteerID 
                        WHERE v.ManagerID = ? AND (v.Status = 'Resign Approved' OR v.Status = 'Resign Denied') AND vl.ActionType = 'RESIGN'";
$stmtApprovedDenied = $conn->prepare($queryApprovedDenied);
$stmtApprovedDenied->bind_param("i", $_SESSION['VolunteerID']);
$stmtApprovedDenied->execute();
$resultApprovedDenied = $stmtApprovedDenied->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resign Requests</title>
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
            margin: 0;
            /* Remove default margin from body */
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
            margin-top: 60px;
            padding: 15px;
            overflow-y: auto;
            /* Enable scrolling for content */
            min-height: calc(100vh - 60px);
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

        .search-container input[type="text"] {
            width: 100%;
            /* Make the input box take full width */
            max-width: 700px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            color: #000;
        }

        th {
            background-color: #252525;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ddd;
        }

        .btn {
            display: inline-block;
            background-color: #252525;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn1 {
            display: inline-block;
            background-color: #fab702;
            /* Yellow background for hover and active states */
            color: #000;
            text-align: center;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            border: none;
        }

        .btn-approve,
        .btn-deny,
        .btn-activate {
            display: inline-block;
            background-color: #252525;
            /* Black background */
            color: #fff;
            /* White text */
            text-align: center;
            padding: 10px 15px;
            /* Adjust padding */
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            /* Remove border */
            cursor: pointer;
            /* Change cursor to pointer */
            width: 100%;
            /* Make buttons fit the cell */
            box-sizing: border-box;
            /* Include padding in width */
        }

        .btn-approve:hover,
        .btn-deny:hover,
        .btn-activate:hover {
            background-color: #343a40;
            /* Darker shade on hover */
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
                    <li class="nav-item active"><a href="volunteer.php" class="nav-link">Volunteer</a></li>
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
        <a href="volunteer.php" class="nav-link">Profile</a>
        <a href="programme-search.php">Programme Search</a>
        <?php if (isset($_SESSION['VolunteerID'])): ?>
            <?php if (isset($_SESSION['RoleID']) && $_SESSION['RoleID'] == 5): ?>
                <a href="manager_monitor.php">Manager Monitor</a>
                <a href="resignrequest.php" class="active">Resign Requests</a>
                <a href="analysis.php">Volunteer Analysis</a>
            <?php endif; ?>
            <a href="logout.php" class="btn1 btn-danger">Log Out</a>
        <?php endif; ?>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <section id="programme-search" class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section">
                        <h2 class="mb-4">Pending Resignation Requests</h2>
                        <p>Requests of Volunteer Resignation</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Matric No.</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1; // Initialize counter
                                while ($row = $resultPending->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($row['MatricNo']); ?></td>
                                        <td><?= htmlspecialchars($row['Name']); ?></td>
                                        <td><?= htmlspecialchars($row['Note']); ?></td>
                                        <td><?= htmlspecialchars($row['RoleName']); ?></td>
                                        <td>
                                            <div style="display: flex; gap: 10px;"> <!-- Use flexbox for alignment -->
                                                <form method="POST" action="resignrequestDB.php" class="action-form">
                                                    <input type="hidden" name="volunteerID" value="<?= htmlspecialchars($row['VolunteerID']); ?>">
                                                    <input type="hidden" name="action" value="approve"> <!-- Hidden input for action -->
                                                    <button type="button" class="btn-approve" onclick="confirmAction('approve', this.form)">Approve</button>
                                                </form>
                                                <form method="POST" action="resignrequestDB.php" class="action-form">
                                                    <input type="hidden" name="volunteerID" value="<?= htmlspecialchars($row['VolunteerID']); ?>">
                                                    <input type="hidden" name="action" value="deny"> <!-- Hidden input for action -->
                                                    <button type="button" class="btn-deny" onclick="confirmAction('deny', this.form)">Deny</button>
                                                </form>
                                                <form method="POST" action="resignrequestDB.php" class="action-form">
                                                    <input type="hidden" name="volunteerID" value="<?= htmlspecialchars($row['VolunteerID']); ?>">
                                                    <input type="hidden" name="action" value="activate"> <!-- Hidden input for action -->
                                                    <button type="button" class="btn-activate" onclick="confirmAction('activate', this.form)">Activate</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12">
                        <h3>Approved and Denied Requests</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Matric No.</th>
                                    <th>Name</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 1; // Initialize counter for approved/denied requests
                                while ($row = $resultApprovedDenied->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $j++; ?></td>
                                        <td><?= htmlspecialchars($row['MatricNo']); ?></td>
                                        <td><?= htmlspecialchars($row['Name']); ?></td>
                                        <td><?= htmlspecialchars($row['Note']); ?></td>
                                        <td><?= htmlspecialchars($row['Status']); ?></td>
                                        <td>
                                            <form method="POST" action="resignrequestDB.php">
                                                <input type="hidden" name="volunteerID" value="<?= htmlspecialchars($row['VolunteerID']); ?>">
                                                <input type="hidden" name="action" value="approve"> <!-- Hidden input for action -->
                                                <?php if ($row['Status'] == 'Resign Denied'): ?>
                                                    <button type="submit" class="btn-approve">Approve</button>
                                                <?php endif; ?>
                                            </form>
                                            <form method="POST" action="resignrequestDB.php">
                                                <input type="hidden" name="volunteerID" value="<?= htmlspecialchars($row['VolunteerID']); ?>">
                                                <input type="hidden" name="action" value="activate"> <!-- Hidden input for action -->
                                                <button type="submit" class="btn-activate">Activate</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="ftco-footer ftco-section">
        <div class="container">
            <!-- Footer content here -->
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/main.js"></script>

    <script>
        function confirmAction(action, form) {
            const message = action === 'approve' ? 'Are you sure you want to approve this resignation request?' : 'Are you sure you want to deny this resignation request?';
            if (confirm(message)) {
                form.submit(); // Submit the form if confirmed
            }
        }
    </script>
</body>

</html>