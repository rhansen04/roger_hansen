<?php

namespace App\Models;

use App\Core\Database\Connection;

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        try {
            // Hash da senha antes de salvar
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (\PDOException $e) {
            error_log("Erro ao criar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        // Se houver senha, fazer hash
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id";
        } else {
            // Remover senha do array se estiver vazia
            unset($data['password']);
            $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        }

        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countByRole($role)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE role = ?");
        $stmt->execute([$role]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function updateLastLogin($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET last_login = ? WHERE id = ?");
        return $stmt->execute([date('Y-m-d H:i:s'), $id]);
    }
}
