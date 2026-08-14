<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enroll_id = $_POST['enroll_id'];
    $completion_date = $_POST['completion_date'];
    if ($enroll_id == null) {
        echo json_encode(['error' => 'Invalid enrollment ID.']);
        exit;
    }

    try {
        $sql = "UPDATE student_enroll SET status = 'Complete', completed_date = ? WHERE enroll_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $completion_date, $enroll_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    $conn->close();
}
?>
