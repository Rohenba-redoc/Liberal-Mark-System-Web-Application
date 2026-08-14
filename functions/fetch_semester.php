<?php 

include '../includes/config.php';

try {
    // Prepare SQL query to get all unique years from the course_combination table
    $sql_years = "SELECT DISTINCT year FROM course_combination";

    // Prepare and execute the statement
    $stmt_years = $conn->prepare($sql_years);
    $stmt_years->execute();
    $result_years = $stmt_years->get_result();

    // Fetch and display the years
   
    while ($row = $result_years->fetch_assoc()) {
        echo $row['year']  ;
    }
    echo "</select>";

    // Close the statement
    $stmt_years->close();

} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
