<?php
include '../includes/config.php'; // Ensure you have your DB connection

$query = "SELECT stream_id, stream_title FROM streams";
$result = mysqli_query($conn, $query);
$streams = [];

while ($row = mysqli_fetch_assoc($result)) {
    $streams[] = $row;
}

echo json_encode($streams);
?>
