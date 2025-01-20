<?php
include 'database.php'; // Include your database connection

$dataPointsProgramme = array();
$dataPointsYear = array();

if (isset($_POST['selectedYear'])) {
    $selectedYear = intval($_POST['selectedYear']);

    try {
        // Fetch data for Volunteers Count by Programme for the selected year
        $handleProgramme = $conn->prepare('CALL GetVolunteersCountByProgrammeByYear(?)');
        $handleProgramme->bind_param('i', $selectedYear);
        $handleProgramme->execute();
        $resultProgramme = $handleProgramme->get_result();

        while ($row = $resultProgramme->fetch_assoc()) {
            array_push($dataPointsProgramme, array("programme" => $row['Programme'], "count" => $row['Volunteer_Count']));
        }

        // Free the result set
        $resultProgramme->free();
        $handleProgramme->close(); // Close the prepared statement

        // Fetch data for Volunteers Count by Year of Study for the selected year
        $handleYear = $conn->prepare('CALL GetVolunteersCountByYearOfStudyByYear(?)');
        $handleYear->bind_param('i', $selectedYear);
        $handleYear->execute();
        $resultYear = $handleYear->get_result();

        while ($row = $resultYear->fetch_assoc()) {
            array_push($dataPointsYear, array("year" => $row['YearOfStudy'], "count" => $row['Volunteer_Count']));
        }

        // Free the result set
        $resultYear->free();
        $handleYear->close(); // Close the prepared statement

    } catch (Exception $ex) {
        echo "Error: " . $ex->getMessage();
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const programmeData = <?php echo json_encode($dataPointsProgramme); ?>;
    const yearData = <?php echo json_encode($dataPointsYear); ?>;

    const programmeLabels = programmeData.map(data => data.programme);
    const programmeCounts = programmeData.map(data => data.count);

    const yearLabels = yearData.map(data => data.year);
    const yearCounts = yearData.map(data => data.count);

    const programmeChart = new Chart(document.getElementById('programmeChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: programmeLabels,
            datasets: [{
                label: 'Volunteers Count by Programme for Year ' + <?= json_encode($selectedYear); ?>,
                data: programmeCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const yearChart = new Chart(document.getElementById('yearChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: yearLabels,
            datasets: [{
                label: 'Volunteers Count by Year of Study for Year ' + <?= json_encode($selectedYear); ?>,
                data: yearCounts,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>