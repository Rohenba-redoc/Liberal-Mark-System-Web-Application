<?php 
header('Content-Type: application/json');

include 'new.php'; // Ensure this file establishes a connection to your database

// Get the student_id from the query string
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Check if student_id is provided
if (empty($student_id)) {
    echo json_encode(['error' => 'Student ID is required']);
    exit;
}

// SQL query to fetch semester details for a specific student with incomplete status and unpaid fees
$sql = "SELECT s.semester_name, s.semester_id, se.fee_status, se.exam_fee
        FROM student_enroll se 
        JOIN semester s ON se.semester_id = s.semester_id
        WHERE se.student_id = '$student_id' 
              AND (se.fee_status = 'Not_Paid' OR se.exam_fee = 'Not_Paid')"; // Fetch records where either fee_status or exam_fee is 'Not_Paid'

// Execute the query
$result = $conn->query($sql);

$messages = []; // Array to store messages for the response
$seenMessages = []; // To track if a specific message has been added for each year and fee type

// Check if the query was successful and if there are any results
if ($result) {
    if ($result->num_rows > 0) {
        // Transform results into custom messages
        while ($row = $result->fetch_assoc()) {
            $semesterName = $row['semester_name'];
            $feeStatus = $row['fee_status']; // Get the fee status from the query result
            $examFeeStatus = $row['exam_fee']; // Get the exam fee status from the query result

            // Determine year based on semester name using regex
            if (preg_match('/1st\s*semester|2nd\s*semester/i', $semesterName)) {
                $year = '1st Year';
            } elseif (preg_match('/3rd\s*semester|4th\s*semester/i', $semesterName)) {
                $year = '2nd Year';
            } elseif (preg_match('/5th\s*semester|6th\s*semester/i', $semesterName)) {
                $year = '3rd Year';
            } elseif (preg_match('/7th\s*semester|8th\s*semester/i', $semesterName)) {
                $year = '4th Year';
            } elseif (preg_match('/9th\s*semester|10th\s*semester/i', $semesterName)) {
                $year = '5th Year';
            } else {
                $year = 'Unknown Year'; // Handle other cases if needed
            }

            // Create unique keys to track added messages for each year and fee type
            $admissionFeeKey = "AdmissionFee-$year";
            $examFeeKey = "ExamFee-$year";

            // Check fee statuses and add corresponding messages if not already added
            if ($feeStatus === 'Not_Paid' && !isset($seenMessages[$admissionFeeKey])) {
                $messages[] = "*** Admission Fee not paid for $year ***";
                $seenMessages[$admissionFeeKey] = true; // Mark the message as added
            }
            if ($examFeeStatus === 'Not_Paid' && !isset($seenMessages[$examFeeKey])) {
                $messages[] = "*** Exam Fee not paid for $year ***";
                $seenMessages[$examFeeKey] = true; // Mark the message as added
            }
        }
    } else {
        $messages[] = ' ';
    }
} else {
    $messages[] = 'Failed to execute query';
}

// Close the database connection
$conn->close();

// Encode the results as JSON and output it
echo json_encode($messages);
?>
