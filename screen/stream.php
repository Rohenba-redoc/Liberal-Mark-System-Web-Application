<?php 
include '../includes/header.php';
include '../functions/display.php';

$streams = fetchStreams();
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
                            <h1 class="header-title">Stream</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="add_stream.php" class="btn btn-primary lift">New Stream</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["streams-stream", "streams-title"]}'>
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
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap card-table">
                        <thead>
                            <tr>
                               
                                <th>Sl No.</th>
                                <th colspan="2">
                                    <a href="#" class="text-body-secondary list-sort" data-sort="streams-title">Title</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                        <?php if (!empty($streams)): ?>
                            <?php $slNo = 1; ?>
                            <?php foreach ($streams as $stream): ?>
                            <tr>
                                
                                <td class="streams-stream"><?php echo $slNo++; ?></td>
                                <td class="streams-title"><?php echo htmlspecialchars($stream['stream_title']); ?></td>
                                <td class="text-end">
                                    <!-- Dropdown -->
                                    <div class="dropdown" style="position:absolute">
                                        <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal" data-stream-id="<?php echo htmlspecialchars($stream['stream_id']); ?>" data-stream-title="<?php echo htmlspecialchars($stream['stream_title']); ?>">
                                                Edit
                                            </a>
                                            <a href="#" class="dropdown-item" onclick="confirmDelete('<?php echo htmlspecialchars($stream['stream_id']); ?>')">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No Streams found</td>
                            </tr>
                        <?php endif; ?>
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
                <h5 class="modal-title" id="editModalLabel">Edit Stream</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStreamForm">
                    <input type="hidden" id="editStreamId">
                    <div class="mb-3">
                        <label for="editStreamTitle" class="form-label">Stream Title<span class="text-red">*</span></label>
                        <input type="text" class="form-control" id="editStreamTitle">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// JavaScript
document.addEventListener("DOMContentLoaded", function() {
    // Populate the modal with stream data
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const streamId = this.getAttribute('data-stream-id');
            const streamTitle = this.getAttribute('data-stream-title');

            document.getElementById('editStreamId').value = streamId;
            document.getElementById('editStreamTitle').value = streamTitle;
        });
    });

    // Handle form submission
    document.getElementById('editStreamForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const streamId = document.getElementById('editStreamId').value;
        const streamTitle = document.getElementById('editStreamTitle').value;

        fetch('../functions/edit_stream.php', {
            method: 'POST',
            body: JSON.stringify({ stream_id: streamId, stream_title: streamTitle }),
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

    // Define the confirmDelete function
    window.confirmDelete = function(streamId) {
        if (confirm('Are you sure you want to delete this stream?')) {
            fetch('../functions/delete_stream.php', {
                method: 'POST',
                body: JSON.stringify({ stream_id: streamId }),
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
        }
    };

   
});
</script>

<?php 
include '../includes/footer.php';
?>
