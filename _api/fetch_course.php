<?php
include 'new.php';


// Get the stream_id from the request
$stream_id = $_GET['stream_id'];

// Prepare the SQL query
$sql = "SELECT * FROM course WHERE stream_id = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $stream_id);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$courses = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Close connections
$stmt->close();
$conn->close();

// Output as JSON
header('Content-Type: application/json');
echo json_encode($courses);
?>
