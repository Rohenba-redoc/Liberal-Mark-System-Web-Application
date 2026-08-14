<?php
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $given_by = $_POST['given_by'];

    // Prepare the response array
    $response = [];

    // Fetch distinct course names and codes
    $stmt = $conn->prepare("
        SELECT DISTINCT c.course_name, utm.course_code
        FROM unit_test_marks utm 
        JOIN course c ON utm.course_code = c.course_code 
        WHERE utm.given_by = ?");
    $stmt->bind_param("s", $given_by);
    if (!$stmt->execute()) {
        echo "Error executing course fetch: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $response['course_names'] = [];
    while ($row = $result->fetch_assoc()) {
        $response['course_names'][] = $row['course_name'];
        $response['course_codes'][] = $row['course_code'];
    }

    // Fetch distinct semester names and IDs
    $stmt = $conn->prepare("
        SELECT DISTINCT s.semester_name, utm.semester_id
        FROM unit_test_marks utm 
        JOIN semester s ON utm.semester_id = s.semester_id 
        WHERE utm.given_by = ?");
    $stmt->bind_param("s", $given_by);
    if (!$stmt->execute()) {
        echo "Error executing semester fetch: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $response['semester_names'] = [];
    while ($row = $result->fetch_assoc()) {
        $response['semester_names'][] = $row['semester_name'];
        $response['semesters'][] = $row['semester_id'];
    }

    // Fetch distinct subject names and codes
    $stmt = $conn->prepare("
        SELECT DISTINCT sub.subject_name, utm.subject_code 
        FROM unit_test_marks utm 
        JOIN subject sub ON utm.subject_code = sub.subject_code 
        WHERE utm.given_by = ?");
    $stmt->bind_param("s", $given_by);
    if (!$stmt->execute()) {
        echo "Error executing subject fetch: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $response['subjects'] = []; // Array for subjects
    while ($row = $result->fetch_assoc()) {
        $response['subjects'][] = [
            'code' => $row['subject_code'],  // Subject code
            'name' => $row['subject_name']    // Subject name
        ];
    }

   

    // Fetch distinct years from test_date
    $stmt = $conn->prepare("SELECT DISTINCT YEAR(test_date) AS year FROM unit_test_marks WHERE given_by = ?");
    $stmt->bind_param("s", $given_by);
    if (!$stmt->execute()) {
        echo "Error executing year fetch: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $response['years'] = [];
    while ($row = $result->fetch_assoc()) {
        $response['years'][] = $row['year'];
    }

    // Return the response as JSON
    echo json_encode($response);
}
?>
