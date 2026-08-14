<?php
include 'new.php'; 

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['student_id']) && isset($data['expo_push_token'])) {
    $studentID = $data['student_id'];
    $expoPushToken = $data['expo_push_token'];

    $checkQuery = "SELECT student_id FROM students WHERE student_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $studentID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE students SET expo_push_token = ? WHERE student_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $expoPushToken, $studentID);
        if ($updateStmt->execute()) {
            echo json_encode(["message" => "Push token updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update push token"]);
        }
    } else {
        echo json_encode(["message" => "Student ID not found"]);
    }

    $checkStmt->close();
    $updateStmt->close();
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
