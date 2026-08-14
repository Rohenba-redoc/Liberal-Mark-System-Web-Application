<?php
include '../includes/config.php';

// Initialize variables
$combinationId = isset($_POST['combination_id']) ? intval($_POST['combination_id']) : 0;
$courseCode = isset($_POST['course_code']) ? $_POST['course_code'] : '';
$years = isset($_POST['year']) ? $_POST['year'] : '';
$subjectCodes = isset($_POST['subject_codes']) ? $_POST['subject_codes'] : [];

// Function to validate input
function validateInput($conn, $sql, $params, $types) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Validate inputs
if ($combinationId > 0 && !empty($courseCode) && !empty($subjectCodes)) {
    // Validate course_code
    $valid_course_sql = "SELECT course_code FROM course WHERE course_code = ?";
    $result = validateInput($conn, $valid_course_sql, [$courseCode], 's');
    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Invalid course code"]);
        exit();
    }

    // Validate combination_id
    $combination_check_sql = "SELECT combination_id FROM course_combination WHERE combination_id = ? AND course_code = ?";
    $result = validateInput($conn, $combination_check_sql, [$combinationId, $courseCode], 'is');
    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Combination ID or course code does not exist"]);
        exit();
    }

    // Validate subject_codes
    foreach ($subjectCodes as $subjectCode) {
        $valid_subject_sql = "SELECT subject_code FROM subject WHERE subject_code = ?";
        $result = validateInput($conn, $valid_subject_sql, [$subjectCode], 's');
        if ($result->num_rows == 0) {
            echo json_encode(["success" => false, "message" => "Invalid subject code: $subjectCode"]);
            exit();
        }
    }

    // Fetch existing subjects for the given combination_id
    $existing_subjects_sql = "SELECT subject_code FROM course_combination WHERE combination_id = ? AND course_code = ?";
    $result = validateInput($conn, $existing_subjects_sql, [$combinationId, $courseCode], 'is');
    $existing_subject_codes = [];

    while ($row = $result->fetch_assoc()) {
        $existing_subject_codes[] = $row['subject_code'];
    }

    // Convert arrays to sets for easier comparison
    $existing_subject_codes_set = array_flip($existing_subject_codes);
    $subjectCodes_set = array_flip($subjectCodes);

    // Identify subject codes to update
    $subjects_to_remove = array_diff($existing_subject_codes, $subjectCodes);
    $subjects_to_add = array_diff($subjectCodes, $existing_subject_codes);

    // Remove old subjects that are not in the new list
    if (!empty($subjects_to_remove)) {
        $delete_sql = "DELETE FROM course_combination WHERE combination_id = ? AND course_code = ? AND subject_code IN (" . implode(',', array_fill(0, count($subjects_to_remove), '?')) . ")";
        $stmt = $conn->prepare($delete_sql);
        $params = [$combinationId, $courseCode];
        foreach ($subjects_to_remove as $subjectCode) {
            $params[] = $subjectCode;
        }
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        if (!$stmt->execute()) {
            echo json_encode(["success" => false, "message" => "Failed to remove some subjects"]);
            exit();
        }
    }

    // Add new subjects to the combination
    if (!empty($subjects_to_add)) {
        $insert_sql = "INSERT INTO course_combination (combination_id, subject_code, course_code, year) VALUES " . implode(',', array_fill(0, count($subjects_to_add), '(?, ?, ?, ?)'));
        $stmt = $conn->prepare($insert_sql);
        $params = [];
        foreach ($subjects_to_add as $subjectCode) {
            $params[] = $combinationId;
            $params[] = $subjectCode;
            $params[] = $courseCode;
            $params[] =$years;
        }
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        if (!$stmt->execute()) {
            echo json_encode(["success" => false, "message" => "Failed to add some subjects"]);
            exit();
        }
    }

    echo json_encode(["success" => true, "message" => "Subjects updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid combination ID, course code, or subject codes"]);
}

$conn->close();
?>
