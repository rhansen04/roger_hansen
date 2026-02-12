<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class School
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todas as escolas
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM schools ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar escolas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar escola por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM schools WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar escola: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Criar nova escola
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO schools (name, city, state, address, contact_person, phone, email,
                    contract_start_date, contract_end_date, logo_url, status, created_at, updated_at)
                    VALUES (:name, :city, :state, :address, :contact_person, :phone, :email,
                    :contract_start_date, :contract_end_date, :logo_url, :status, :created_at, :updated_at)";

            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');

            $result = $stmt->execute([
                ':name' => $data['name'],
                ':city' => $data['city'] ?? null,
                ':state' => $data['state'] ?? null,
                ':address' => $data['address'] ?? null,
                ':contact_person' => $data['contact_person'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':email' => $data['email'] ?? null,
                ':contract_start_date' => $data['contract_start_date'] ?? null,
                ':contract_end_date' => $data['contract_end_date'] ?? null,
                ':logo_url' => $data['logo_url'] ?? null,
                ':status' => $data['status'] ?? 'active',
                ':created_at' => $now,
                ':updated_at' => $now
            ]);

            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Erro ao criar escola: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar escola
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE schools
                    SET name = :name,
                        city = :city,
                        state = :state,
                        address = :address,
                        contact_person = :contact_person,
                        phone = :phone,
                        email = :email,
                        contract_start_date = :contract_start_date,
                        contract_end_date = :contract_end_date,
                        logo_url = :logo_url,
                        status = :status,
                        updated_at = :updated_at
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':city' => $data['city'] ?? null,
                ':state' => $data['state'] ?? null,
                ':address' => $data['address'] ?? null,
                ':contact_person' => $data['contact_person'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':email' => $data['email'] ?? null,
                ':contract_start_date' => $data['contract_start_date'] ?? null,
                ':contract_end_date' => $data['contract_end_date'] ?? null,
                ':logo_url' => $data['logo_url'] ?? null,
                ':status' => $data['status'] ?? 'active',
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar escola: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar escola
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM schools WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar escola: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Contar escolas ativas
     */
    public function countActive()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM schools WHERE status = 'active'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar escolas: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Buscar escola com contagem de alunos
     */
    public function findWithStudentsCount($id)
    {
        try {
            $sql = "SELECT s.*, COUNT(st.id) as students_count
                    FROM schools s
                    LEFT JOIN students st ON s.id = st.school_id
                    WHERE s.id = ?
                    GROUP BY s.id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar escola com contagem: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar todas as escolas com contagem de alunos
     */
    public function allWithStudentsCount()
    {
        try {
            $sql = "SELECT s.*, COUNT(st.id) as students_count
                    FROM schools s
                    LEFT JOIN students st ON s.id = st.school_id
                    GROUP BY s.id
                    ORDER BY s.name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar escolas com contagem: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar alunos de uma escola
     */
    public function getStudents($schoolId)
    {
        try {
            $sql = "SELECT * FROM students WHERE school_id = ? ORDER BY name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$schoolId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar alunos da escola: " . $e->getMessage());
            return [];
        }
    }
}
