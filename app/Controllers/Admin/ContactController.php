<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class ContactController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /admin/contacts
     */
    public function index()
    {
        $stmt = $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC");
        $contacts = $stmt->fetchAll();

        $unreadCount = (int)$this->db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();

        return $this->render('contacts/index', [
            'contacts' => $contacts,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * GET /admin/contacts/{id}
     */
    public function show($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        $contact = $stmt->fetch();

        if (!$contact) {
            $_SESSION['error_message'] = 'Contato não encontrado.';
            header('Location: /admin/contacts');
            exit;
        }

        // Marcar como lido
        if (!$contact['is_read']) {
            $this->db->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?")->execute([$id]);
            $contact['is_read'] = 1;
        }

        return $this->render('contacts/show', [
            'contact' => $contact,
        ]);
    }

    /**
     * POST /admin/contacts/{id}/delete
     */
    public function delete($id)
    {
        $this->db->prepare("DELETE FROM contacts WHERE id = ?")->execute([$id]);
        $_SESSION['success_message'] = 'Contato removido.';
        header('Location: /admin/contacts');
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Página {$view} em construção</h2>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
