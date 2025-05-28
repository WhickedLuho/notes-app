<?php
namespace App\Models;

class User extends BaseModel
{
    protected string $table = 'users';

    public int $id;
    public string $nickname;
    public string $fullname;
    public string $email;
    public ?string $email_verified_at;
    public string $password_hash;
    public ?string $remember_token;
    public bool $is_active;
    public string $created_at;
    public ?string $modified_at;
    public ?string $deleted_at;

    public function findActiveById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? AND is_active = 1 AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if ($data) {
            $user = new self($this->db);
            $user->fill($data);
            // $user->toArray($data);
            return $user;
        }

        return null;
    }

}

