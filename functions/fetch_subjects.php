<?php
include '../includes/config.php';

// Get the combination ID
$combination_id = $_GET['combination_id'];

// Fetch all subjects
$subjects_sql = "SELECT subject_code, subject_name FROM subject";
$subjects_result = $conn->query($subjects_sql);

$subjects = [];
if ($subjects_result->num_rows > 0) {
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[$row['subject_code']] = $row['subject_name'];
    }
}

// Fetch combination subjects
$combination_subjects_sql = "
SELECT subject_code
FROM course_combination
WHERE combination_id = ?";
$stmt = $conn->prepare($combination_subjects_sql);
$stmt->bind_param("i", $combination_id);
$stmt->execute();
$combination_subjects_result = $stmt->get_result();

$selected_subjects = [];
if ($combination_subjects_result->num_rows > 0) {
    while ($row = $combination_subjects_result->fetch_assoc()) {
        $selected_subjects[] = $row['subject_code'];
    }
}

$stmt->close();
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode([
    'subjects' => $subjects,
    'selected_subjects' => $selected_subjects
]);
?>
