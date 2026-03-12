<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class SupportMaterialFolder
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todas as pastas
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM support_material_folders ORDER BY sort_order ASC, name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar pastas de material: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar pasta por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT f.*, pf.name as parent_name
                    FROM support_material_folders f
                    LEFT JOIN support_material_folders pf ON f.parent_id = pf.id
                    WHERE f.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pasta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Retorna arvore de pastas (recursiva)
     */
    public function getTree()
    {
        $all = $this->all();
        return $this->buildTree($all, null);
    }

    /**
     * Construir arvore recursivamente
     */
    private function buildTree(array $items, $parentId)
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['children'] = $this->buildTree($items, $item['id']);
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * Filhos diretos de uma pasta
     */
    public function children($parentId)
    {
        try {
            $sql = "SELECT * FROM support_material_folders
                    WHERE parent_id = :parent_id
                    ORDER BY sort_order ASC, name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':parent_id' => $parentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar subpastas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Pastas raiz (sem parent)
     */
    public function roots()
    {
        try {
            $sql = "SELECT * FROM support_material_folders
                    WHERE parent_id IS NULL
                    ORDER BY sort_order ASC, name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pastas raiz: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Breadcrumb: caminho da pasta ate a raiz
     */
    public function getBreadcrumb($id)
    {
        $breadcrumb = [];
        $current = $this->find($id);
        while ($current) {
            array_unshift($breadcrumb, $current);
            if ($current['parent_id']) {
                $current = $this->find($current['parent_id']);
            } else {
                break;
            }
        }
        return $breadcrumb;
    }

    /**
     * Contar materiais por pasta
     */
    public function countMaterials($folderId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM support_materials WHERE folder_id = :folder_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':folder_id' => $folderId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar materiais: " . $e->getMessage());
            return 0;
        }
    }
}
