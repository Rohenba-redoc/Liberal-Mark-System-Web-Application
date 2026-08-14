<?php include '../includes/header.php'; ?>
<?php include '../functions/display.php'; ?>
<?php $passkeys = fetchPass(); ?>

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
                            <h1 class="header-title">Passkey</h1>
                        </div>
                    </div>
                </div> <!-- / .row -->
            </div>
            <div class="card" data-list='{"valueNames": ["notice-title", "notice-message", "notice-date", "notice-course", "notice-subject", "notice-semester"]}'>
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap card-table tab-content" id="content-all">
                        <thead>
                            <tr>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-title">
                                        Role
                                    </a>
                                </th>
                                <th colspan="2">
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-date">
                                        Passkey
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($passkeys)): ?>
                                <?php foreach ($passkeys as $pass): ?>
                                    <tr>
                                        <td class="notice-title">
                                            <?php echo htmlspecialchars($pass['role']); ?>
                                        </td>
                                        <td class="notice-message">
                                            <?php echo htmlspecialchars($pass['passkey']); ?>
                                        </td>
                                        <td class="text-end">
                                            <!-- Dropdown -->
                                            <div class="dropdown" style="position:absolute">
                                                <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal"
                                                        data-pass-id="<?php echo htmlspecialchars($pass['id']); ?>" 
                                                        data-pass-key="<?php echo htmlspecialchars($pass['passkey']); ?>">
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">No Pass found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Update Passkey</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPassForm">
                    <input type="hidden" id="editPassId">
                    <div class="mb-3">
                        <label for="editPassKey" class="form-label">Pass Key</label>
                        <input type="text" class="form-control" id="editPassKey" maxlength="4">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Populate modal with passkey data
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const ID = this.getAttribute('data-pass-id');
            const passkey = this.getAttribute('data-pass-key');
            
            document.getElementById('editPassId').value = ID;
            document.getElementById('editPassKey').value = passkey;
        });
    });

    // Enforce exactly four characters for passkey input
    const passKeyInput = document.getElementById('editPassKey');
    passKeyInput.addEventListener('input', function() {
        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4);
        }
    });

    // Handle form submission
    document.getElementById('editPassForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const ID = document.getElementById('editPassId').value;
        const Key = document.getElementById('editPassKey').value;

        if (Key.length !== 4) {
            alert('The passkey must be exactly 4 characters long.');
            return;
        }

        fetch('../functions/edit_pass.php', {
            method: 'POST',
            body: JSON.stringify({ id: ID, key: Key }),
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
    });
});
</script>

<?php include '../includes/footer.php'; ?>
