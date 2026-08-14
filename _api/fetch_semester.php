<?php
include 'new.php';


// Adjust the SQL query to filter semesters based on the year
$sql = "SELECT * FROM semester ";

// Prepare and bind the query
$stmt = $conn->prepare($sql);

$stmt->execute();

// Fetch results
$result = $stmt->get_result();
$semester = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $semester[] = $row;
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($semester);
?>
