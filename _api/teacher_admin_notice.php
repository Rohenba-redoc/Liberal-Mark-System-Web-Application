<?php
header('Content-Type: application/json');

// Include the database connection file
include 'new.php';

// Initialize an array to store the matched notices
$matchedNotices = [];

// Prepare the SQL query
$sql2 = "SELECT id, title, message, created_at 
         FROM admin_notice 
         WHERE type='all'";

// Execute the query
$result2 = $conn->query($sql2);

// Check if the query returned any results
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        // Strip HTML tags from the message field
        $row['message'] = strip_tags($row['message']);
        // Add the row to the matched notices array
        $matchedNotices[] = $row;
    }
} else {
    // If no results, you can also return a message or an empty array
    $matchedNotices = [];
}

// Close the database connection
$conn->close();

// Create a response array that includes the matched notices
$response = [
    'notices' => $matchedNotices
];

// Encode the results as JSON and output it
echo json_encode($response);
?>
