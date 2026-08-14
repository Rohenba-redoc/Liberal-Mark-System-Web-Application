<?php 
include '../includes/header.php';
include '../functions/display.php';

$admins = fetchAdmin();
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
                    <h6 class="header-pretitle">
                      Overview
                    </h6>

                    <!-- Title -->
                    <h1 class="header-title">
                      Admin User
                    </h1>

                  </div>
                  <div class="col-auto">

                  <a href="#" class="btn btn-primary lift" data-bs-toggle="modal" data-bs-target="#addModal">
                                New User
                            </a>

                  </div>
                </div> <!-- / .row -->
                <div class="row align-items-center">
                  <div class="col">

                    <!-- Nav -->
                    <ul class="nav nav-tabs nav-overflow header-tabs">
                      <li class="nav-item">
                        <a href="#!" class="nav-link active">
                          All <span class="badge rounded-pill text-bg-secondary-subtle"></span>
                        </a>
                      </li>
                      
                      
                    </ul>

                  </div>
                </div>
              </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["admin-username"]}'>
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
                <table class="table table-sm table-nowrap card-table" >
                  <thead>
                    <tr>
                     
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="admin-username">
                          Username
                        </a>
                      </th>
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="admin-email">
                          Email
                        </a>
                      </th>
                      <th>
                        <a href="#" class="text-body-secondary list-sort" data-sort="admin-level">
                          Level
                        </a>
                      </th>
                      <th colspan="2">
                        <a href="#" class="text-body-secondary list-sort" data-sort="admin-status">
                          Status
                        </a>
                      </th>
                      
                    </tr>
                  </thead>
                  <tbody class="list">
                  <?php if (!empty($admins)): ?>
                    <?php foreach($admins as $admin): ?>
                    <tr>
                     
                      <td class="admin-username">
                      <?php echo htmlspecialchars($admin['username']); ?>

                      </td>
                      <td class="admin-username">
                      <?php echo htmlspecialchars($admin['email']); ?>

                      </td>
                      <td class="admin-level">
                      <?php echo htmlspecialchars($admin['level']); ?>
                      </td>
                      <td class="admin-status">

                      <?php echo htmlspecialchars($admin['status']); ?>


                      </td>
                     
                      <td class="text-end">

                        <!-- Dropdown -->
                        <div class="dropdown" style="position:absolute">
                          <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe fe-more-vertical"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-end">
                          <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal" 
                          data-id="<?php echo $admin['id']; ?>" 
                          data-username="<?php echo htmlspecialchars($admin['username']); ?>"
                          data-email="<?php echo htmlspecialchars($admin['email']); ?>"
                           data-level="<?php echo htmlspecialchars($admin['level']); ?>">
                            Edit
                            </a>
                            <a href="#" class="dropdown-item"
                                                 onclick="confirmDelete('<?php echo htmlspecialchars($admin['id']); ?>')">
                                                Delete
                                            </a>
                            
                            <?php if($admin['status'] == 'active'){?>
                                <a href="#" class="dropdown-item"
                                    onclick="confirmDeActivate('<?php echo htmlspecialchars($admin['id']); ?>')">
                                        De-Activate
                                </a>
                           <?php }
                            else{?>
                               <a href="#" class="dropdown-item"
                                    onclick="confirmActivate('<?php echo htmlspecialchars($admin['id']); ?>')">
                                        Activate
                                </a>
                            <?php }
                            ?>
                           
                          </div>
                        </div>

                      </td>
                    </tr>
                    <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;">No User found</td>
                                </tr>
                            <?php endif; ?> 
                    
                   
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div> <!-- / .row -->
</div>
<!-- Add Teacher Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" class="form-control" id="name" name="name" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" class="form-control" id="pass" name="pass" required>
                    </div>
                  
                        <div class="mb-3 ">
                            <label for="level" class="form-label">Account Level</label>
                            <select class="form-control" id="level" name="level" required>
                                <option value="Intermediate Admin">Intermediate Admin</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                        
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editName" name="name" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="text" class="form-control" id="editEmail" name="email" required autocomplete="off">
                    </div>
                    
                    <div class="mb-3">
                        <label for="editLevel" class="form-label">Account Level</label>
                        <select class="form-control" id="editLevel" name="level" required>
                            <option value="Intermediate Admin">Intermediate Admin</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Teacher
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var pass = document.getElementById('pass').value;
        var level = document.getElementById('level').value;
       

        fetch('../functions/add_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                name: name, 
                email: email, 
                pass: pass, 
                level: level, 
              
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);

                location.reload(); 
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Handle opening the edit modal
    document.querySelectorAll('.dropdown-item[data-bs-target="#editModal"]').forEach(item => {
        item.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            const level = this.getAttribute('data-level');
            
            document.getElementById('editUserId').value = id;
            document.getElementById('editName').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editLevel').value = level;
        });
    });

    // Handle edit form submission
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var id = document.getElementById('editUserId').value;
        var name = document.getElementById('editName').value;
        var email = document.getElementById('editEmail').value;
        var level = document.getElementById('editLevel').value;

        fetch('../functions/edit_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                name: name,
                email: email,
                level: level
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
// Define the confirmDelete function
window.confirmDelete = function(Id) {
        if (confirm('Are you sure you want to delete this User?')) {
            fetch('../functions/delete_user.php', {
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
window.confirmDeActivate = function(Id) {
        if (confirm('Are you sure you want to De-Activate this User?')) {
            fetch('../functions/deactivate.php', {
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
window.confirmActivate = function(Id) {
        if (confirm('Are you sure you want to Activate this User?')) {
            fetch('../functions/activate.php', {
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
</script>
<?php 
include '../includes/footer.php'
?>