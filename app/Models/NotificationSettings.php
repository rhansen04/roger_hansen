<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class NotificationSettings
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Buscar configurações de um usuário
     */
    public function findByUser($userId)
    {
        try {
            $sql = "SELECT * FROM notification_settings WHERE user_id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar configurações: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar configurações por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM notification_settings WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar configurações: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Criar novas configurações
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO notification_settings 
                    (user_id, notify_video_completed, notify_course_completed, 
                     notify_quiz_results, notify_new_content, notify_reminders, 
                     notify_promotions, notify_updates, email_notifications, 
                     push_notifications, sms_notifications)
                    VALUES (:user_id, :notify_video_completed, :notify_course_completed,
                            :notify_quiz_results, :notify_new_content, :notify_reminders,
                            :notify_promotions, :notify_updates, :email_notifications,
                            :push_notifications, :sms_notifications)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar configurações: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar configurações
     */
    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE notification_settings
                    SET user_id = :user_id,
                        notify_video_completed = :notify_video_completed,
                        notify_course_completed = :notify_course_completed,
                        notify_quiz_results = :notify_quiz_results,
                        notify_new_content = :notify_new_content,
                        notify_reminders = :notify_reminders,
                        notify_promotions = :notify_promotions,
                        notify_updates = :notify_updates,
                        email_notifications = :email_notifications,
                        push_notifications = :push_notifications,
                        sms_notifications = :sms_notifications,
                        updated_at = NOW()
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar configurações: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deletar configurações
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM notification_settings WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar configurações: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obter ou criar configurações padrão para um usuário
     */
    public function getOrCreateForUser($userId)
    {
        $settings = $this->findByUser($userId);
        
        if ($settings) {
            return $settings;
        }
        
        $defaultSettings = [
            'user_id' => $userId,
            'notify_video_completed' => 1,
            'notify_course_completed' => 1,
            'notify_quiz_results' => 1,
            'notify_new_content' => 1,
            'notify_reminders' => 0,
            'notify_promotions' => 0,
            'notify_updates' => 1,
            'email_notifications' => 1,
            'push_notifications' => 1,
            'sms_notifications' => 0
        ];
        
        if ($this->create($defaultSettings)) {
            return $this->findByUser($userId);
        }
        
        return null;
    }
    
    /**
     * Verificar se usuário deve receber notificação específica
     */
    public function shouldNotify($userId, $notificationType)
    {
        try {
            $settings = $this->findByUser($userId);
            
            if (!$settings) {
                $settings = $this->getOrCreateForUser($userId);
            }
            
            if (!$settings) {
                return false;
            }
            
            $columnMap = [
                'video_completed' => 'notify_video_completed',
                'course_completed' => 'notify_course_completed',
                'quiz_results' => 'notify_quiz_results',
                'new_content' => 'notify_new_content',
                'reminders' => 'notify_reminders',
                'promotions' => 'notify_promotions',
                'updates' => 'notify_updates'
            ];
            
            $column = $columnMap[$notificationType] ?? null;
            
            if (!$column) {
                return false;
            }
            
            return (bool)($settings[$column] ?? false);
        } catch (PDOException $e) {
            error_log("Erro ao verificar notificação: " . $e->getMessage());
            return false;
        }
    }
}
