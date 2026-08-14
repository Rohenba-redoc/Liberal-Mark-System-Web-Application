<?php
include '../includes/config.php';

function fetchSubjects() {
    global $conn;
    
    $sql = "SELECT s.subject_code, s.subject_name, s.semester_id, s.department_id, d.department_name, sem.semester_name
        FROM subject s
        LEFT JOIN department d ON s.department_id = d.department_id
        JOIN semester sem ON s.semester_id = sem.semester_id
        ORDER BY sem.semester_name, d.department_name;
        ";
    $result = $conn->query($sql);

    $subjects = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    }
    
    $conn->close();
    return $subjects;
}

function fetchStreams() {
    global $conn;
    
    $sql = "SELECT stream_id, stream_title FROM streams";
    $result = $conn->query($sql);

    $streams = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $streams[] = $row;
        }
    }
    
    $conn->close();
    return $streams;
}
function fetchDepartments() {
    global $conn;
    
    $sql = "SELECT department_id, department_name FROM department";
    $result = $conn->query($sql);

    $departments = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }
    
    $conn->close();
    return $departments;
}


function fetchSemesters() {
    global $conn;
    
    $sql = "SELECT * FROM semester";
    $result = $conn->query($sql);

    $semesters = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $semesters[] = $row;
        }
    }
    
    $conn->close();
    return $semesters;
}

function fetchCourses() {
    global $conn;
    
    $sql = "SELECT course.*, streams.stream_title 
            FROM course 
            JOIN streams ON course.stream_id = streams.stream_id";
    
    $result = $conn->query($sql);

    $courses = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    
    $conn->close();
    return $courses;
}
function fetchStudents($status = null) {
    global $conn;
    $query = "SELECT * FROM students";
    if ($status) {
        $query .= " WHERE status = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $status);
    } else {
        $stmt = $conn->prepare($query);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
function fetchStudentsByStatus($status) {
    global $conn;

    // Prepare the SQL query
    $sql = "SELECT * FROM students WHERE status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $status);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all results
    $students = $result->fetch_all(MYSQLI_ASSOC);

    // Close the connection
    $stmt->close();
    $conn->close();

    return $students;
}
function fetchTeacher() {
    global $conn;
    
    $sql = "SELECT t.teacher_id,t.teacher_name,t.teacher_phone,t.teacher_email,t.teacher_address,t.desgination,t.dob,td.department_id,d.department_name
     FROM teacher t
     JOIN teacher_department td ON t.teacher_id = td.teacher_id
     JOIN department d ON td.department_id = d.department_id";
    $result = $conn->query($sql);

    $teachers = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
    }
    
    $conn->close();
    return $teachers;
}
function fetchAdmin() {
    global $conn;
    
    $sql = "SELECT * FROM admin_credentials";
    $result = $conn->query($sql);

    $admins = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
    }
    
    $conn->close();
    return $admins;
}
function fetchNotice() {
    global $conn;
    
    $sql = "SELECT id,title,message,created_at FROM admin_notice WHERE type='all' ";
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
function fetchPass() {
    global $conn;
    
    $sql = "SELECT * FROM passkeys";
    $result = $conn->query($sql);

    $passkeys = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $passkeys[] = $row;
        }
    }
    
    $conn->close();
    return $passkeys;
}

?>
