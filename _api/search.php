<?php
header('Content-Type: application/json');

include 'new.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

    if (empty($query) || empty($student_id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input parameters'));
        exit;
    }

    $sql = "SELECT u.mark_id, u.test_name,u.subject_code, u.test_date, u.result_date, u.full_mark, u.pass_mark, u.mark_score, u.given_by, s.semester_name, sc.subject_name 
            FROM unit_test_marks u 
            JOIN semester s ON u.semester_id = s.semester_id
            JOIN subject sc ON u.subject_code = sc.subject_code
            WHERE u.student_id = ? AND (s.semester_name LIKE ? OR sc.subject_name LIKE ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database prepare error: ' . $conn->error));
        exit;
    }

    $searchTerm = "%{$query}%";
    if (!$stmt->bind_param("sss", $student_id, $searchTerm, $searchTerm)) {
        http_response_code(500);
        echo json_encode(array('error' => 'Binding parameters failed: ' . $stmt->error));
        exit;
    }

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(array('error' => 'Execute failed: ' . $stmt->error));
        exit;
    }

    $result = $stmt->get_result();
    $results = array();

    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();

    if (!empty($results)) {
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'No results found'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}
?>
