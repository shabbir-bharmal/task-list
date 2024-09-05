<?php
class TaskController extends Controller
{

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskModel = $this->model('Task');
            $response = $taskModel->addTask($_POST['task_name']);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskModel = $this->model('Task');
            $response = $taskModel->updateTask($_POST['id'], $_POST['task_name']);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $taskModel = $this->model('Task');
            $id = $_POST['id'];
            $status = isset($_POST['task_status']) ? 1 : 0;
            $taskModel->updateTaskStatus($id, $status);

            // Fetch updated completed tasks
            $completedTasks = $taskModel->getCompletedTasks();

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'completedTasks' => $completedTasks
            ]);
        }
    }

    public function delete($id)
    {
        $id =  $_GET['Id'];
        $taskModel = $this->model('Task');

        if ($taskModel->deleteTask($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function index()
    {
        $taskModel = $this->model('Task');

        $tasks = $taskModel->getTasks();
        $completedTasks = $taskModel->getCompletedTasks();

        $this->view('task/index', [
            'tasks' => $tasks,
            'completedTasks' => $completedTasks
        ]);
    }
}
