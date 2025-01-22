<?php
session_start(); // Start the session at the top of the file
include 'database.php'; // Include database connection

// Initialize selected action type
$selectedActionType = 'FAILED LOGIN'; // Default action type
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actionType'])) {
    $selectedActionType = $_POST['actionType'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Access</title>
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
            /*font-size: 15px;*/
        }

        .btn:hover {
            background-color: #fff;
            color: #000;
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
            <?php if (isset($_SESSION['RoleID']) && $_SESSION['RoleID'] == 8): ?>
                <a href="newvolunteer.php">Registration Approval</a>
                <a href="assignation.php">Manager Assignment</a>
                <a href="account-access.php" class="active">Account Access</a>
                <a href="maintenance.php">Maintenance</a>
            <?php endif; ?>
            <a href="logout.php" class="btn1 btn-danger">Log Out</a>
        <?php endif; ?>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <section id="account-access" class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section">
                        <h2 class="mb-4">Account Access</h2>
                        <p>Select the type of access log to view.</p>
                    </div>
                </div>

                <!-- Action Type Selection Form -->
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" action="">
                            <select name="actionType" required style="width: 200px; height: 45px;">
                                <option value="FAILED LOGIN" <?= $selectedActionType == 'FAILED LOGIN' ? 'selected' : ''; ?>>Failed Login</option>
                                <option value="LOGIN" <?= $selectedActionType == 'LOGIN' ? 'selected' : ''; ?>>Login</option>
                            </select>
                            <button type="submit" class="btn">View Logs</button>
                        </form>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12">
                    <h3><?= $selectedActionType; ?> attempts log</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No.</th>
                                    <th style="text-align: center;">Matric No.</th>
                                    <th style="text-align: center;">Name</th>
                                    <th style="text-align: center;">Year Of Study</th>
                                    <th style="text-align: center;">Contact No.</th>
                                    <th style="text-align: center;">Role</th>
                                    <th style="text-align: center;">Manager</th>
                                    <th style="text-align: center;">Attempt At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT v.MatricNo, v.Name, v.YearOfStudy, v.ContactNo, r.RoleName, m.Name AS 'Manager', al.DoneAT
                                          FROM volunteer v
                                          LEFT JOIN role r ON v.RoleID = r.RoleID
                                          LEFT JOIN programme p ON v.ProgrammeID = p.ProgrammeID
                                          LEFT JOIN volunteer m ON v.ManagerID = m.VolunteerID
                                          JOIN accountlog al ON v.VolunteerID = al.VolunteerID
                                          WHERE al.ActionType = ?";

                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("s", $selectedActionType);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $i = 1;
                                while ($row = $result->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i++; ?></td>
                                        <td><?= $row['MatricNo']; ?></td>
                                        <td><?= $row['Name']; ?></td>
                                        <td style="text-align: center;"><?= $row['YearOfStudy']; ?></td>
                                        <td><?= $row['ContactNo']; ?></td>
                                        <td><?= $row['RoleName']; ?></td>
                                        <td><?= $row['Manager']; ?></td>
                                        <td><?= $row['DoneAT']; ?></td>
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
</body>

</html>
