<?php 
include '../../includes/config.php';
$given_by = isset($_GET['given_by']) ? $_GET['given_by'] : '';
if (empty($given_by)) {
    echo json_encode(['error' => 'No Test Available']);
    exit;
}
if($given_by)
{
    $sql = "SELECT DISTINCT test_name FROM unit_test_marks where given_by=$given_by";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$test = [];

// Fetch the results
while ($row = $result->fetch_assoc()) {
    $test[] = $row;
}

// Output the results as JSON
echo json_encode($test);
}
?>
