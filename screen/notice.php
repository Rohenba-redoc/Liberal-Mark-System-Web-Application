


<?php 

include '../functions/display.php';

include '../includes/header.php';
$notices = fetchNotice();




?>
<?php 

include '../includes/config.php';

// Initialize count variable
$count = 0;

try {
    // Prepare the SQL statement
    $sql = "SELECT COUNT(*) AS count FROM admin_notice WHERE type = 'filter'";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
    } else {
        throw new Exception('Query failed or no data found');
    }

    // Close the database connection
    $result->free();
    $conn->close();

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
 <?php 
                      function limitText($text, $length = 50) {
                        return mb_strimwidth($text, 0, $length, '...');
                        }?>
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Replace with your actual token
    const expectedToken = 'YOUR_EXPECTED_TOKEN_HERE';

    // Get the token from localStorage
    const authToken = localStorage.getItem('authToken');

    // Check if the token matches
    if (authToken !== expectedToken) {
        // Redirect to the sign-in page if tokens do not match
        window.location.href = 'sign-in.php';
    }
});
</script> -->

<div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-12">

            <!-- Header -->
            <div class="header">
              <div class="header-body">
                <div class="row align-items-center">
                  <div class="col">

                    <!-- Pretitle -->
                    <h6 class="header-pretitle">
                      Overview
                    </h6>

                    <!-- Title -->
                    <h1 class="header-title">
                      Notice
                    </h1>

                  </div>
                  <div class="col-auto">

                    <!-- Button -->
                    <a href="add_notice.php" class="btn btn-primary lift">
                      New Notice
                    </a>

                  </div>
                </div> <!-- / .row -->
                <div class="row align-items-center">
                  <div class="col">

                    <!-- Nav -->
                    <ul class="nav nav-tabs nav-overflow header-tabs">
                        <li class="nav-item">
                            <a href="#!" class="nav-link active" id="tab-all">
                              All <span class="badge rounded-pill text-bg-secondary-subtle"><?php echo count($notices); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#!" class="nav-link" id="tab-filter">
                              Filter <span class="badge rounded-pill text-bg-secondary-subtle">

                              <?php echo htmlspecialchars($count); ?>
                              </span>
                            </a>
                        </li>
                    </ul>

                  </div>
                </div>
              </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["notice-title", "notice-message", "notice-date", "notice-course", "notice-subject", "notice-semester"]}'>
              <div class="card-header">

                <!-- Search -->
                <form>
                  <div class="input-group input-group-flush input-group-merge input-group-reverse">
                    <input class="form-control list-search" type="search" placeholder="Search">
                    <span class="input-group-text">
                      <i class="fe fe-search"></i>
                    </span>
                  </div>
                </form>

               

              </div>
              <div class="table-responsive" >
                <table class="table table-sm table-nowrap card-table tab-content" id="content-all"  >
                  <thead>
                    <tr>
                      
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-title">
                          Title
                        </a>
                      </th>
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-message">
                          Description
                        </a>
                      </th>
                      <th colspan="2">
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-date">
                          Annouced_Date
                        </a>
                      </th>
                      
                    </tr>
                  </thead>
                  <tbody class="list">
                  <?php if (!empty($notices)): ?>
                    <?php foreach ($notices as $notice): ?>
                    <tr>
                      
                      <td class="notice-title">
                      <?php echo htmlspecialchars($notice['title']); ?>
                      </td>
                      <td class="notice-message">
                     
                      <?php echo limitText($notice['message'], 50); ?>                      </td>
                      <td class="notice-date">

                        <!-- Time -->
                        <?php
                          $date = new DateTime($notice['created_at']);
                          echo htmlspecialchars($date->format('d-m-Y'));
                          ?>


                      </td>
                     
                      <td class="text-end">

                        <!-- Dropdown -->
                        <div class="dropdown" style="position:absolute">
                          <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe fe-more-vertical"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal"
                             data-notice-id="<?php echo htmlspecialchars($notice['id']); ?>" 
                            data-notice-title="<?php echo htmlspecialchars($notice['title']);?>"
                            data-notice-message="<?php echo htmlspecialchars($notice['message']);?>"
                            data-notice-created_at="<?php echo htmlspecialchars($notice['created_at']);?>"
                            
                            >
                                                Edit
                            </a>
                            <a href="#" class="dropdown-item" onclick="confirmDelete('<?php echo htmlspecialchars($notice['id']); ?>')">
                                                Delete
                            </a>
                           
                          </div>
                        </div>

                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                        <tr>
                            <td colspan="10">No Notices found</td>
                         </tr>
                  <?php endif; ?>
                    
                   
                  </tbody>
                </table>
                <table class="table table-sm table-nowrap card-table tab-content d-none"  id="content-filter" >
                  <thead>
                    <tr>
                      
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-title">
                          Title
                        </a>
                      </th>
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-message">
                          Description
                        </a>
                      </th>
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-course">
                          Discipline
                        </a>
                      </th>
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-subject">
                          Subject
                        </a>
                      </th>
                      <th colspan="2">
                        <a href="#" class="text-body-secondary list-sort" data-sort="notice-date">
                          Annouced_Date
                        </a>
                      </th>
                      
                    </tr>
                  </thead>
                  
                      <tbody class="list">
                          <?php 
                              include '../includes/config.php';

                                  // Query to fetch filtered notices with combined subject codes and names
                                  $sql = "SELECT a.id,
                                                 a.title,
                                                 a.message,
                                                 a.created_at,
                                                 se.semester_name,
                                                 co.course_name,
                                                 d.department_name,
                                                 GROUP_CONCAT(DISTINCT CONCAT(s.subject_code, '(', s.subject_name, ')') ORDER BY s.subject_name ASC SEPARATOR '<br>') AS subjects
                                          FROM admin_notice AS a
                                          JOIN admin_notice_type AS act ON a.id = act.admin_notice_id
                                          LEFT JOIN subject AS s ON act.subject_code = s.subject_code
                                          JOIN semester AS se ON act.semester_id = se.semester_id
                                          JOIN course AS co ON act.course_code = co.course_code
                                          JOIN department AS d ON act.department_id = d.department_id
                                          WHERE a.type = 'filter'
                                          GROUP BY a.id, a.title, a.message, a.created_at, se.semester_name, co.course_name, d.department_name
                                  ";

                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                // Define variables from the row
                                                $id = $row['id'];
                                                $title = $row['title'];  
                                                $message = $row['message'];  
                                                $created_at = $row['created_at'];  
                                                $subjects = $row['subjects'];  // This will already include subject_code(subject_name)
                                                $semester = $row['semester_name'];
                                                $course = $row['course_name'];
                                                $limited_message = limitText($message, 50);
                                                $department = $row['department_name'];

                                                  echo '
                                                  <tr>
                                                      <td class="notice-title">' . htmlspecialchars($title) . '</td>
                                                      <td class="notice-message">' . $limited_message . '</td>
                                                      <td class="notice-course">
                                                          ' . htmlspecialchars($course) . '
                                                         -
                                                          ' . htmlspecialchars($semester) . '
                                                          <hr>
                                                          ' . htmlspecialchars($department) . '
                                                      </td>
                                                      <td class="notice-subject">
                                                          ' . $subjects . '
                                                      </td>
                                                      <td class="notice-date">';
                                                          $date = new DateTime($created_at);
                                                          echo htmlspecialchars($date->format('d-m-Y'));
                                                      echo '</td>
                                                      <td class="text-end">
                                                          <!-- Dropdown -->
                                                          <div class="dropdown">
                                                              <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                  <i class="fe fe-more-vertical"></i>
                                                              </a>
                                                              <div class="dropdown-menu dropdown-menu-end">
                                                                  <a href="#" class="dropdown-item" onclick="confirmDeleteType('.$id.')">Delete</a>
                                                              </div>
                                                          </div>
                                                      </td>
                                                  </tr>';
                                          }
                                        } else {
                                             echo '<tr><td colspan="6">No notices found.</td></tr>';
                                        }
                          ?>
                      </tbody>

                </table>
              </div>
            </div>

          </div>
        </div> <!-- / .row -->
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editNoticeForm">
                    <input type="hidden" id="editNoticeId">
                    <div class="mb-3">
                        <label for="editNoticeTitle" class="form-label">Notice Title<span class="text-red">*</span></label>
                        <input type="text" class="form-control" id="editNoticeTitle">
                    </div>
                    <div class="mb-3">
                        <label for="editNoticeMessage" class="form-label">Notice Message<span class="text-red">*</span></label>
                        <textarea name="editNoticeMessage" id="editNoticeMessage" class="form-control"></textarea>

                    </div>
                    <div class="mb-3">
                        <label for="editNoticeCreatedAt" class="form-label">Annouced Date<span class="text-red">*</span></label>
                        <input type="date" class="form-control" id="editNoticeCreatedAt">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.8.0/ckeditor.js"></script>

<script>
      CKEDITOR.replace('editNoticeMessage');

  document.addEventListener('DOMContentLoaded', function() {
    // Function to show content based on the selected tab
    function showContent(tabId) {
      // Get all content sections
      const contents = document.querySelectorAll('.tab-content');
      
      // Hide all content sections
      contents.forEach(function(content) {
        content.classList.add('d-none');
      });
      
      // Show the selected content section
      const selectedContent = document.getElementById('content-' + tabId);
      if (selectedContent) {
        selectedContent.classList.remove('d-none');
      }

      // Remove 'active' class from all tabs
      const tabs = document.querySelectorAll('.nav-link');
      tabs.forEach(function(tab) {
        tab.classList.remove('active');
      });

      // Add 'active' class to the clicked tab
      const selectedTab = document.getElementById('tab-' + tabId);
      if (selectedTab) {
        selectedTab.classList.add('active');
      }
    }

    // Add click event listeners to tabs
    document.getElementById('tab-all').addEventListener('click', function() {
      showContent('all');
    });

    document.getElementById('tab-filter').addEventListener('click', function() {
      showContent('filter');
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const ID = this.getAttribute('data-notice-id');
            const noticeTitle = this.getAttribute('data-notice-title');
            const noticeMessage = this.getAttribute('data-notice-message');
            const noticeCreated_at = this.getAttribute('data-notice-created_at');

            document.getElementById('editNoticeId').value = ID;
            document.getElementById('editNoticeTitle').value = noticeTitle;
            CKEDITOR.instances.editNoticeMessage.setData(noticeMessage);
                        document.getElementById('editNoticeCreatedAt').value = noticeCreated_at;
           
        });
    });

    // Handle form submission
    document.getElementById('editNoticeForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const NoticeId = document.getElementById('editNoticeId').value;
        const NoticeTitle = document.getElementById('editNoticeTitle').value;
        const NoticeMessage = CKEDITOR.instances.editNoticeMessage.getData();
                const NoticeCreatedAt = document.getElementById('editNoticeCreatedAt').value;

        fetch('../functions/edit_adminNoticeAll.php', {
            method: 'POST',
            body: JSON.stringify({ id: NoticeId, title: NoticeTitle, message:NoticeMessage, created_at:NoticeCreatedAt }),
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            }).catch(error => {
                console.error('Error:', error);
            });
    });
    window.confirmDelete = function(Id) {
        if (confirm('Are you sure you want to delete this Notice?')) {
            fetch('../functions/delete_adminNoticeAll.php', {
                method: 'POST',
                body: JSON.stringify({ id: Id }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                      alert(data.message);

                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
        }
    };
    window.confirmDeleteType = function(Id) {
        if (confirm('Are you sure you want to delete this Notice?')) {
            fetch('../functions/delete_adminNoticeType.php', {
                method: 'POST',
                body: JSON.stringify({ id: Id }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                      alert(data.message);

                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
        }
    };
  });
</script>



<?php 
include '../includes/footer.php'
?>