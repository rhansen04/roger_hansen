<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class Notification
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Create a notification
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO notifications (user_id, type, title, message, reference_type, reference_id)
                    VALUES (:user_id, :type, :title, :message, :reference_type, :reference_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'],
                ':type' => $data['type'],
                ':title' => $data['title'],
                ':message' => $data['message'] ?? null,
                ':reference_type' => $data['reference_type'] ?? null,
                ':reference_id' => $data['reference_id'] ?? null,
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar notificacao: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find a single notification by ID
     */
    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM notifications WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar notificacao: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get recent notifications for a user
     */
    public function findByUser($userId, $limit = 20)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM notifications
                WHERE user_id = ?
                ORDER BY created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$userId, (int) $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar notificacoes do usuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count unread notifications for a user
     */
    public function countUnread($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$userId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar notificacoes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao marcar notificacao como lida: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        try {
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erro ao marcar todas notificacoes como lidas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Convenience static helper to create a notification
     */
    public static function notify($userId, $type, $title, $message = null, $refType = null, $refId = null)
    {
        $instance = new self();
        return $instance->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'reference_type' => $refType,
            'reference_id' => $refId,
        ]);
    }

    /**
     * Notify all coordenadores (used by approval flow)
     */
    public static function notifyAllCoordenadores($type, $title, $message = null, $refType = null, $refId = null)
    {
        try {
            $db = Connection::getInstance();
            $stmt = $db->query("SELECT id FROM users WHERE role = 'coordenador'");
            $coordenadores = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($coordenadores as $coordId) {
                self::notify($coordId, $type, $title, $message, $refType, $refId);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Erro ao notificar coordenadores: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the URL for a notification based on its reference
     */
    public function getReferenceUrl($notification)
    {
        if (empty($notification['reference_type']) || empty($notification['reference_id'])) {
            return '/admin/notifications';
        }

        $type = $notification['reference_type'];
        $id = $notification['reference_id'];

        switch ($type) {
            case 'descriptive_report':
                return "/admin/descriptive-reports/{$id}";
            case 'portfolio':
                return "/admin/portfolios/{$id}";
            case 'planning':
                return "/admin/planning/{$id}";
            case 'observation':
                return "/admin/observations/{$id}";
            default:
                return '/admin/notifications';
        }
    }
}
