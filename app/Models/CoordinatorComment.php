<?php
namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class CoordinatorComment
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->createTable();
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS coordinator_comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            coordinator_id INT NOT NULL,
            content_type ENUM('observation','descriptive_report','portfolio','planning') NOT NULL,
            content_id INT NOT NULL,
            comment TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_content (content_type, content_id),
            INDEX idx_coordinator (coordinator_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        try {
            $this->db->exec($sql);
        } catch (PDOException $e) {
            error_log("CoordinatorComment createTable: " . $e->getMessage());
        }
    }

    public function findByContent(string $type, int $contentId): array
    {
        try {
            $sql = "SELECT cc.*, u.name as coordinator_name
                    FROM coordinator_comments cc
                    JOIN users u ON u.id = cc.coordinator_id
                    WHERE cc.content_type = ? AND cc.content_id = ?
                    ORDER BY cc.created_at ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$type, $contentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CoordinatorComment findByContent: " . $e->getMessage());
            return [];
        }
    }

    public function deleteByContent(string $type, int $id): void
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM coordinator_comments WHERE content_type = ? AND content_id = ?");
            $stmt->execute([$type, $id]);
        } catch (\PDOException $e) {
            error_log("CoordinatorComment::deleteByContent: " . $e->getMessage());
        }
    }

    public function create(array $data): int|false
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO coordinator_comments (coordinator_id, content_type, content_id, comment)
                 VALUES (:coordinator_id, :content_type, :content_id, :comment)"
            );
            $stmt->execute([
                ':coordinator_id' => $data['coordinator_id'],
                ':content_type'   => $data['content_type'],
                ':content_id'     => $data['content_id'],
                ':comment'        => $data['comment'],
            ]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("CoordinatorComment create: " . $e->getMessage());
            return false;
        }
    }
}
