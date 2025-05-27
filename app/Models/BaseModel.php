<?php
namespace App\Models;

use PDO;
use Exception;
use DateTime;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected bool $softDelete = true;

    public function __construct(PDO $db)
    {
        $this->db = $db;

        if (!isset($this->table)) {
            throw new Exception("Model must define a table name.");
        }
    }

    // Alap where feltétel soft delete-hez
    protected function baseWhere(): string
    {
        return $this->softDelete ? "WHERE deleted_at IS NULL" : "";
    }

    public function find(int $id): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";

        if ($this->softDelete) {
            $query .= " AND deleted_at IS NULL";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function all(): array
    {
        $query = "SELECT * FROM {$this->table} " . $this->baseWhere();
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = (new DateTime())->format('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data['modified_at'] = (new DateTime())->format('Y-m-d H:i:s');

        $fields = implode(', ', array_map(fn($key) => "{$key} = ?", array_keys($data)));
        $data[] = $id;

        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = ?");
        return $stmt->execute(array_values($data));
    }

    public function delete(int $id): bool
    {
        if ($this->softDelete) {
            $now = (new DateTime())->format('Y-m-d H:i:s');
            $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = ? WHERE {$this->primaryKey} = ?");
            return $stmt->execute([$now, $id]);
        } else {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
            return $stmt->execute([$id]);
        }
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
