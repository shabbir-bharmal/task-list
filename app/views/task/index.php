<?php require_once '../app/views/layout/header.php'; ?>
<main class="container mt-4">
    <h1 class="mb-4">Task Management</h1>

    <div class="row">
        <!-- Left Column: Form and Task List -->
        <div class="col-md-8">
            <!-- Add Task Form -->
            <form id="addTaskForm" class="mb-4">
                <div class="form-row">
                    <div class="col-md-8 mb-2">
                        <input type="text" id="taskName" name="task_name" class="form-control" placeholder="Enter task name" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Tasks Table -->
            <div class="table-container">
                <table class="table table-bordered table-striped" id="tasksTable">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%"></th>
                            <th width="85%">Task Name</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['tasks'])): ?>
                            <tr id="noTaskRow">
                                <td colspan="3" class="text-center">No Task Available</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['tasks'] as $task): ?>
                                <tr data-task-id="<?= $task['id']; ?>">
                                    <td><input type="checkbox" id="task_status" name="task_status" <?= $task['task_status'] ? 'checked' : ''; ?>></td>
                                    <td class="task-name-cell <?= $task['task_status'] ? 'completed-task' : null ?>">
                                        <span class="task-name-text"><?= htmlspecialchars($task['task_name']); ?></span>
                                        <input type="text" name="task_name" class="form-control task-name-input" value="<?= htmlspecialchars($task['task_name']); ?>" style="display:none;">
                                    </td>
                                    <td>
                                        <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                        <button type="button" class="btn btn-link btn-sm update-task <?= $task['task_status'] ? 'd-none' : null ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-link btn-sm delete-task" data-task-id="<?= $task['id']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-link btn-sm save-task d-none" data-task-id="<?= $task['id']; ?>">
                                            <i class="bi bi-file-earmark-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<!-- Define BASE_URL in JavaScript -->
<script>
    var BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<!-- AJAX Script -->
<script>
    $(document).ready(function() {
        // Handle form submission for adding tasks
        $('#addTaskForm').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting the default way

            var taskName = $('#taskName').val();

            $.ajax({
                url: BASE_URL + 'task/create', // URL to send the request to
                type: 'POST',
                data: {
                    task_name: taskName
                },
                dataType: 'json', // Ensure the response is parsed as JSON
                success: function(response) {
                    if (response && response.id) {
                        $('#noTaskRow').remove();
                        $('#tasksTable tbody').append(`
                        <tr data-task-id="${response.id}">
                            <td><input type="checkbox" name="task_status" ${response.task_status ? 'checked' : ''}></td>
                            <td>
                                <span class="task-name-text">${response.task_name}</span>
                                <input type="text" name="task_name" class="form-control task-name-input" value="${response.task_name}" style="display:none;">
                            </td>
                            <td>
                                <input type="hidden" name="id" value="${response.id}">
                                <button type="button" class="btn btn-link btn-sm update-task">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-link btn-sm delete-task" data-task-id="${response.id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button type="button" class="btn btn-link btn-sm save-task d-none" data-task-id="<?= $task['id']; ?>">
                                        <i class="bi bi-file-earmark-check"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                        $('#taskName').val(''); // Clear the input field
                    } else {
                        console.error('Invalid response format');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });

        $(document).on('click', '.update-task', function() {
            var row = $(this).closest('tr');
            row.find('.task-name-text').hide(); // Hide the task name text
            row.find('.task-name-input').show(); // Show the task name input
            row.find('.update-task').hide(); // Hide the update button
            row.find('.delete-task').hide(); // Hide the delete button
            row.find('.save-task').removeClass('d-none'); // Show the save button
            row.find('#task_status').hide();
        });
        // Handle task update
        $(document).on('click', '.save-task', function() {
            var row = $(this).closest('tr');
            var taskNameText = row.find('.task-name-text');
            var taskNameInput = row.find('.task-name-input');

            if (taskNameInput.is(':visible')) {
                var taskId = row.find('input[name="id"]').val();
                var updatedTaskName = taskNameInput.val();

                $.ajax({
                    url: BASE_URL + 'task/update',
                    type: 'POST',
                    data: {
                        id: taskId,
                        task_name: updatedTaskName
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            taskNameText.text(updatedTaskName); // Update text
                            taskNameInput.hide(); // Hide input
                            taskNameText.show(); // Show text
                            row.find('.save-task').hide(); // Hide save button
                            row.find('.update-task, .delete-task, #task_status').show();
                        } else {
                            console.error('Update failed');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }
        });

        // Handle task deletion
        $(document).on('click', '.delete-task', function() {
            var taskId = $(this).data('task-id');
            var row = $(this).closest('tr');

            $.ajax({
                url: BASE_URL + 'task/delete/' + taskId,
                type: 'GET',
                data: {
                    Id: taskId
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response && response.success) {
                        row.remove(); // Remove the row from the table
                        if ($('#tasksTable tbody tr').length === 0) {
                            // If no tasks left, show the "No task available" message
                            $('#tasksTable tbody').append(`
                            <tr id="noTaskRow">
                                <td colspan="3" class="text-center">No Task Available</td>
                            </tr>
                        `);
                        }
                    } else {
                        console.error('Delete failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });

        // Handle task status change
        $(document).on('change', 'input[name="task_status"]', function() {
            var row = $(this).closest('tr');
            var taskId = row.find('input[name="id"]').val();
            var taskStatus = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: BASE_URL + 'task/updateStatus',
                type: 'POST',
                data: {
                    id: taskId,
                    task_status: taskStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        row.find('.task-name-cell').toggleClass('completed-task');
                        row.find('.update-task').toggleClass('d-none');
                    } else {
                        console.error('Status update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });
</script>
<?php require_once '../app/views/layout/footer.php'; ?>