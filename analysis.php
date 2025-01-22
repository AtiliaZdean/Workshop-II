<?php
session_start(); // Start the session
include 'database.php'; // Include database connection
$currentPage = 'analysis'; // Set the current page for active tab highlighting
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Analysis</title>
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
            color: #fff;
        }

        .sidebar a.active {
            background-color: #fab702;
            color: #000;
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

        .chart-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
            border: 1px solid #ddd;
            /* Add border to the chart container */
            padding: 15px;
            /* Add padding for spacing */
            border-radius: 5px;
            /* Rounded corners */
            background-color: #f9f9f9;
            /* Light background for the box */
        }

        .chart-container canvas {
            max-width: 400px;
            /* Set a max width for the charts */
            height: auto;
            /* Maintain aspect ratio */
            margin-left: 40px;
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

        .print-button {
            margin-top: 20px;
            /* Adjust margin for the print button */
        }

        .btn:hover,
        .print-button:hover {
            background-color: #fff;
            color: #000;
        }

        @media print {
            .print-hidden {
                display: none;
                /* Hide this class in print view */
            }

            body * {
                visibility: hidden;
                /* Hide everything */
            }

            .content,
            .content * {
                visibility: visible;
                /* Show only content section */
            }

            .content {
                position: absolute;
                /* Position content for printing */
                left: 0;
                top: 0;
                text-align: center;
                margin: 0 auto;
            }

            .btn,
            .print-button {
                display: none;
                /* Hide sidebar, navbar, and print button */
            }
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
        <a href="volunteer.php">Profile</a>
        <a href="programme-search.php">Programme Search</a>
        <?php if (isset($_SESSION['VolunteerID'])): ?>
            <?php if (isset($_SESSION['RoleID']) && $_SESSION['RoleID'] == 5): ?>
                <a href="manager_monitor.php">Manager Monitor</a>
                <a href="resignrequest.php">Resign Requests</a>
                <a href="analysis.php" class="active">Volunteer Analysis</a>
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
                        <h2 class="mb-4">Volunteer Analysis</h2>
                        <p>View reports and statistical analyses of registered volunteers.</p>
                    </div>
                </div>

                <!-- Year and Report Type Selection Form -->
                <div class="row print-hidden">
                    <div class="col-md-12">
                        <h3>Select Year and Report Type for Analysis</h3>
                        <form method="POST" action="">
                            <select name="selectedYear" required style="width: 125px; height: 45px;">
                                <option value="">Select Year</option>
                                <?php
                                $currentYear = date("Y");
                                for ($year = 2023; $year <= $currentYear; $year++) {
                                    echo "<option value='$year'>$year</option>";
                                }
                                ?>
                            </select>
                            <select name="reportType" required style="width: 325px; height: 45px;">
                                <option value="">Select Report Type</option>
                                <option value="programme">Volunteers Count by Programme</option>
                                <option value="yearOfStudy">Volunteers Count by Year of Study</option>
                            </select>
                            <button type="submit" class="btn">Generate Report</button>
                        </form>
                    </div>
                </div>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selectedYear']) && isset($_POST['reportType'])) {
                    $selectedYear = intval($_POST['selectedYear']);
                    $reportType = $_POST['reportType'];
                ?>

                    <!-- Reporting Section -->
                    <div class="row mt-5">
                        <div class="col-md-12 printable-content">
                            <h3><?= htmlspecialchars($reportType == 'programme' ? 'Volunteers Count by Programme' : 'Volunteers Count by Year of Study'); ?> for Year: <?= htmlspecialchars($selectedYear); ?></h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">No.</th>
                                        <th style="text-align: center;"><?= $reportType == 'programme' ? 'Programme' : 'Year of Study'; ?></th>
                                        <th style="text-align: center;">Volunteer Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rowNumber = 1;
                                    if ($reportType == 'programme') {
                                        $query = "CALL GetVolunteersCountByProgrammeByYear($selectedYear)";
                                    } else {
                                        $query = "CALL GetVolunteersCountByYearOfStudyByYear($selectedYear)";
                                    }
                                    $result = $conn->query($query);

                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) :
                                    ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $rowNumber++; ?></td>
                                                <td style="text-align: center;"><?= htmlspecialchars($reportType == 'programme' ? $row['Programme'] : $row['YearOfStudy']); ?></td>
                                                <td style="text-align: center;"><?= htmlspecialchars($row['Volunteer_Count']); ?></td>
                                            </tr>
                                    <?php
                                        endwhile;
                                        $result->free();
                                    } else {
                                        echo "<tr><td colspan='2'>Error executing procedure: " . htmlspecialchars($conn->error) . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="row mt-5">
                        <div class="col-md-12 printable-content">
                            <canvas id="chartCanvas" width="100" height="50"></canvas>
                        </div>
                    </div>

                    <?php include 'charts.php'; ?>
                <?php } // End of POST check
                ?>

                <div class="row print-button">
                    <div class="col-md-12 text-center">
                        <button onclick="window.print();" class="btn">Print Report</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
