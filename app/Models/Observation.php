<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class Observation
{
    protected $db;

    /**
     * Campos dos eixos pedagogicos
     */
    protected $axisFields = [
        'observation_general',
        'axis_movement',
        'axis_manual',
        'axis_music',
        'axis_stories',
        'axis_pca'
    ];

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todas as observacoes
     */
    public function all()
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar observacoes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar observacoes por usuario (professor)
     */
    public function allByUser($userId)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.user_id = ?
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar observacoes por usuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar observacao por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           s.school_id,
                           COALESCE(sch.pca_enabled, 0) as school_pca_enabled,
                           COALESCE(uf.name, '') as finalized_by_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    LEFT JOIN schools sch ON s.school_id = sch.id
                    LEFT JOIN users uf ON o.finalized_by = uf.id
                    WHERE o.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observacao: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @deprecated Use createWithAxes() instead.
     * Legacy fields (category, description, observation_date) kept for backward compatibility only.
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO observations (student_id, user_id, category, title, description, observation_date, observation_general, axis_movement, axis_manual, axis_music, axis_stories, axis_pca, created_at, updated_at)
                    VALUES (:student_id, :user_id, :category, :title, :description, :observation_date, :observation_general, :axis_movement, :axis_manual, :axis_music, :axis_stories, :axis_pca, :created_at, :updated_at)";

            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');

            return $stmt->execute([
                ':student_id' => $data['student_id'],
                ':user_id' => $data['user_id'],
                ':category' => $data['category'] ?? 'Geral',
                ':title' => $data['title'],
                ':description' => $data['description'] ?? '',
                ':observation_date' => $data['observation_date'] ?? date('Y-m-d'),
                ':observation_general' => $data['observation_general'] ?? '',
                ':axis_movement' => $data['axis_movement'] ?? '',
                ':axis_manual' => $data['axis_manual'] ?? '',
                ':axis_music' => $data['axis_music'] ?? '',
                ':axis_stories' => $data['axis_stories'] ?? '',
                ':axis_pca' => $data['axis_pca'] ?? '',
                ':created_at' => $now,
                ':updated_at' => $now
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao criar observacao: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Criar observacao com eixos pedagogicos
     */
    public function createWithAxes($data)
    {
        try {
            $sql = "INSERT INTO observations (
                        student_id, user_id, semester, year, status,
                        content, title,
                        observation_general, axis_movement, axis_manual,
                        axis_music, axis_stories, axis_pca,
                        created_at, updated_at
                    ) VALUES (
                        :student_id, :user_id, :semester, :year, 'in_progress',
                        :content, :title,
                        :observation_general, :axis_movement, :axis_manual,
                        :axis_music, :axis_stories, :axis_pca,
                        :created_at, :updated_at
                    )";

            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');

            $stmt->execute([
                ':student_id' => $data['student_id'],
                ':user_id' => $data['user_id'],
                ':semester' => $data['semester'],
                ':year' => $data['year'],
                ':content' => $data['observation_general'] ?? '',
                ':title' => 'Observacao ' . ($data['semester'] ?? '') . 'o Sem/' . ($data['year'] ?? ''),
                ':observation_general' => $data['observation_general'] ?? '',
                ':axis_movement' => $data['axis_movement'] ?? '',
                ':axis_manual' => $data['axis_manual'] ?? '',
                ':axis_music' => $data['axis_music'] ?? '',
                ':axis_stories' => $data['axis_stories'] ?? '',
                ':axis_pca' => $data['axis_pca'] ?? '',
                ':created_at' => $now,
                ':updated_at' => $now
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar observacao com eixos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar observacao (compatibilidade)
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE observations
                    SET student_id = :student_id,
                        user_id = :user_id,
                        category = :category,
                        title = :title,
                        description = :description,
                        observation_date = :observation_date,
                        updated_at = :updated_at
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':student_id' => $data['student_id'],
                ':user_id' => $data['user_id'],
                ':category' => $data['category'] ?? 'Geral',
                ':title' => $data['title'],
                ':description' => $data['description'] ?? '',
                ':observation_date' => $data['observation_date'] ?? date('Y-m-d'),
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar observacao: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar observacao com eixos pedagogicos
     */
    public function updateWithAxes($id, $data)
    {
        try {
            $sql = "UPDATE observations
                    SET student_id = :student_id,
                        semester = :semester,
                        year = :year,
                        observation_general = :observation_general,
                        axis_movement = :axis_movement,
                        axis_manual = :axis_manual,
                        axis_music = :axis_music,
                        axis_stories = :axis_stories,
                        axis_pca = :axis_pca,
                        updated_at = :updated_at
                    WHERE id = :id AND status = 'in_progress'";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':student_id' => $data['student_id'],
                ':semester' => $data['semester'],
                ':year' => $data['year'],
                ':observation_general' => $data['observation_general'] ?? '',
                ':axis_movement' => $data['axis_movement'] ?? '',
                ':axis_manual' => $data['axis_manual'] ?? '',
                ':axis_music' => $data['axis_music'] ?? '',
                ':axis_stories' => $data['axis_stories'] ?? '',
                ':axis_pca' => $data['axis_pca'] ?? '',
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar observacao com eixos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar campo individual (auto-save)
     */
    public function updateField($id, $field, $value)
    {
        // Validar que o campo e permitido
        $allowedFields = array_merge($this->axisFields, ['semester', 'year', 'student_id']);
        if (!in_array($field, $allowedFields)) {
            error_log("Campo nao permitido para auto-save: " . $field);
            return false;
        }

        try {
            $sql = "UPDATE observations
                    SET {$field} = :value, updated_at = :updated_at
                    WHERE id = :id AND status = 'in_progress'";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':value' => $value,
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar campo {$field}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Finalizar observacao
     */
    public function finalize($id, $userId)
    {
        try {
            $sql = "UPDATE observations
                    SET status = 'finalized',
                        finalized_at = :finalized_at,
                        finalized_by = :finalized_by,
                        updated_at = :updated_at
                    WHERE id = :id AND status = 'in_progress'";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':finalized_at' => date('Y-m-d H:i:s'),
                ':finalized_by' => $userId,
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao finalizar observacao: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reabrir observacao (coordenador)
     */
    public function reopen($id)
    {
        try {
            $sql = "UPDATE observations
                    SET status = 'in_progress',
                        finalized_at = NULL,
                        finalized_by = NULL,
                        updated_at = :updated_at
                    WHERE id = :id AND status = 'finalized'";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao reabrir observacao: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar observacao por aluno e semestre
     */
    public function findByStudentAndSemester($studentId, $semester, $year)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.student_id = ? AND o.semester = ? AND o.year = ?
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$studentId, $semester, $year]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observacoes por semestre: " . $e->getMessage());
            return [];
        }
    }

    public function countForStudentSemester($studentId, $semester, $year, $excludeId = null): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM observations WHERE student_id = ? AND semester = ? AND year = ?";
            $params = [$studentId, $semester, $year];

            if ($excludeId !== null) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar observacoes por aluno/semestre: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Listar com filtros avancados
     */
    public function allFiltered($filters = [], $userId = null, $roleRestrict = false)
    {
        try {
            $where = [];
            $params = [];

            // Restricao por role (professor ve apenas suas)
            if ($roleRestrict && $userId) {
                $where[] = "o.user_id = ?";
                $params[] = $userId;
            }

            // Filtro por aluno
            if (!empty($filters['student_id'])) {
                $where[] = "o.student_id = ?";
                $params[] = $filters['student_id'];
            }

            // Filtro por semestre
            if (!empty($filters['semester'])) {
                $where[] = "o.semester = ?";
                $params[] = $filters['semester'];
            }

            // Filtro por ano
            if (!empty($filters['year'])) {
                $where[] = "o.year = ?";
                $params[] = $filters['year'];
            }

            // Filtro por status
            if (!empty($filters['status'])) {
                $where[] = "o.status = ?";
                $params[] = $filters['status'];
            }

            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id";

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            $sql .= " ORDER BY o.year DESC, o.semester DESC, s.name ASC, o.created_at DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar observacoes filtradas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Deletar observacao
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM observations WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar observacao: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar observacoes por aluno
     */
    public function findByStudent($student_id)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name
                    FROM observations o
                    LEFT JOIN students s ON o.student_id = s.id
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.student_id = ?
                    ORDER BY o.year DESC, o.semester DESC, o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$student_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observacoes por aluno: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Alias para compatibilidade com codigo existente
     */
    public function allByStudent($studentId)
    {
        return $this->findByStudent($studentId);
    }

    /**
     * Contar total de observacoes
     */
    public function countTotal()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM observations";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar observacoes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Buscar observacoes recentes
     */
    public function recentObservations($limit = 10)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    ORDER BY o.created_at DESC
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observacoes recentes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar observacoes por tipo/categoria
     */
    public function findByType($category)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.category = ?
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observacoes por categoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar observacoes por periodo
     */
    public function findByDateRange($dateFrom, $dateTo)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuario desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.observation_date BETWEEN ? AND ?
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$dateFrom, $dateTo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observacoes por periodo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Contar observacoes por categoria
     */
    public function countByCategory()
    {
        try {
            $sql = "SELECT category, COUNT(*) as total
                    FROM observations
                    GROUP BY category
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao contar observacoes por categoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Alias para compatibilidade (deprecated - use countByCategory)
     */
    public function countByType()
    {
        return $this->countByCategory();
    }

    /**
     * Verificar se ja existe observacao para aluno/semestre/ano
     */
    public function existsForStudentSemester($studentId, $semester, $year, $excludeId = null)
    {
        try {
            $sql = "SELECT id FROM observations
                    WHERE student_id = ? AND semester = ? AND year = ?";
            $params = [$studentId, $semester, $year];

            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao verificar existencia: " . $e->getMessage());
            return null;
        }
    }

    public function compileSemesterText($studentId, $semester, $year): string
    {
        $observations = $this->findByStudentAndSemester($studentId, $semester, $year);
        usort($observations, static function (array $a, array $b): int {
            return strcmp((string) ($a['created_at'] ?? ''), (string) ($b['created_at'] ?? ''));
        });

        $parts = [];

        foreach ($observations as $observation) {
            $compiled = $this->compileObservationText($observation);
            if ($compiled !== '') {
                $parts[] = $compiled;
            }
        }

        return implode("\n\n", $parts);
    }

    public function compileObservationText(array $observation): string
    {
        $parts = [];

        $general = $this->normalizeAxisValue($observation['observation_general'] ?? '');
        if ($general !== '') {
            $parts[] = $general;
        }

        $axes = [
            'axis_movement' => 'Atividade de Movimento',
            'axis_manual' => 'Atividade Manual',
            'axis_music' => 'Atividade Musical',
            'axis_stories' => 'Atividade de Contos',
            'axis_pca' => 'Programa Comunicacao Ativa (PCA)',
        ];

        foreach ($axes as $field => $label) {
            $text = $this->normalizeAxisValue($observation[$field] ?? '');
            if ($text !== '') {
                $parts[] = $text;
            }
        }

        return trim(implode(' ', $parts));
    }

    /**
     * Listar observacoes agrupadas por aluno
     */
    public function allGroupedByStudent($filters = [], $userId = null, $roleRestrict = false)
    {
        try {
            $where = [];
            $params = [];

            if ($roleRestrict && $userId) {
                $where[] = "o.user_id = ?";
                $params[] = $userId;
            }
            if (!empty($filters['student_id'])) {
                $where[] = "o.student_id = ?";
                $params[] = $filters['student_id'];
            }
            if (!empty($filters['semester'])) {
                $where[] = "o.semester = ?";
                $params[] = $filters['semester'];
            }
            if (!empty($filters['year'])) {
                $where[] = "o.year = ?";
                $params[] = $filters['year'];
            }
            if (!empty($filters['status'])) {
                $where[] = "o.status = ?";
                $params[] = $filters['status'];
            }

            $whereClause = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

            $sql = "SELECT
                        s.id as student_id,
                        s.name as student_name,
                        MAX(o.semester) as semester,
                        MAX(o.year) as year,
                        COUNT(o.id) as observation_count,
                        MAX(o.id) as latest_observation_id,
                        MAX(CASE WHEN o.status = 'in_progress' THEN o.id ELSE NULL END) as latest_editable_observation_id,
                        MAX(o.updated_at) as last_updated,
                        CASE
                            WHEN SUM(CASE WHEN o.status = 'in_progress' THEN 1 ELSE 0 END) > 0
                            THEN 'in_progress'
                            ELSE 'finalized'
                        END as aggregated_status
                    FROM observations o
                    LEFT JOIN students s ON o.student_id = s.id
                    {$whereClause}
                    GROUP BY s.id, s.name
                    ORDER BY s.name ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar observacoes agrupadas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Alterar status de todas as observacoes de um aluno
     */
    public function setStatusForStudent(int $studentId, int $semester, int $year, string $status, int $userId): bool
    {
        try {
            $now = date('Y-m-d H:i:s');

            if ($status === 'finalized') {
                $sql = "UPDATE observations
                        SET status = 'finalized',
                            finalized_at = :now,
                            finalized_by = :user_id,
                            updated_at = :updated_at
                        WHERE student_id = :student_id
                          AND semester = :semester
                          AND year = :year
                          AND status = 'in_progress'";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    ':now' => $now,
                    ':user_id' => $userId,
                    ':updated_at' => $now,
                    ':student_id' => $studentId,
                    ':semester' => $semester,
                    ':year' => $year,
                ]);
            } else {
                $sql = "UPDATE observations
                        SET status = 'in_progress',
                            finalized_at = NULL,
                            finalized_by = NULL,
                            updated_at = :updated_at
                        WHERE student_id = :student_id
                          AND semester = :semester
                          AND year = :year
                          AND status = 'finalized'";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    ':updated_at' => $now,
                    ':student_id' => $studentId,
                    ':semester' => $semester,
                    ':year' => $year,
                ]);
            }
        } catch (PDOException $e) {
            error_log("Erro ao alterar status por aluno: " . $e->getMessage());
            return false;
        }
    }

    private function normalizeAxisValue($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $decoded = json_decode((string) $value, true);
        if (is_array($decoded)) {
            $items = array_values(array_filter(array_map(static function ($item) {
                return trim((string) $item);
            }, $decoded), static function ($item) {
                return $item !== '';
            }));

            return implode(' ', $items);
        }

        return trim((string) $value);
    }
}
