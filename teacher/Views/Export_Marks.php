<?php
include '../includes/header.php';
include '../../includes/config.php';

// Assuming $teacherId is retrieved from the session or elsewhere
$teacherId = $_SESSION['teacher']['teacher_id'] ?? null; // Replace with actual session value retrieval

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="header">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">Overview</h6>
                            <!-- Title -->
                            <h1 class="header-title">Results</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="#" id="export-pdf" class="btn btn-primary lift">Export in PDF</a>
                            <a href="#" id="export-excel" class="btn btn-secondary lift">Export in Excel</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Filters -->
            <div style="display:flex;justify-content:space-between">
                <div class="form-group col-3">
                    <label for="course_code">Course <span class="text-red">*</span></label>
                    <select id="course" name="course_code" class="form-control mb-3">
                        <option value="">Select Course</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="semester_id">Semester<span class="text-red">*</span></label>
                    <select id="semester" name="semester_id" class="form-control mb-3">
                        <option value="">Select Semester</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="subject_code">Paper<span class="text-red">*</span></label>
                    <select id="subject_code" name="subject_code" class="form-control mb-3">
                        <option value="">Select Paper</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between">
               
                
                <div class="form-group col-3">
                    <input type="hidden" value="<?php echo $teacherId; ?>" name="given_by" id="given_by">
                    <button id="filter-btn" class="btn btn-primary lift mb-5" style="display:none">Filter</button>
                </div>
            </div>

            <!-- Container to Display the Results -->
            <div id="results-container" class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Unique_ID</th>
                            <th>MU_Roll_No</th>
                            <th>Student Name</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody id="results-table-body">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>



<style>
    .table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
}

.table th {
    text-align: left;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Optionally include jsPDF AutoTable plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.27/jspdf.plugin.autotable.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js"></script>
<script>
   const givenBy = document.getElementById('given_by').value;
        document.addEventListener('DOMContentLoaded', function () {
            const givenBy = document.getElementById('given_by').value;
            if (givenBy) {
             fetchFilters(givenBy);
            } else {
             console.error("Given by value is empty.");
            }
        });

            // Fetch filters based on the given_by value
            function fetchFilters(givenBy) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/fetch_filters.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                                       const data = JSON.parse(xhr.responseText);
                        
                            // Populate dropdowns with fetched options
                            populateDropdown('course', data.course_codes, data.course_names);
                            populateDropdown('semester', data.semesters, data.semester_names);
                            populateSubjectDropdown('subject_code', data.subjects);
                       } catch (e) {
                            console.error("JSON parsing error:", e);
                        }
                    } else {
                        console.error("Request failed. Status:", xhr.status, "Response:", xhr.responseText);
                    }
                };

                // Send the given_by value to the server
                xhr.send(`given_by=${encodeURIComponent(givenBy)}`);
            }

            // Populate dropdown options for non-subject dropdowns
            function populateDropdown(id, values, names) {
                const select = document.getElementById(id);
                select.innerHTML = ''; // Clear existing options

                // Add a "Select" option at the top
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select';
                select.appendChild(defaultOption);

                // Check if values are present and populate the dropdown
                if (values && values.length > 0) {
                    values.forEach(function (value, index) {
                        const option = document.createElement('option');
                        option.value = value; // Use the corresponding code/ID as value
                        option.textContent = names ? names[index] : value; // Use names if provided
                        select.appendChild(option);
                    });
                } else {
                    console.error(`No values found for ${id}`);
                }
            }

            // Populate dropdown options specifically for subjects (code and name)
            function populateSubjectDropdown(id, subjects) {
                const select = document.getElementById(id);
                select.innerHTML = ''; // Clear existing options

                // Add a "Select" option at the top
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
               defaultOption.textContent = 'Select';
                select.appendChild(defaultOption);

                // Populate the dropdown with subject codes and names
                if (subjects && subjects.length > 0) {
                    subjects.forEach(function (subject) {
                        const option = document.createElement('option');
                        option.value = subject.code; // Use subject code as value
                        option.textContent = `${subject.code} (${subject.name})`; // Display code and name
                        select.appendChild(option);
                    });
                } else {
                    console.error("No subjects found.");
                }
            }

            document.getElementById('subject_code').addEventListener('change', function() {
                    const filterButton = document.getElementById('filter-btn');
    
                    // Check if a date is selected (not an empty value)
                    if (this.value) {
                       filterButton.style.display = 'block'; // Show the filter button
                    } else {
                        filterButton.style.display = 'none'; // Hide the filter button if no date is selected
                        }
            });
            document.getElementById('filter-btn').addEventListener('click', function() {
        const table = document.getElementById('table');
        table.style.display = 'block'; // Show the table
    });
    document.getElementById('filter-btn').addEventListener('click', function() {
            const courseCode = document.getElementById('course').value;
            const semesterId = document.getElementById('semester').value;
            const subjectCode = document.getElementById('subject_code').value;

            console.log(courseCode);
            console.log(semesterId);
            console.log(subjectCode);

            if (courseCode && semesterId && subjectCode) {
                fetch(`../controller/export_mark.php?course_code=${courseCode}&semester_id=${semesterId}&subject_code=${subjectCode}`)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('results-table-body');
                        tableBody.innerHTML = ''; // Clear previous results
                    if (data.length > 0) {
                        data.forEach(result => {
                            const row = document.createElement('tr');
                            const idcell = document.createElement('td');
                            const rollCell = document.createElement('td');
                            const nameCell = document.createElement('td');
                            const markCell = document.createElement('td');
                            
                            idcell.textContent = result.unique_id;
                            rollCell.textContent = result.mu_roll;
                            nameCell.textContent = result.name;
                            markCell.innerHTML = result.Mark_score_mean; // HTML allows for formatted content
                            
                            row.appendChild(idcell);
                            row.appendChild(rollCell);
                            row.appendChild(nameCell);
                            row.appendChild(markCell);
                            
                            tableBody.appendChild(row);
                        });
                    }
                    else{
                        // No students found
                const row = document.createElement('tr');
                const noDataCell = document.createElement('td');
                noDataCell.setAttribute('colspan', '4'); // Adjust colspan according to the number of columns in your table
                noDataCell.textContent = 'No students found';
                noDataCell.classList.add('text-center', 'text-muted'); // Optional: Add classes for styling

                row.appendChild(noDataCell);
                tableBody.appendChild(row);
                    }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                alert('Please select all required fields to filter.');
            }
        });
        document.getElementById('export-pdf').addEventListener('click', function() {
    // Check if jsPDF is loaded
    if (!window.jspdf) {
        alert('jsPDF library is not loaded.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Select table element
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');
    if (!table || !table.querySelector('tbody') || !table.querySelector('tbody').rows.length) {
       alert('Select the Result To Export It');
        return;
    }
    if (tbody.textContent.includes('No students found')) {
        alert('No students found to export');
        return;
    }

    // Get filter values
    const course = document.getElementById('course').selectedOptions[0].textContent;
    const paperCode = document.getElementById('subject_code').selectedOptions[0].textContent;
    const semester = document.getElementById('semester').selectedOptions[0].textContent;

    // Set up PDF margins and positioning
    const margins = { top: 20, left: 10 };
    let y = margins.top;

    // Add title
    doc.setFontSize(16);
    doc.text('Results', margins.left, y);
    y += 10;

    // Add filter details
    doc.setFontSize(12);
    doc.text(`Course: ${course}`, margins.left, y);
    y += 10;
    doc.text(`Paper Code: ${paperCode}`, margins.left, y);
    y += 10;
    doc.text(`Semester: ${semester}`, margins.left, y);
    y += 10;

    // Set font size for table
    doc.setFontSize(10);

    // Add table
    const headers = ['Unique_ID', 'MU_Roll_No', 'Student Name', 'Marks'];
    const rows = Array.from(table.querySelectorAll('tbody tr')).map(row =>
        Array.from(row.querySelectorAll('td')).map(cell => cell.textContent)
    );

    // Define column widths based on the longest header text
    const colWidths = [40, 40, 60, 40];

    doc.autoTable({
        startY: y,
        head: [headers],
        body: rows,
        theme: 'grid',
        styles: {
            fontSize: 10,
            cellPadding: 2,
            cellWidth: 'auto',
        },
        headStyles: {
            fillColor: [22, 160, 133],
            textColor: [255, 255, 255],
        },
        columnStyles: {
            0: { cellWidth: colWidths[0] },
            1: { cellWidth: colWidths[1] },
            2: { cellWidth: colWidths[2] },
            3: { cellWidth: colWidths[3] }
        },
        margin: { top: y }
    });

    y = doc.autoTable.previous.finalY;

    // Save the PDF

    doc.save('Results -' + course + '-' + paperCode + '-' + semester + '.pdf');
});




document.getElementById('export-excel').addEventListener('click', function() {
    const wb = XLSX.utils.book_new();

    // Get table element
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');

    if (!table || !table.querySelector('tbody') || !table.querySelector('tbody').rows.length) {
       alert('Select the Result To Export It');
        return;
    }
    if (tbody.textContent.includes('No students found')) {
        alert('No students found to export');
        return;
    }

    // Define header values
    const course = document.getElementById('course')?.selectedOptions[0].textContent || 'N/A';
    const paperCode = document.getElementById('subject_code')?.selectedOptions[0].textContent || 'N/A';
    const semester = document.getElementById('semester')?.selectedOptions[0].textContent || 'N/A';

    // Create header rows
    const headerRows = [
        [`Course: ${course}`],
        [`Paper Code: ${paperCode}`],
        [`Semester: ${semester}`],
        [] // Blank row to separate headers from table
    ];

    // Create a worksheet with header rows
    const wsHeaders = XLSX.utils.aoa_to_sheet(headerRows);

    // Get the cell range for the headers
    const headerRange = XLSX.utils.decode_range(wsHeaders['!ref']);
    for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
        const cellAddress = XLSX.utils.encode_cell({ c: C, r: 0 });
        wsHeaders[cellAddress].s = {
            font: { bold: true },
            fill: { fgColor: { rgb: '00C0FFEE' } } // Light blue background color
        };
    }

    // Create a worksheet from the table
    const wsTable = XLSX.utils.table_to_sheet(table);

    // Define column widths based on the longest header text
    const colWidths = [15, 15, 30, 20]; // Adjust these values as needed

    // Apply column widths
    wsTable['!cols'] = colWidths.map(width => ({ wpx: width }));

    // Create a new worksheet with both headers and table data
    const wsCombined = XLSX.utils.json_to_sheet([], { header: [] });
    XLSX.utils.sheet_add_aoa(wsCombined, headerRows);
    XLSX.utils.sheet_add_json(wsCombined, XLSX.utils.sheet_to_json(wsTable, { header: 1 }), { skipHeader: true, origin: -1 });

    // Append the combined worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, wsCombined, 'Results');

    // Write the Excel file
    XLSX.writeFile(wb, 'Results -'+course +'-'+ paperCode + '-'+ semester +'.xlsx');
});


</script>

<?php include '../includes/footer.php'; ?>
