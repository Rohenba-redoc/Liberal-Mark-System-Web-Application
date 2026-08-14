<?php
include 'new.php';


// Get the stream_id from the request
$add_by = $_GET['add_by'];

// Prepare the SQL query
$sql = "SELECT c.course_name,s.semester_name,ss.subject_name,t.*
         FROM teacher_notice t
         JOIN 
         course c ON t.course_code = c.course_code
         JOIN
         semester s ON t.semester_id = s.semester_id
         JOIN
         subject ss ON t.subject_code = ss.subject_code
          WHERE add_by = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $add_by);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$notices = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['message'] = strip_tags($row['message']);

        $notices[] = $row;
    }
}

// Close connections
$stmt->close();
$conn->close();

// Output as JSON
header('Content-Type: application/json');
echo json_encode($notices);
?>
