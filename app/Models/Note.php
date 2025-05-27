<?php
namespace App\Models;

class Note extends BaseModel
{
    protected $table = 'notes';

    public function saveNote($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $stmt = $this->pdo->prepare("
                UPDATE notes SET title = :title, content = :content, color = :color, is_pinned = :is_pinned, is_archieved = :is_archieved, modified_at = NOW()
                WHERE id = :id AND user_id = :user_id
            ");
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO notes (user_id, title, content, color, is_pinned, is_archieved, created_at)
                VALUES (:user_id, :title, :content, :color, :is_pinned, :is_archieved, NOW())
            ");
        }

        return $stmt->execute([
            ':id' => $data['id'] ?? null,
            ':user_id' => $data['user_id'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':color' => $data['color'] ?? '#FFFFFF',
            ':is_pinned' => $data['is_pinned'] ?? 0,
            ':is_archieved' => $data['is_archieved'] ?? 0
        ]);
    }

    public function getAllByUser($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY is_pinned DESC, created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id, $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function softDelete($id, $userId)
    {
        $stmt = $this->pdo->prepare("UPDATE notes SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}
