<?php
session_start(); // Start the session at the top of the file
include 'database.php'; // Include database connection
$currentPage = 'maintenance'; // Set the current page for active tab highlighting

// Increase execution time limit
set_time_limit(300);

// Start output buffering if not already started
if (ob_get_level() === 0) {
    ob_start(); // Start output buffering
}

// Directory where backups are stored
$backupDir = "C:\\xampp\\htdocs\\MyPHPSite\\workshop 2\\backupfile\\";
$backupFiles = glob($backupDir . "*.sql"); // Get all .sql files in the backup directory

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Define your database credentials
    $dbUser  = 'shira';
    $dbName = 'fsvolunteer';

    // Backup functionality
    if (isset($_POST['backup'])) {
        $backupScript = '"C:\\xampp\\htdocs\\MyPHPSite\\workshop 2\\autobackup\\backup.bat"';
        
        // Execute the batch file
        $output = [];
        $returnVar = 0;
        exec($backupScript, $output, $returnVar); // Use exec to capture output
    
        if ($returnVar === 0) {
            $message = "<div class='alert alert-success'>Backup successful!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Backup failed! Error: " . implode("\n", $output) . "</div>";
        }
    }

    // Restore functionality
    if (isset($_POST['restore'])) {
        // Get the selected backup file from the dropdown
        $backupFile = $_POST['backupFile'];

        if (!empty($backupFile) && file_exists($backupFile)) {
            $command = "C:\\xampp\\mysql\\bin\\mysql -u $dbUser $dbName < \"$backupFile\"";

            // Execute the command
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar); // Use exec to capture output

            if ($returnVar === 0) {
                $message = "<div class='alert alert-success'>Restore successful!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Restore failed! Error: " . implode("\n", $output) . "</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Please select a valid backup file!</div>";
        }
    }
}

// At the end of your script or after generating output
if (ob_get_level() > 0) {
    ob_end_flush(); // Disable output buffering if it is active
}
flush(); // Flush the system output buffer
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
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
                <a href="account-access.php">Account Access</a>
                <a href="maintenance.php" class="active">Maintenance</a>
            <?php endif; ?>
            <a href="logout.php" class="btn1 btn-danger">Log Out</a>
        <?php endif; ?>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <section id="analysis" class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section">
                        <h2 class="mb-4">Maintenance</h2>
                        <p>Backup and Recovery.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Display the message here -->
                        <?php if (!empty($message)): ?>
                            <?php echo $message; ?>
                        <?php endif; ?>
                        <form method="POST">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Backup Database</td>
                                        <td></td>
                                        <td>
                                            <button type="submit" name="backup" class="btn btn-primary">Backup</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Restore Database</td>
                                        <td>
                                            <label for="backupFile" class="form-label">Select Backup File:</label>
                                            <select name="backupFile" id="backupFile" class="form-control">
                                                <option value="">Select a backup file</option>
                                                <?php foreach ($backupFiles as $file): ?>
                                                    <option value="<?php echo htmlspecialchars($file); ?>"><?php echo basename($file); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" name="restore" class="btn btn-warning">Restore</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
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