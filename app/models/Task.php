<?php
class Task extends Model
{
    public function addTask($taskName)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO tasks (task_name) VALUES (:task_name)");
            $stmt->bindParam(':task_name', $taskName);
            $stmt->execute();
            return ['id' => $this->db->lastInsertId(), 'task_name' => $taskName, 'task_status' => 0]; // Return new task details
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getTasks()
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTask($id, $task_name)
    {
        try {
            $stmt = $this->db->prepare("UPDATE tasks SET task_name = :task_name WHERE id = :id");
            $stmt->bindParam(':task_name', $task_name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateTaskStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET task_status = :task_status WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'task_status' => $status
        ]);
    }

    public function getCompletedTasks()
    {
        $stmt = $this->db->query("SELECT * FROM tasks WHERE task_status = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTask($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
