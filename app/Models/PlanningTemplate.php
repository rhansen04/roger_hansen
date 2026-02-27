<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class PlanningTemplate
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function all()
    {
        try {
            $sql = "SELECT * FROM planning_templates ORDER BY sort_order ASC, title ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar templates: " . $e->getMessage());
            return [];
        }
    }

    public function allActive()
    {
        try {
            $sql = "SELECT * FROM planning_templates WHERE is_active = 1 ORDER BY sort_order ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar templates ativos: " . $e->getMessage());
            return [];
        }
    }

    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_templates WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar template: " . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO planning_templates (title, description, age_group, is_active, sort_order, created_at, updated_at)
                    VALUES (:title, :description, :age_group, :is_active, :sort_order, :created_at, :updated_at)";
            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');
            $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'] ?? null,
                ':age_group' => $data['age_group'] ?? 'all',
                ':is_active' => $data['is_active'] ?? 1,
                ':sort_order' => $data['sort_order'] ?? 0,
                ':created_at' => $now,
                ':updated_at' => $now
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar template: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE planning_templates
                    SET title = :title, description = :description, age_group = :age_group,
                        is_active = :is_active, sort_order = :sort_order, updated_at = :updated_at
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':description' => $data['description'] ?? null,
                ':age_group' => $data['age_group'] ?? 'all',
                ':is_active' => $data['is_active'] ?? 1,
                ':sort_order' => $data['sort_order'] ?? 0,
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar template: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_templates WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar template: " . $e->getMessage());
            return false;
        }
    }

    // --- Sections ---

    public function getSections($templateId)
    {
        try {
            $sql = "SELECT * FROM planning_template_sections WHERE template_id = ? ORDER BY sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$templateId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar seções: " . $e->getMessage());
            return [];
        }
    }

    public function findSection($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_template_sections WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar seção: " . $e->getMessage());
            return null;
        }
    }

    public function createSection($data)
    {
        try {
            $sql = "INSERT INTO planning_template_sections (template_id, title, description, section_type, sort_order, is_registration)
                    VALUES (:template_id, :title, :description, :section_type, :sort_order, :is_registration)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':template_id' => $data['template_id'],
                ':title' => $data['title'],
                ':description' => $data['description'] ?? null,
                ':section_type' => $data['section_type'] ?? 'default',
                ':sort_order' => $data['sort_order'] ?? 0,
                ':is_registration' => $data['is_registration'] ?? 0
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar seção: " . $e->getMessage());
            return false;
        }
    }

    public function updateSection($id, $data)
    {
        try {
            $sql = "UPDATE planning_template_sections
                    SET title = :title, description = :description, section_type = :section_type,
                        sort_order = :sort_order, is_registration = :is_registration
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':description' => $data['description'] ?? null,
                ':section_type' => $data['section_type'] ?? 'default',
                ':sort_order' => $data['sort_order'] ?? 0,
                ':is_registration' => $data['is_registration'] ?? 0
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar seção: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSection($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_template_sections WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar seção: " . $e->getMessage());
            return false;
        }
    }

    // --- Fields ---

    public function getFields($sectionId)
    {
        try {
            $sql = "SELECT * FROM planning_template_fields WHERE section_id = ? ORDER BY sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sectionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar campos: " . $e->getMessage());
            return [];
        }
    }

    public function findField($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_template_fields WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar campo: " . $e->getMessage());
            return null;
        }
    }

    public function createField($data)
    {
        try {
            $sql = "INSERT INTO planning_template_fields (section_id, field_type, label, description, options_json, is_required, sort_order)
                    VALUES (:section_id, :field_type, :label, :description, :options_json, :is_required, :sort_order)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':section_id' => $data['section_id'],
                ':field_type' => $data['field_type'] ?? 'text',
                ':label' => $data['label'],
                ':description' => $data['description'] ?? null,
                ':options_json' => $data['options_json'] ?? null,
                ':is_required' => $data['is_required'] ?? 0,
                ':sort_order' => $data['sort_order'] ?? 0
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar campo: " . $e->getMessage());
            return false;
        }
    }

    public function updateField($id, $data)
    {
        try {
            $sql = "UPDATE planning_template_fields
                    SET field_type = :field_type, label = :label, description = :description,
                        options_json = :options_json, is_required = :is_required, sort_order = :sort_order
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':field_type' => $data['field_type'] ?? 'text',
                ':label' => $data['label'],
                ':description' => $data['description'] ?? null,
                ':options_json' => $data['options_json'] ?? null,
                ':is_required' => $data['is_required'] ?? 0,
                ':sort_order' => $data['sort_order'] ?? 0
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar campo: " . $e->getMessage());
            return false;
        }
    }

    public function deleteField($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_template_fields WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar campo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get template with all sections and fields nested
     */
    public function getWithSectionsAndFields($templateId)
    {
        $template = $this->find($templateId);
        if (!$template) return null;

        $sections = $this->getSections($templateId);
        foreach ($sections as &$section) {
            $section['fields'] = $this->getFields($section['id']);
        }
        $template['sections'] = $sections;
        return $template;
    }
}
