<?php
include 'new.php';
?>
<?php
$sql = "SELECT * FROM streams";

$result = $conn->query($sql);

// Fetch results
$streams = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $streams[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($streams);
?>
