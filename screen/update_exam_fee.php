<?php
include '../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_exam_fee') {
    $success = false;
    $enroll_id = isset($_GET['enroll_id']) ? (int)$_GET['enroll_id'] : 0;

    if ($enroll_id <= 0) {
        echo json_encode(['error' => 'Invalid enrollment ID.']);
        exit;
    }

    try {
        // Update fee status to 'Paid'
        $query = "UPDATE student_enroll SET exam_fee = 'Paid' WHERE enroll_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $enroll_id);

        if ($stmt->execute()) {
            $success = true;
        } else {
            echo json_encode(['error' => 'Error updating fee status: ' . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }

    echo json_encode(['success' => $success]);
    exit;
}

// If the request is not POST or action is not update_fee_status, redirect or show an error
header('Location: enrolled_student.php');
exit;
?>
