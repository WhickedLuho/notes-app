<?php
namespace App\Models;

class Note extends BaseModel
{
    protected string $table = 'notes';

    public function saveNote($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $stmt = $this->db->prepare("
                UPDATE notes SET title = :title, content = :content, color = :color, is_pinned = :is_pinned, is_archieved = :is_archieved, modified_at = NOW()
                WHERE id = :id AND user_id = :user_id
            ");
        } else {
            $stmt = $this->db->prepare("
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
        $stmt = $this->db->prepare("SELECT * FROM notes WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY is_pinned DESC, created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPinnedByUser($userId, $limit = 6)
    {
        $limit = (int)$limit;
        try {
            $stmt = $this->db->prepare("
                SELECT * 
                FROM notes 
                WHERE user_id = :user_id 
                    AND deleted_at IS NULL 
                    AND is_pinned = 1
                ORDER BY created_at DESC 
                LIMIT $limit
            ");
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            error_log("Database error in getPinnedByUser(): " . $e->getMessage());
            return 0;
        }
    }

    public function getAllNoteCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(id) as note_count 
                FROM notes 
                WHERE user_id = :user_id 
                AND deleted_at IS NULL
            ");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return (int)($result['note_count'] ?? 0);
        } catch (\PDOException $e) {
            error_log("Database error in getAllNoteCount(): " . $e->getMessage());
            return 0;
        }
    }

    public function getPinnedNoteCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(id) as note_count 
                FROM notes 
                WHERE user_id = :user_id 
                AND deleted_at IS NULL AND is_pinned = 1
            ");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return (int)($result['note_count'] ?? 0);
        } catch (\PDOException $e) {
            error_log("Database error in getAllNoteCount(): " . $e->getMessage());
            return 0;
        }
    }

    public function getArchivedCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(id) as count 
                FROM notes 
                WHERE user_id = :user_id 
                AND is_archieved = 1
                AND deleted_at IS NULL
            ");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return (int)($result['count'] ?? 0);
        } catch (\PDOException $e) {
            error_log("Database error in getArchivedCount(): " . $e->getMessage());
            return 0;
        }
    }

    public function getTagCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT tag) as count 
                FROM note_tags 
                WHERE user_id = :user_id
            ");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return (int)($result['count'] ?? 0);
        } catch (\PDOException $e) {
            error_log("Database error in getTagCount(): " . $e->getMessage());
            return 0;
        }
    }

    public function getById($id, $userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function softDelete($id, $userId)
    {
        $stmt = $this->db->prepare("UPDATE notes SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}
