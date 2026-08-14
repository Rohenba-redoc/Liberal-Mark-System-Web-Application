<?php
include '../../includes/config.php';

function fetchNotice() {
    global $conn;
    
    $sql = "SELECT  a.id,
                    a.title,
                    a.message,
                    a.created_at,
                    se.semester_name,
                    co.course_name,
                    GROUP_CONCAT(CONCAT(s.subject_name, '-', s.subject_code) SEPARATOR ', ') AS subject_info
                    FROM teacher_notice AS a
                    JOIN subject AS s ON a.subject_code = s.subject_code
                    JOIN semester AS se ON a.semester_id = se.semester_id
                    JOIN course AS co ON a.course_code = co.course_code
                    -- WHERE a.type = 'filter'
                    GROUP BY a.id, a.title, a.message, a.created_at, se.semester_name, co.course_name
            ORDER BY a.created_at DESC";
            
    $result = $conn->query($sql);

    $notices = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $notices[] = $row;
        }
    }
    
    $conn->close();
    return $notices;
}











?>