<?php
include 'database.php'; // Include your database connection

$dataPoints = array();

if (isset($_POST['selectedYear'])) {
    $selectedYear = intval($_POST['selectedYear']);
    $reportType = $_POST['reportType'];

    try {
        if ($reportType == 'programme') {
            // Fetch data for Volunteers Count by Programme for the selected year
            $handle = $conn->prepare('CALL GetVolunteersCountByProgrammeByYear(?)');
        } else {
            // Fetch data for Volunteers Count by Year of Study for the selected year
            $handle = $conn->prepare('CALL GetVolunteersCountByYearOfStudyByYear(?)');
        }
        $handle->bind_param('i', $selectedYear);
        $handle->execute();
        $result = $handle->get_result();

        while ($row = $result->fetch_assoc()) {
            if ($reportType == 'programme') {
                $dataPoints[] = array("label" => $row['Programme'], "count" => $row['Volunteer_Count']);
            } else {
                $dataPoints[] = array("label" => $row['YearOfStudy'], "count" => $row['Volunteer_Count']);
            }
        }

        // Free the result set
        $result->free();
        $handle->close(); // Close the prepared statement

    } catch (Exception $ex) {
        echo "Error: " . $ex->getMessage();
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const dataPoints = <?php echo json_encode($dataPoints); ?>;

    const labels = dataPoints.map(data => data.label);
    const counts = dataPoints.map(data => data.count);

    const chartCanvas = new Chart(document.getElementById('chartCanvas').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Volunteer Count',
                data: counts,
                backgroundColor: 'rgba(0, 123, 255, 0.6)', // Darker color
                borderColor: 'rgba(0, 123, 255, 1)',
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
