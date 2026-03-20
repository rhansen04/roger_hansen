<?php
/**
 * ============================================================
 * Hansen Educacional - Bateria Completa de Testes (Revisão 04)
 * ============================================================
 *
 * Testa TODOS os módulos, formulários, workflows e validações.
 * Gerado por auditoria de código — identifica defeitos reais.
 *
 * USO:
 *   php tests/test_forms_completo.php
 *   php tests/test_forms_completo.php --modulo=observations
 *   php tests/test_forms_completo.php --modulo=planning
 *   php tests/test_forms_completo.php --verbose
 *
 * MÓDULOS COBERTOS:
 *   01. Banco de dados + tabelas obrigatórias
 *   02. Auth — login, registro, reset de senha
 *   03. Usuários — CRUD, validação de papéis
 *   04. Escolas — CRUD, validação de campos
 *   05. Turmas — CRUD, add/remove alunos
 *   06. Alunos — CRUD, data de nascimento, foto
 *   07. Observações pedagógicas — criação, finalização, reabertura, auto-save
 *   08. Planejamento — submissão, rotina, registros de período
 *   09. Parecer descritivo — criação, compilação, exportação
 *   10. Portfólios — criação, fotos por eixo, finalização
 *   11. Feedback de coordenação — comentários por tipo de conteúdo
 *   12. Banco de imagens — upload, mover, legenda
 *   13. Cursos — CRUD, módulos, seções, aulas
 *   14. Quiz — criação, questões, submissão, notas
 *   15. Matrículas — criar, ativar, desativar
 *   16. Notificações — marcar como lida
 *   17. Modelos — integridade de schema
 *   18. Rotas — todos os endpoints registrados
 *   19. Views — existência de todos os arquivos de template
 *   20. Workflows — fluxos completos de aprovação/revisão
 *
 * SAÍDA:
 *   [PASS] - teste passou
 *   [FAIL] - teste falhou (defeito confirmado)
 *   [WARN] - comportamento suspeito / possível defeito
 *   [SKIP] - teste ignorado (dependência não disponível)
 * ============================================================
 */

// ===================== BOOTSTRAP =====================

error_reporting(E_ALL);
ini_set('display_errors', 0); // capturamos erros manualmente
ini_set('log_errors', 0);

session_start();
$_SESSION = [];

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

// Carregar vendor (mPDF, etc.)
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerAutoload)) require $composerAutoload;

use App\Core\Database\Connection;
use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Classroom;
use App\Models\Observation;
use App\Models\PlanningSubmission;
use App\Models\PlanningPeriodRecord;
use App\Models\DescriptiveReport;
use App\Models\Portfolio;
use App\Models\CoordinatorComment;
use App\Models\ImageBank;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Notification;

// ===================== ARGS =====================
$args = getopt('', ['modulo:', 'verbose']);
$filtroModulo = $args['modulo'] ?? null;
$verbose = isset($args['verbose']);

// ===================== TEST RUNNER =====================

$passed = 0;
$failed = 0;
$warned = 0;
$skipped = 0;
$errors = [];
$warns = [];
$currentSection = '';
$cleanupIds = [
    'users' => [],
    'students' => [],
    'schools' => [],
    'classrooms' => [],
    'observations' => [],
    'descriptive_reports' => [],
    'portfolios' => [],
    'planning_submissions' => [],
    'enrollments' => [],
    'courses' => [],
];

function test(string $name, bool $condition, string &$currentSection): void {
    global $passed, $failed, $errors;
    if ($condition) {
        echo "  \033[32m[PASS]\033[0m $name\n";
        $passed++;
    } else {
        echo "  \033[31m[FAIL]\033[0m $name\n";
        $failed++;
        $errors[] = "[{$currentSection}] {$name}";
    }
}

function warn(string $name, bool $condition, string &$currentSection): void {
    global $warned, $warns;
    if (!$condition) {
        echo "  \033[33m[WARN]\033[0m $name\n";
        $warned++;
        $warns[] = "[{$currentSection}] {$name}";
    } else {
        echo "  \033[32m[PASS]\033[0m $name\n";
        global $passed;
        $passed++;
    }
}

function skip(string $name, string $reason): void {
    global $skipped;
    echo "  \033[90m[SKIP]\033[0m $name — $reason\n";
    $skipped++;
}

function section(string $title, ?string $modulo = null): bool {
    global $filtroModulo, $currentSection;
    if ($filtroModulo !== null && $modulo !== null && $modulo !== $filtroModulo) {
        return false;
    }
    $currentSection = $title;
    echo "\n\033[36m╔══ $title ══\033[0m\n";
    return true;
}

function tableExists(PDO $db, string $table): bool {
    $stmt = $db->query("SHOW TABLES LIKE '{$table}'");
    return $stmt->rowCount() > 0;
}

function columnExists(PDO $db, string $table, string $column): bool {
    $stmt = $db->query("DESCRIBE `{$table}`");
    $cols = array_column($stmt->fetchAll(), 'Field');
    return in_array($column, $cols);
}

// ===================== HEADER =====================
echo "\n\033[1m╔═══════════════════════════════════════════════════╗\033[0m\n";
echo "\033[1m║  HANSEN EDUCACIONAL — AUDITORIA COMPLETA v4.0     ║\033[0m\n";
echo "\033[1m╚═══════════════════════════════════════════════════╝\033[0m\n";
echo " Data: " . date('d/m/Y H:i:s') . "\n";
if ($filtroModulo) echo " Módulo: \033[33m{$filtroModulo}\033[0m\n";

// ===================== 01. BANCO DE DADOS =====================

if (section("01. Banco de Dados — Conexão e Tabelas", 'db')) {
    try {
        $db = Connection::getInstance();
        test("Conexão MySQL ativa", $db !== null, $currentSection);
    } catch (Exception $e) {
        test("Conexão MySQL ativa", false, $currentSection);
        echo "\n  \033[31mFATAL: {$e->getMessage()}\033[0m\n";
        echo "  Impossível continuar sem banco de dados.\n\n";
        exit(1);
    }

    // Garantir que tabelas auto-criadas por models já existam antes da verificação
    new \App\Models\CoordinatorComment();

    $requiredTables = [
        'users', 'schools', 'classrooms', 'classroom_students',
        'students', 'courses', 'sections', 'lessons',
        'enrollments', 'course_progress', 'video_progress',
        'quizzes', 'quiz_questions', 'quiz_answers', 'quiz_attempts',
        'observations', 'planning_templates', 'planning_template_sections',
        'planning_template_fields', 'planning_submissions',
        'planning_submission_answers', 'planning_daily_entries',
        'planning_daily_routines', 'descriptive_reports', 'portfolios',
        'coordinator_comments', 'image_folders', 'image_bank',
        'support_material_folders', 'support_materials',
        'notifications', 'notification_settings',
        'contacts', 'password_resets', 'course_messages',
    ];
    foreach ($requiredTables as $table) {
        test("Tabela '{$table}' existe", tableExists($db, $table), $currentSection);
    }

    // Colunas críticas
    $criticalColumns = [
        'observations' => ['axis_movement', 'axis_manual', 'axis_music', 'axis_stories', 'axis_pca', 'observation_general', 'status', 'finalized_at'],
        'planning_submissions' => ['template_id', 'teacher_id', 'classroom_id', 'period_start', 'period_end', 'status'],
        'descriptive_reports' => ['student_id', 'semester', 'year', 'student_text', 'student_text_edited', 'status', 'axis_photos'],
        'portfolios' => ['classroom_id', 'semester', 'year', 'status', 'axis_movement_photos', 'axis_pca_photos'],
        'coordinator_comments' => ['coordinator_id', 'content_type', 'content_id', 'comment'],
        'image_bank' => ['folder_id', 'filename', 'uploaded_by', 'caption'],
        'users' => ['name', 'email', 'password', 'role'],
        'students' => ['name', 'birth_date', 'school_id', 'photo_url'],
    ];
    foreach ($criticalColumns as $table => $columns) {
        if (tableExists($db, $table)) {
            foreach ($columns as $col) {
                test("  Coluna {$table}.{$col} existe", columnExists($db, $table, $col), $currentSection);
            }
        }
    }
}

// ===================== 02. AUTH =====================

if (section("02. Auth — Login, Registro e Reset de Senha", 'auth')) {
    $userModel = new User();

    // Admin seed
    $admin = $userModel->findByEmail('admin@hansen.com');
    test("Usuário admin@hansen.com existe (seed)", $admin !== null, $currentSection);
    if ($admin) {
        test("Admin role = 'admin'", $admin['role'] === 'admin', $currentSection);
        test("Senha admin é hash bcrypt", strpos($admin['password'], '$2y$') === 0, $currentSection);
    }

    // Criação de usuário de teste
    $testEmail = 'teste_auto_' . time() . '@hanseneducacional.test';
    $uid = $userModel->create([
        'name' => 'Usuário Teste Auto',
        'email' => $testEmail,
        'password' => 'Senha@123',
        'role' => 'professor',
    ]);
    test("Criar usuário com dados válidos", (bool)$uid, $currentSection);

    $createdUser = $userModel->findByEmail($testEmail);
    test("findByEmail() encontra usuário criado", $createdUser !== null, $currentSection);
    if ($createdUser) {
        test("Senha armazenada como hash (não texto puro)", strpos($createdUser['password'], 'Senha@123') === false, $currentSection);
        test("Hash bcrypt válido", strpos($createdUser['password'], '$2y$') === 0, $currentSection);
        test("password_verify() confirma senha", password_verify('Senha@123', $createdUser['password']), $currentSection);
        $cleanupIds['users'][] = $createdUser['id'];
    }

    // Email duplicado
    $dup = $userModel->create([
        'name' => 'Duplicado',
        'email' => $testEmail,
        'password' => 'outra',
        'role' => 'professor',
    ]);
    test("Email duplicado retorna false (sem crash)", $dup === false, $currentSection);

    // Validação de role inválido
    warn(
        "Role inválido rejeitado no model (não aceita 'hacker')",
        $userModel->create(['name' => 'X', 'email' => 'x_' . time() . '@test.com', 'password' => '123456', 'role' => 'hacker']) === false,
        $currentSection
    );

    // Password reset
    $resetToken = bin2hex(random_bytes(32));
    if ($createdUser ?? false) {
        $stmt = $db->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $ok = $stmt->execute([$testEmail, $resetToken]);
        test("Inserir token de reset", $ok, $currentSection);

        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $stmt->execute([$resetToken]);
        $rec = $stmt->fetch();
        test("Token válido encontrado (dentro de 1h)", $rec !== false && ($rec['email'] ?? '') === $testEmail, $currentSection);

        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = 'token_invalido_xyz_99' AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $stmt->execute();
        test("Token inválido não encontrado", $stmt->fetch() === false, $currentSection);

        $db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$testEmail]);
    }
}

// ===================== 03. USUÁRIOS =====================

if (section("03. Usuários — CRUD e Validação", 'users')) {
    $userModel = new User();
    $all = $userModel->all();
    test("all() retorna array", is_array($all), $currentSection);
    test("all() tem ao menos 1 usuário", count($all) >= 1, $currentSection);

    foreach (['admin', 'professor', 'coordenador'] as $role) {
        $count = $userModel->countByRole($role);
        warn("countByRole('{$role}') >= 0", $count >= 0, $currentSection);
    }

    // Testar update sem senha
    $admin = $userModel->findByEmail('admin@hansen.com');
    if ($admin) {
        $updated = $userModel->update($admin['id'], [
            'name' => $admin['name'],
            'email' => $admin['email'],
            'role' => $admin['role'],
            // sem password — não deve apagar a senha
        ]);
        $afterUpdate = $userModel->findByEmail('admin@hansen.com');
        test("update() sem senha não apaga o hash existente", !empty($afterUpdate['password'] ?? ''), $currentSection);
    }
}

// ===================== 04. ESCOLAS =====================

if (section("04. Escolas — CRUD", 'schools')) {
    if (!class_exists('App\Models\School')) {
        skip("School model", "classe não encontrada");
    } else {
        $schoolModel = new School();
        $schools = $schoolModel->all();
        test("all() retorna array", is_array($schools), $currentSection);

        // Criar escola de teste
        $schoolId = $schoolModel->create([
            'name' => 'Escola Teste Auto ' . time(),
            'city' => 'São Paulo',
            'state' => 'SP',
            'address' => 'Rua Teste, 123',
            'contact_person' => 'Fulano',
            'phone' => '11999999999',
            'email' => 'escola' . time() . '@teste.com',
            'contract_start_date' => '2026-01-01',
            'contract_end_date' => '2026-12-31',
            'status' => 'active',
        ]);
        test("create() escola com dados válidos", (bool)$schoolId, $currentSection);

        if ($schoolId) {
            $school = $schoolModel->find($schoolId);
            test("find() retorna escola criada", $school !== null, $currentSection);

            $updated = $schoolModel->update($schoolId, array_merge($school, ['name' => 'Escola Atualizada Auto']));
            test("update() escola", (bool)$updated, $currentSection);

            $after = $schoolModel->find($schoolId);
            test("Nome atualizado corretamente", ($after['name'] ?? '') === 'Escola Atualizada Auto', $currentSection);

            $cleanupIds['schools'][] = $schoolId;
        }

        // Validação: email inválido deve ser rejeitado
        warn(
            "create() com email inválido retorna false",
            $schoolModel->create(['name' => 'X', 'email' => 'nao-e-email', 'status' => 'active']) === false,
            $currentSection
        );

        // Validação: contrato com datas invertidas
        warn(
            "create() com data_inicio > data_fim retorna false",
            $schoolModel->create(['name' => 'X', 'contract_start_date' => '2026-12-31', 'contract_end_date' => '2026-01-01', 'status' => 'active']) === false,
            $currentSection
        );
    }
}

// ===================== 05. TURMAS =====================

if (section("05. Turmas — CRUD e Matrícula de Alunos", 'classrooms')) {
    if (!class_exists('App\Models\Classroom')) {
        skip("Classroom model", "classe não encontrada");
    } else {
        $classroomModel = new Classroom();
        $classrooms = $classroomModel->all();
        test("all() retorna array", is_array($classrooms), $currentSection);

        // Encontrar uma escola e um professor existentes
        $stmt = $db->query("SELECT id FROM schools LIMIT 1");
        $schoolRow = $stmt->fetch();
        $stmt2 = $db->query("SELECT id FROM users WHERE role='professor' LIMIT 1");
        $profRow = $stmt2->fetch();

        if ($schoolRow && $profRow) {
            $classroomId = $classroomModel->create([
                'name' => 'Turma Teste Auto ' . time(),
                'school_id' => $schoolRow['id'],
                'teacher_id' => $profRow['id'],
                'age_group' => '3-6',
                'period' => 'morning',
                'school_year' => 2026,
            ]);
            test("create() turma com dados válidos", (bool)$classroomId, $currentSection);

            if ($classroomId) {
                $classroom = $classroomModel->find($classroomId);
                test("find() retorna turma criada", $classroom !== null, $currentSection);

                // Testar toggle status
                $toggled = $classroomModel->toggleStatus($classroomId);
                test("toggleStatus() retorna resultado", $toggled !== false, $currentSection);

                $cleanupIds['classrooms'][] = $classroomId;
            }
        } else {
            skip("CRUD de turma", "nenhuma escola ou professor disponível no banco");
        }

        // Validação: teacher_id de usuário que não é professor
        $stmt = $db->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
        $adminRow = $stmt->fetch();
        if ($adminRow && ($schoolRow ?? false)) {
            warn(
                "create() com teacher_id de admin (não professor) deve ser rejeitado",
                $classroomModel->create(['name' => 'X', 'school_id' => $schoolRow['id'], 'teacher_id' => $adminRow['id']]) === false,
                $currentSection
            );
        }
    }
}

// ===================== 06. ALUNOS =====================

if (section("06. Alunos — CRUD e Validação de Dados", 'students')) {
    $studentModel = new Student();
    $all = $studentModel->all();
    test("all() retorna array", is_array($all), $currentSection);

    $stmt = $db->query("SELECT id FROM schools LIMIT 1");
    $schoolRow = $stmt->fetch();

    if ($schoolRow) {
        // Criar aluno válido
        $studentId = $studentModel->create([
            'name' => 'Aluno Teste Auto ' . time(),
            'birth_date' => '2020-06-15',
            'school_id' => $schoolRow['id'],
        ]);
        test("create() aluno com dados válidos", (bool)$studentId, $currentSection);

        if ($studentId) {
            $student = $studentModel->find($studentId);
            test("find() retorna aluno criado", $student !== null, $currentSection);
            test("Aluno tem birth_date correto", ($student['birth_date'] ?? '') !== '', $currentSection);

            // Cálculo de idade
            try {
                $bday = new \DateTime($student['birth_date']);
                $age = (new \DateTime())->diff($bday)->y;
                test("Idade calculável a partir de birth_date", $age >= 0 && $age < 120, $currentSection);
            } catch (\Exception $e) {
                test("Birth_date não crashou DateTime", false, $currentSection);
            }

            $cleanupIds['students'][] = $studentId;
        }

        // Validação: aluno sem nome deve falhar
        warn(
            "create() sem nome retorna false",
            $studentModel->create(['birth_date' => '2020-01-01', 'school_id' => $schoolRow['id']]) === false,
            $currentSection
        );

        // Validação: data de nascimento inválida
        warn(
            "create() com birth_date inválido retorna false",
            $studentModel->create(['name' => 'X', 'birth_date' => 'nao-e-data', 'school_id' => $schoolRow['id']]) === false,
            $currentSection
        );

        // Validação: data de nascimento futura
        warn(
            "create() com birth_date no futuro retorna false",
            $studentModel->create(['name' => 'X', 'birth_date' => '2099-01-01', 'school_id' => $schoolRow['id']]) === false,
            $currentSection
        );
    } else {
        skip("CRUD de alunos", "nenhuma escola no banco");
    }
}

// ===================== 07. OBSERVAÇÕES =====================

if (section("07. Observações Pedagógicas — Fluxo Completo", 'observations')) {
    $obsModel = new Observation();

    $stmt = $db->query("SELECT id FROM students LIMIT 1");
    $studentRow = $stmt->fetch();
    $stmt2 = $db->query("SELECT id FROM users WHERE role='professor' LIMIT 1");
    $profRow = $stmt2->fetch();

    if ($studentRow && $profRow) {
        // Criar observação
        $obsId = $obsModel->createWithAxes([
            'student_id' => $studentRow['id'],
            'user_id' => $profRow['id'],
            'semester' => 1,
            'year' => 2026,
            'observation_general' => 'Observação geral de teste auto.',
            'axis_movement' => 'Movimento adequado para a faixa etária.',
            'axis_manual' => 'Coordenação motora fina em desenvolvimento.',
            'axis_music' => 'Participativo nas atividades musicais.',
            'axis_stories' => 'Demonstra interesse em histórias.',
            'axis_pca' => 'Boa interação com colegas.',
        ]);
        test("createWithAxes() observação com 6 eixos", (bool)$obsId, $currentSection);

        if ($obsId) {
            $obs = $obsModel->find($obsId);
            test("find() retorna observação criada", $obs !== null, $currentSection);
            test("Status inicial = 'in_progress'", ($obs['status'] ?? '') === 'in_progress', $currentSection);
            test("Eixo movement salvo corretamente", !empty($obs['axis_movement'] ?? ''), $currentSection);
            test("Eixo pca salvo corretamente", !empty($obs['axis_pca'] ?? ''), $currentSection);

            // Auto-save de campo
            $saved = $obsModel->updateField($obsId, 'observation_general', 'Texto atualizado via auto-save.');
            test("updateField() auto-save de observation_general", (bool)$saved, $currentSection);

            // Tentar updateField com campo não permitido (segurança)
            $injected = $obsModel->updateField($obsId, 'status', 'INJETADO');
            warn(
                "updateField() com campo não permitido é bloqueado pelo whitelist",
                $injected === false,
                $currentSection
            );

            // Finalizar
            $finalized = $obsModel->finalize($obsId, $profRow['id']);
            test("finalize() muda status para 'finalized'", (bool)$finalized, $currentSection);

            $finObs = $obsModel->find($obsId);
            test("Status após finalize = 'finalized'", ($finObs['status'] ?? '') === 'finalized', $currentSection);
            test("finalized_by preenchido", !empty($finObs['finalized_by'] ?? ''), $currentSection);
            test("finalized_at preenchido", !empty($finObs['finalized_at'] ?? ''), $currentSection);

            // Tentativa de editar observação finalizada
            $editBlocked = $obsModel->updateField($obsId, 'observation_general', 'Tentativa de edição pós-finalização');
            warn(
                "updateField() em observação finalizada deve ser bloqueado",
                $editBlocked === false,
                $currentSection
            );

            // Reabrir
            $reopened = $obsModel->reopen($obsId);
            test("reopen() muda status de volta para 'in_progress'", (bool)$reopened, $currentSection);

            $reopenedObs = $obsModel->find($obsId);
            test("Status após reopen = 'in_progress'", ($reopenedObs['status'] ?? '') === 'in_progress', $currentSection);

            // Duplicata no mesmo semestre/ano
            $duplicate = $obsModel->findByStudentAndSemester($studentRow['id'], 1, 2026);
            test("findByStudentAndSemester() encontra observação existente", $duplicate !== null, $currentSection);

            $cleanupIds['observations'][] = $obsId;
        }
    } else {
        skip("Observações", "nenhum aluno ou professor disponível");
    }

    // Observação sem student_id deve falhar
    warn(
        "createWithAxes() sem student_id retorna false",
        $obsModel->createWithAxes(['user_id' => 1, 'semester' => 1, 'year' => 2026]) === false,
        $currentSection
    );
}

// ===================== 08. PLANEJAMENTO =====================

if (section("08. Planejamento Pedagógico — Submissão e Registros", 'planning')) {
    $planModel = new PlanningSubmission();

    $stmt = $db->query("SELECT id FROM planning_templates WHERE is_active=1 LIMIT 1");
    $templateRow = $stmt->fetch();
    $stmt2 = $db->query("SELECT id FROM classrooms LIMIT 1");
    $classroomRow = $stmt2->fetch();
    $stmt3 = $db->query("SELECT id FROM users WHERE role='professor' LIMIT 1");
    $profRow = $stmt3->fetch();

    if ($templateRow && $classroomRow && $profRow) {
        $planId = $planModel->create([
            'template_id' => $templateRow['id'],
            'teacher_id' => $profRow['id'],
            'classroom_id' => $classroomRow['id'],
            'period_start' => '2026-02-02',
            'period_end' => '2026-02-14',
            'status' => 'draft',
        ]);
        test("create() planejamento com dados válidos", (bool)$planId, $currentSection);

        if ($planId) {
            $plan = $planModel->find($planId);
            test("find() retorna planejamento criado", $plan !== null, $currentSection);
            test("Status inicial = 'draft'", ($plan['status'] ?? '') === 'draft', $currentSection);

            // Transições de status
            $submitted = $planModel->updateStatus($planId, 'submitted');
            test("updateStatus() → 'submitted'", (bool)$submitted, $currentSection);

            $afterSubmit = $planModel->find($planId);
            test("Status após submit = 'submitted'", ($afterSubmit['status'] ?? '') === 'submitted', $currentSection);
            test("submitted_at preenchido", !empty($afterSubmit['submitted_at'] ?? ''), $currentSection);

            // Registro de período
            $periodRecord = new PlanningPeriodRecord();
            $recId = $periodRecord->create([
                'submission_id' => $planId,
                'activity_synthesis' => 'Síntese das atividades do período.',
                'planning_execution' => 'sim',
                'child_engagement' => 'alto',
                'adjustments_time' => 1,
                'adjustments_space' => 0,
                'adjustments_materials' => 1,
                'adjustments_mediation' => 0,
                'adjustments_interest' => 0,
                'adjustments_description' => 'Precisamos ajustar o tempo das atividades.',
                'advances_challenges' => 'Boa participação geral da turma.',
            ]);
            test("PlanningPeriodRecord::create() cria registro", (bool)$recId, $currentSection);

            if ($recId) {
                $loaded = $periodRecord->findBySubmission($planId);
                test("findBySubmission() retorna registro salvo", $loaded !== null, $currentSection);
                test("activity_synthesis correto", ($loaded['activity_synthesis'] ?? '') === 'Síntese das atividades do período.', $currentSection);
                test("planning_execution = 'sim'", ($loaded['planning_execution'] ?? '') === 'sim', $currentSection);
            }

            $cleanupIds['planning_submissions'][] = $planId;
        }
    } else {
        skip("Planejamento", "template ativo, turma ou professor não disponível no banco");
    }

    // Planejamento sem teacher_id deve falhar
    warn(
        "create() sem teacher_id retorna false",
        $planModel->create(['template_id' => 1, 'classroom_id' => 1, 'period_start' => '2026-01-01', 'period_end' => '2026-01-31']) === false,
        $currentSection
    );

    // Datas invertidas
    warn(
        "create() com period_start > period_end deve ser rejeitado",
        $planModel->create(['teacher_id' => 1, 'template_id' => 1, 'classroom_id' => 1, 'period_start' => '2026-12-31', 'period_end' => '2026-01-01']) === false,
        $currentSection
    );
}

// ===================== 09. PARECER DESCRITIVO =====================

if (section("09. Parecer Descritivo — Criação e Workflow", 'reports')) {
    $reportModel = new DescriptiveReport();

    $stmt = $db->query("SELECT id FROM students LIMIT 1");
    $studentRow = $stmt->fetch();
    $stmt2 = $db->query("SELECT id FROM classrooms LIMIT 1");
    $classroomRow = $stmt2->fetch();
    $stmt3 = $db->query("SELECT id FROM users WHERE role IN ('admin','coordenador') LIMIT 1");
    $coordRow = $stmt3->fetch();

    if ($studentRow && $classroomRow) {
        $reportId = $reportModel->create([
            'student_id' => $studentRow['id'],
            'classroom_id' => $classroomRow['id'],
            'semester' => 1,
            'year' => 2026,
            'student_text' => 'Texto gerado automaticamente para teste.',
            'status' => 'draft',
        ]);
        test("create() parecer com dados válidos", (bool)$reportId, $currentSection);

        if ($reportId) {
            $report = $reportModel->find($reportId);
            test("find() retorna parecer criado", $report !== null, $currentSection);
            test("Status inicial = 'draft'", ($report['status'] ?? '') === 'draft', $currentSection);

            // Editar texto
            $textUpdated = $reportModel->updateText($reportId, 'Texto editado manualmente.');
            test("updateText() atualiza student_text_edited", (bool)$textUpdated, $currentSection);

            $afterEdit = $reportModel->find($reportId);
            test("student_text_edited preenchido após updateText()", !empty($afterEdit['student_text_edited'] ?? ''), $currentSection);

            // Finalizar
            $coordId = $coordRow['id'] ?? 1;
            $finalized = $reportModel->finalize($reportId, $coordId);
            test("finalize() muda status para 'finalized'", (bool)$finalized, $currentSection);

            $finReport = $reportModel->find($reportId);
            test("Status após finalize = 'finalized'", ($finReport['status'] ?? '') === 'finalized', $currentSection);

            // Solicitar revisão
            $revision = $reportModel->requestRevision($reportId, 'Revisar texto do eixo música.', $coordId);
            test("requestRevision() muda status para 'revision_requested'", (bool)$revision, $currentSection);

            $revReport = $reportModel->find($reportId);
            test("Status = 'revision_requested'", ($revReport['status'] ?? '') === 'revision_requested', $currentSection);
            test("revision_notes preenchido", !empty($revReport['revision_notes'] ?? ''), $currentSection);

            // Reabrir
            $reopened = $reportModel->reopen($reportId);
            test("reopen() muda status para 'draft'", (bool)$reopened, $currentSection);

            $cleanupIds['descriptive_reports'][] = $reportId;
        }
    } else {
        skip("Parecer descritivo", "nenhum aluno ou turma disponível");
    }

    // Duplicata no mesmo semestre/ano/aluno
    warn(
        "findByStudent() detecta registros por aluno",
        method_exists($reportModel, 'findByStudent'),
        $currentSection
    );
}

// ===================== 10. PORTFÓLIOS =====================

if (section("10. Portfólios — Criação, Eixos e Workflow", 'portfolios')) {
    $portfolioModel = new Portfolio();

    $stmt = $db->query("SELECT id FROM classrooms LIMIT 1");
    $classroomRow = $stmt->fetch();
    $stmt2 = $db->query("SELECT id FROM users WHERE role IN ('admin','coordenador') LIMIT 1");
    $coordRow = $stmt2->fetch();

    if ($classroomRow) {
        // Usar ano único para evitar conflito com UNIQUE(classroom_id, semester, year)
        $testYear = (int)date('Y') + 10;
        $portfolioId = $portfolioModel->create([
            'classroom_id' => $classroomRow['id'],
            'semester' => 1,
            'year' => $testYear,
            'teacher_message' => 'Mensagem inicial da professora.',
        ]);
        test("create() portfólio com dados válidos", (bool)$portfolioId, $currentSection);

        if ($portfolioId) {
            $portfolio = $portfolioModel->find($portfolioId);
            test("find() retorna portfólio criado", $portfolio !== null, $currentSection);
            test("Status inicial = 'pending'", ($portfolio['status'] ?? '') === 'pending', $currentSection);

            // Atualizar com fotos de eixo (JSON)
            $axisPhotos = json_encode([
                ['url' => '/uploads/image-bank/foto1.jpg', 'caption' => 'Atividade de movimento'],
            ]);
            $updated = $portfolioModel->update($portfolioId, [
                'axis_movement_photos' => $axisPhotos,
                'axis_movement_description' => 'Descrição do eixo movimento.',
            ]);
            test("update() com fotos de eixo em JSON", (bool)$updated, $currentSection);

            $afterUpdate = $portfolioModel->find($portfolioId);
            $photosRaw = $afterUpdate['axis_movement_photos'] ?? '';
            $photosDecoded = json_decode($photosRaw, true);
            test("axis_movement_photos decodificável (JSON válido)", is_array($photosDecoded), $currentSection);

            // Finalizar
            $coordId = $coordRow['id'] ?? 1;
            $finalized = $portfolioModel->finalize($portfolioId, $coordId);
            test("finalize() muda status para 'finalized'", (bool)$finalized, $currentSection);

            $finPortfolio = $portfolioModel->find($portfolioId);
            test("Status após finalize = 'finalized'", ($finPortfolio['status'] ?? '') === 'finalized', $currentSection);

            // Solicitar revisão
            $revision = $portfolioModel->requestRevision($portfolioId, 'Revisar fotos do eixo PCA.', $coordId);
            test("requestRevision() muda status para 'revision_requested'", (bool)$revision, $currentSection);

            // Reabrir
            $reopened = $portfolioModel->reopen($portfolioId);
            test("reopen() muda status para 'pending'", (bool)$reopened, $currentSection);

            $cleanupIds['portfolios'][] = $portfolioId;
        }
    } else {
        skip("Portfólios", "nenhuma turma disponível");
    }
}

// ===================== 11. FEEDBACK DE COORDENAÇÃO =====================

if (section("11. Feedback de Coordenação — Comentários Polimórficos", 'coordinator')) {
    $commentModel = new CoordinatorComment();

    $stmt = $db->query("SELECT id FROM users WHERE role='coordenador' LIMIT 1");
    $coordRow = $stmt->fetch();

    $stmt2 = $db->query("SELECT id FROM observations LIMIT 1");
    $obsRow = $stmt2->fetch();

    if ($coordRow && $obsRow) {
        $commentId = $commentModel->create([
            'coordinator_id' => $coordRow['id'],
            'content_type' => 'observation',
            'content_id' => $obsRow['id'],
            'comment' => 'Ótimo trabalho neste eixo!',
        ]);
        test("create() comentário em observação", (bool)$commentId, $currentSection);

        if ($commentId) {
            $comments = $commentModel->findByContent('observation', $obsRow['id']);
            test("findByContent() retorna comentários", is_array($comments) && count($comments) > 0, $currentSection);
            test("Comentário contém coordinator_name (JOIN)", !empty($comments[0]['coordinator_name'] ?? ''), $currentSection);

            // Cleanup manual
            $db->prepare("DELETE FROM coordinator_comments WHERE id = ?")->execute([$commentId]);
        }
    } else {
        skip("Feedback de coordenação", "nenhum coordenador ou observação disponível");
    }

    // content_type inválido deve falhar
    warn(
        "create() com content_type inválido retorna false",
        $commentModel->create(['coordinator_id' => 1, 'content_type' => 'tipo_invalido', 'content_id' => 1, 'comment' => 'X']) === false,
        $currentSection
    );
}

// ===================== 12. BANCO DE IMAGENS =====================

if (section("12. Banco de Imagens — Estrutura e Consultas", 'imagebank')) {
    $imageBankModel = new ImageBank();

    $stmt = $db->query("SELECT id FROM image_folders LIMIT 1");
    $folderRow = $stmt->fetch();

    if ($folderRow) {
        $images = $imageBankModel->findByFolder($folderRow['id']);
        test("findByFolder() retorna array", is_array($images), $currentSection);
    } else {
        skip("findByFolder()", "nenhuma pasta de imagens disponível");
    }

    $stmt = $db->query("SELECT id FROM classrooms LIMIT 1");
    $classroomRow = $stmt->fetch();
    if ($classroomRow) {
        $images = $imageBankModel->getByClassroom($classroomRow['id']);
        test("getByClassroom() retorna array", is_array($images), $currentSection);
    }

    // Verificar que updateCaption existe e funciona
    test("Método updateCaption() existe", method_exists($imageBankModel, 'updateCaption'), $currentSection);
    test("Método moveToFolder() existe", method_exists($imageBankModel, 'moveToFolder'), $currentSection);
    test("Método delete() existe", method_exists($imageBankModel, 'delete'), $currentSection);
}

// ===================== 13. CURSOS =====================

if (section("13. Cursos — CRUD Completo com Módulos", 'courses')) {
    $courseModel = new Course();

    $testSlug = 'curso-teste-auditoria-' . time();
    $created = $courseModel->create([
        ':title' => 'Curso de Auditoria Auto',
        ':slug' => $testSlug,
        ':description' => 'Descrição detalhada do curso de auditoria.',
        ':short_description' => 'Curso de teste auto',
        ':cover_image' => null,
        ':price' => 0.00,
        ':is_free' => 1,
        ':is_active' => 1,
        ':category' => 'pedagogia',
        ':level' => 'beginner',
        ':duration_hours' => 5,
        ':instructor_id' => null,
    ]);
    // Course::create() retorna bool; capturar lastInsertId separadamente
    $courseId = $created ? (int)$db->lastInsertId() : false;
    test("create() curso com dados válidos", (bool)$courseId, $currentSection);

    if ($courseId) {
        $course = $courseModel->find($courseId);
        test("find() retorna curso criado", $course !== null, $currentSection);
        test("Slug correto", ($course['slug'] ?? '') === $testSlug, $currentSection);

        $bySlug = $courseModel->findBySlug($testSlug);
        test("findBySlug() encontra por slug", $bySlug !== null, $currentSection);

        // Curso sem título deve falhar
        warn(
            "create() sem título retorna false",
            $courseModel->create([':title' => '', ':slug' => 'slug-vazio-' . time(), ':is_active' => 1]) === false,
            $currentSection
        );

        // Preço negativo deve falhar
        warn(
            "create() com preço negativo retorna false",
            $courseModel->create([':title' => 'X', ':slug' => 'x-' . time(), ':price' => -100, ':is_active' => 1]) === false,
            $currentSection
        );

        $cleanupIds['courses'][] = $courseId;
    }
}

// ===================== 14. QUIZ =====================

if (section("14. Quiz — Criação, Questões e Submissão", 'quiz')) {
    $stmt = $db->query("SELECT id FROM quizzes LIMIT 1");
    $quizRow = $stmt->fetch();

    if ($quizRow) {
        $quiz = $db->prepare("SELECT * FROM quizzes WHERE id = ?");
        $quiz->execute([$quizRow['id']]);
        $quizData = $quiz->fetch();

        test("Quiz tem passing_score definido", isset($quizData['passing_score']), $currentSection);
        test("passing_score entre 0 e 100", (int)($quizData['passing_score'] ?? -1) >= 0 && (int)($quizData['passing_score'] ?? 101) <= 100, $currentSection);

        warn(
            "attempts_allowed > 0 (não zero — causaria loop infinito)",
            (int)($quizData['attempts_allowed'] ?? 0) !== 0,
            $currentSection
        );

        // Verificar que há questões
        $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM quiz_questions WHERE quiz_id = ?");
        $stmt->execute([$quizRow['id']]);
        $qCount = $stmt->fetch()['cnt'] ?? 0;
        warn("Quiz tem ao menos 1 questão", $qCount >= 1, $currentSection);

        if ($qCount > 0) {
            // Cada questão tem ao menos 1 resposta correta
            $stmt = $db->prepare("
                SELECT qq.id, COUNT(qa.id) as correct_count
                FROM quiz_questions qq
                LEFT JOIN quiz_answers qa ON qa.question_id = qq.id AND qa.is_correct = 1
                WHERE qq.quiz_id = ?
                GROUP BY qq.id
            ");
            $stmt->execute([$quizRow['id']]);
            $questions = $stmt->fetchAll();
            $allHaveCorrect = true;
            foreach ($questions as $q) {
                if ((int)$q['correct_count'] === 0) $allHaveCorrect = false;
            }
            test("Todas as questões têm ao menos 1 resposta correta", $allHaveCorrect, $currentSection);

            // Divisão por zero: totalPoints > 0
            $stmt = $db->prepare("SELECT SUM(points) as total FROM quiz_questions WHERE quiz_id = ?");
            $stmt->execute([$quizRow['id']]);
            $total = $stmt->fetch()['total'] ?? 0;
            test("Total de pontos do quiz > 0 (evita divisão por zero)", (int)$total > 0, $currentSection);
        }
    } else {
        skip("Quiz", "nenhum quiz cadastrado no banco");
    }
}

// ===================== 15. MATRÍCULAS =====================

if (section("15. Matrículas — CRUD e Progresso", 'enrollments')) {
    $enrollModel = new Enrollment();

    $stmt = $db->query("SELECT id FROM users WHERE role='student' LIMIT 1");
    $studentUserRow = $stmt->fetch();
    $stmt2 = $db->query("SELECT id FROM courses WHERE is_active=1 LIMIT 1");
    $courseRow = $stmt2->fetch();

    if ($studentUserRow && $courseRow) {
        // Evitar duplicata
        $alreadyEnrolled = $enrollModel->isEnrolled($studentUserRow['id'], $courseRow['id']);
        if (!$alreadyEnrolled) {
            $enrollId = $enrollModel->create([
                ':user_id' => $studentUserRow['id'],
                ':course_id' => $courseRow['id'],
                ':status' => 'active',
                ':payment_status' => 'free',
            ]);
            test("create() matrícula com dados válidos", (bool)$enrollId, $currentSection);

            if ($enrollId) {
                test("isEnrolled() retorna true após matrícula", $enrollModel->isEnrolled($studentUserRow['id'], $courseRow['id']), $currentSection);

                $userEnrollments = $enrollModel->getByUser($studentUserRow['id']);
                test("getByUser() retorna matrículas do usuário", is_array($userEnrollments), $currentSection);
                test("Matrícula inclui course_title (JOIN)", !empty($userEnrollments[0]['course_title'] ?? ''), $currentSection);

                $cleanupIds['enrollments'][] = $enrollId;
            }
        } else {
            skip("Criar matrícula", "aluno já matriculado neste curso");
        }
    } else {
        skip("Matrículas", "nenhum aluno (role=student) ou curso ativo no banco");
    }

    // Dupla matrícula deve retornar false ou lançar erro controlado
    if (($studentUserRow ?? false) && ($courseRow ?? false) && $enrollModel->isEnrolled($studentUserRow['id'], $courseRow['id'])) {
        $dup = $enrollModel->create([
            ':user_id' => $studentUserRow['id'],
            ':course_id' => $courseRow['id'],
            ':status' => 'active',
            ':payment_status' => 'free',
        ]);
        warn("Dupla matrícula retorna false (previne duplicata)", $dup === false, $currentSection);
    }
}

// ===================== 16. NOTIFICAÇÕES =====================

if (section("16. Notificações — Criação e Leitura", 'notifications')) {
    $notifModel = new Notification();

    $stmt = $db->query("SELECT id FROM users LIMIT 1");
    $userRow = $stmt->fetch();

    if ($userRow) {
        $notifId = $notifModel->create([
            'user_id' => $userRow['id'],
            'type' => 'teste',
            'title' => 'Teste Automático',
            'message' => 'Notificação de teste automático.',
            'reference_type' => null,
            'reference_id' => null,
        ]);
        test("create() notificação", (bool)$notifId, $currentSection);

        if ($notifId) {
            $notifs = $notifModel->findByUser($userRow['id']);
            test("findByUser() retorna notificações", is_array($notifs), $currentSection);

            $marked = $notifModel->markAsRead($notifId);
            test("markAsRead() marca como lida", (bool)$marked, $currentSection);

            $allRead = $notifModel->markAllAsRead($userRow['id']);
            test("markAllAsRead() retorna resultado", (bool)$allRead, $currentSection);

            // Cleanup
            $db->prepare("DELETE FROM notifications WHERE id = ?")->execute([$notifId]);
        }
    } else {
        skip("Notificações", "nenhum usuário no banco");
    }
}

// ===================== 17. INTEGRIDADE DE MODELS =====================

if (section("17. Integridade dos Models — Métodos Obrigatórios", 'models')) {
    $requiredMethods = [
        'App\Models\Observation' => ['find', 'createWithAxes', 'updateField', 'finalize', 'reopen', 'findByStudentAndSemester'],
        'App\Models\PlanningSubmission' => ['find', 'create', 'updateStatus', 'getAnswers', 'saveAnswer'],
        'App\Models\PlanningPeriodRecord' => ['create', 'findBySubmission'],
        'App\Models\DescriptiveReport' => ['find', 'create', 'update', 'updateText', 'finalize', 'reopen', 'requestRevision'],
        'App\Models\Portfolio' => ['find', 'create', 'update', 'finalize', 'reopen', 'requestRevision'],
        'App\Models\CoordinatorComment' => ['create', 'findByContent', 'deleteByContent'],
        'App\Models\ImageBank' => ['findByFolder', 'getByClassroom', 'updateCaption', 'moveToFolder', 'delete'],
        'App\Models\User' => ['find', 'findByEmail', 'create', 'update', 'delete', 'all', 'countByRole'],
        'App\Models\Student' => ['find', 'create', 'update', 'delete', 'all'],
        'App\Models\Enrollment' => ['find', 'create', 'isEnrolled', 'getByUser', 'getByCourse'],
        'App\Models\Notification' => ['create', 'findByUser', 'markAsRead', 'markAllAsRead'],
    ];

    foreach ($requiredMethods as $class => $methods) {
        if (!class_exists($class)) {
            skip("Class {$class}", "não encontrada");
            continue;
        }
        foreach ($methods as $method) {
            test("  {$class}::{$method}() existe", method_exists($class, $method), $currentSection);
        }
    }
}

// ===================== 18. ROTAS REGISTRADAS =====================

if (section("18. Rotas — Todos os Endpoints em index.php", 'routes')) {
    $indexContent = file_get_contents(__DIR__ . '/../public/index.php');

    $criticalRoutes = [
        // Auth
        '/login' => 'Login',
        '/registro' => 'Registro',
        '/esqueci-senha' => 'Esqueci senha',
        '/redefinir-senha/{token}' => 'Redefinir senha',
        // Admin pedagógico
        '/admin/observations' => 'Listagem observações',
        '/admin/observations/create' => 'Criar observação',
        '/admin/observations/{id}/auto-save' => 'Auto-save observação',
        '/admin/observations/{id}/finalize' => 'Finalizar observação',
        '/admin/observations/{id}/reopen' => 'Reabrir observação',
        '/admin/planning' => 'Listagem planejamento',
        '/admin/planning/create' => 'Criar planejamento',
        '/admin/planning/{id}/days' => 'Dias do planejamento',
        '/admin/planning/{id}/routine' => 'Rotina semanal',
        '/admin/planning/{id}/record/create' => 'Criar registro de período',
        '/admin/planning/{id}/finalize' => 'Finalizar planejamento',
        '/admin/descriptive-reports' => 'Listagem pareceres',
        '/admin/descriptive-reports/create' => 'Criar parecer',
        '/admin/descriptive-reports/{id}/finalize' => 'Finalizar parecer',
        '/admin/descriptive-reports/{id}/reopen' => 'Reabrir parecer',
        '/admin/descriptive-reports/{id}/correct-text' => 'Corrigir texto (IA)',
        '/admin/descriptive-reports/{id}/recompile' => 'Recompilar parecer',
        '/admin/descriptive-reports/{id}/export-pdf' => 'Exportar PDF parecer',
        '/admin/portfolios' => 'Listagem portfólios',
        '/admin/portfolios/create' => 'Criar portfólio',
        '/admin/portfolios/{id}/finalize' => 'Finalizar portfólio',
        '/admin/portfolios/{id}/reopen' => 'Reabrir portfólio',
        '/admin/portfolios/{id}/export-pdf' => 'Exportar PDF portfólio',
        '/admin/coordinator-feedback' => 'Feedback de coordenação',
        '/admin/image-bank' => 'Banco de imagens',
        '/admin/image-bank/folder/{folderId}/upload' => 'Upload banco de imagens',
        '/admin/image-bank/image/{id}/caption' => 'Legenda imagem',
        '/admin/image-bank/image/{id}/move' => 'Mover imagem',
        // Turmas e alunos
        '/admin/classrooms' => 'Listagem turmas',
        '/admin/classrooms/{id}/add-student' => 'Adicionar aluno à turma',
        '/admin/classrooms/{id}/remove-student' => 'Remover aluno da turma',
        '/admin/students' => 'Listagem alunos',
        '/admin/students/{id}/ai-summary' => 'Resumo IA aluno',
        // Cursos e quiz
        '/admin/courses' => 'Listagem cursos',
        '/admin/quizzes/{quizId}/questions' => 'Adicionar questão',
        '/admin/quiz/{quizId}/reset-attempts' => 'Reset tentativas',
        // Notificações
        '/admin/notifications/dropdown' => 'Dropdown notificações',
        '/admin/notifications/mark-all-read' => 'Marcar todas lidas',
        // Central de ajuda
        '/admin/help' => 'Central de ajuda',
        // Painel do aluno
        '/minha-conta' => 'Dashboard aluno',
        '/minha-conta/senha' => 'Alterar senha aluno',
        '/minha-area' => 'Dashboard responsável',
        // API
        '/api/video-progress' => 'API progresso de vídeo',
    ];

    foreach ($criticalRoutes as $route => $desc) {
        $found = strpos($indexContent, "'{$route}'") !== false || strpos($indexContent, "\"{$route}\"") !== false;
        test("Rota '{$route}' ({$desc})", $found, $currentSection);
    }
}

// ===================== 19. VIEWS =====================

if (section("19. Views — Existência de Todos os Templates", 'views')) {
    $viewBase = __DIR__ . '/../views/';

    $requiredViews = [
        // Auth
        'pages/login.php' => 'Login',
        'pages/register.php' => 'Registro',
        'pages/forgot-password.php' => 'Esqueci senha',
        'pages/reset-password.php' => 'Redefinir senha',
        // Públicas
        'pages/home.php' => 'Home',
        'pages/cursos.php' => 'Catálogo cursos',
        'pages/contato.php' => 'Contato',
        // Admin — dashboard
        'admin/dashboard.php' => 'Dashboard admin',
        'admin/dashboard_professor.php' => 'Dashboard professor',
        'admin/dashboard_coordenador.php' => 'Dashboard coordenador',
        // Admin — escolas e alunos
        'admin/schools/index.php' => 'Escolas lista',
        'admin/schools/show.php' => 'Escola detalhe',
        'admin/classrooms/index.php' => 'Turmas lista',
        'admin/classrooms/show.php' => 'Turma detalhe',
        'admin/students/index.php' => 'Alunos lista',
        'admin/students/show.php' => 'Aluno detalhe',
        // Admin — pedagógico
        'admin/observations/index.php' => 'Observações lista',
        'admin/observations/create.php' => 'Criar observação',
        'admin/observations/edit.php' => 'Editar observação',
        'admin/observations/show.php' => 'Ver observação',
        'admin/observations/_coordinator_feedback.php' => 'Feedback coord. (partial)',
        'admin/observations/_questions.php' => 'Questões (partial)',
        'admin/planning/index.php' => 'Planejamento lista',
        'admin/planning/days.php' => 'Planejamento dias',
        'admin/planning/record_form.php' => 'Registro de período',
        'admin/descriptive-reports/index.php' => 'Pareceres lista',
        'admin/descriptive-reports/create.php' => 'Criar parecer',
        'admin/descriptive-reports/show.php' => 'Ver parecer',
        'admin/portfolios/index.php' => 'Portfólios lista',
        'admin/portfolios/form.php' => 'Form portfólio',
        'admin/portfolios/show.php' => 'Ver portfólio',
        // Admin — mídias
        'admin/image-bank/index.php' => 'Banco de imagens',
        'admin/image-bank/classroom.php' => 'Turma imagens',
        'admin/image-bank/folder.php' => 'Pasta imagens',
        // Admin — cursos
        'admin/courses/index.php' => 'Cursos lista',
        'admin/courses/create.php' => 'Criar curso',
        'admin/courses/show.php' => 'Ver curso',
        // Admin — utilitários
        'admin/notifications/index.php' => 'Notificações lista',
        'admin/help/index.php' => 'Central de ajuda',
        // Layouts
        'layouts/admin.php' => 'Layout admin',
        'layouts/student.php' => 'Layout aluno',
        'layouts/public.php' => 'Layout público',
    ];

    foreach ($requiredViews as $file => $desc) {
        test("{$file} ({$desc})", file_exists($viewBase . $file), $currentSection);
    }

    // Verificar que layouts não têm dados hardcoded críticos
    $adminLayout = file_get_contents($viewBase . 'layouts/admin.php');
    test("Admin layout tem link /admin/observations", strpos($adminLayout, '/admin/observations') !== false, $currentSection);
    test("Admin layout tem link /admin/planning", strpos($adminLayout, '/admin/planning') !== false, $currentSection);
    test("Admin layout tem link /admin/descriptive-reports", strpos($adminLayout, '/admin/descriptive-reports') !== false, $currentSection);
    test("Admin layout tem link /admin/portfolios", strpos($adminLayout, '/admin/portfolios') !== false, $currentSection);
    test("Admin layout tem link /admin/image-bank", strpos($adminLayout, '/admin/image-bank') !== false, $currentSection);
    test("Admin layout tem link /admin/notifications", strpos($adminLayout, '/admin/notifications') !== false, $currentSection);
    test("Admin layout tem link /admin/help", strpos($adminLayout, '/admin/help') !== false, $currentSection);
}

// ===================== 20. WORKFLOWS DE APROVAÇÃO =====================

if (section("20. Workflows — Fluxos de Status e Permissões", 'workflows')) {
    // Verificar que transições de status ilógicas são bloqueadas

    // Observação: não deve poder finalizar se já finalizada
    $stmt = $db->query("SELECT id FROM observations WHERE status='finalized' LIMIT 1");
    $finalizedObs = $stmt->fetch();
    if ($finalizedObs) {
        $obsModel = new Observation();
        $stmt2 = $db->query("SELECT id FROM users LIMIT 1");
        $userRow = $stmt2->fetch();
        if ($userRow) {
            $obsBeforeRefinalize = $obsModel->find($finalizedObs['id']);
            $obsModel->finalize($finalizedObs['id'], $userRow['id']); // tenta finalizar novamente
            $obsAfter = $obsModel->find($finalizedObs['id']);
            warn(
                "finalize() em obs. já finalizada não duplica finalized_at",
                $obsBeforeRefinalize['finalized_at'] === $obsAfter['finalized_at'],
                $currentSection
            );
        }
    }

    // Verificar service de IA (Gemini) existe
    test("GeminiService.php existe", file_exists(__DIR__ . '/../app/Services/GeminiService.php'), $currentSection);
    test("PdfExportService.php existe", file_exists(__DIR__ . '/../app/Services/PdfExportService.php'), $currentSection);
    test("MailerService.php existe", file_exists(__DIR__ . '/../app/Services/MailerService.php'), $currentSection);

    // Verificar que GeminiService tem métodos esperados
    if (class_exists('App\Services\GeminiService')) {
        test("GeminiService::correctDescriptiveText() existe",
            method_exists('App\Services\GeminiService', 'correctDescriptiveText'),
            $currentSection
        );
        test("GeminiService::generateStudentSummary() existe",
            method_exists('App\Services\GeminiService', 'generateStudentSummary'),
            $currentSection
        );
    }

    // mPDF disponível
    test("mPDF composer package disponível", class_exists('Mpdf\Mpdf'), $currentSection);

    // Verificar .env.example (arquivo opcional de documentação)
    warn(".env.example com GEMINI_API_KEY existe",
        file_exists(__DIR__ . '/../.env.example') &&
        strpos(file_get_contents(__DIR__ . '/../.env.example') ?: '', 'GEMINI_API_KEY') !== false,
        $currentSection
    );

    // Verificar variável de ambiente Gemini
    warn(
        "GEMINI_API_KEY configurado no .env",
        !empty(getenv('GEMINI_API_KEY')),
        $currentSection
    );
}

// ===================== CLEANUP =====================

if ($db ?? false) {
    // Limpar dados de teste na ordem correta (dependências)
    foreach ($cleanupIds['observations'] as $id) {
        $db->prepare("DELETE FROM observations WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['descriptive_reports'] as $id) {
        $db->prepare("DELETE FROM descriptive_reports WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['portfolios'] as $id) {
        $db->prepare("DELETE FROM portfolios WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['planning_submissions'] as $id) {
        $db->prepare("DELETE FROM planning_period_records WHERE submission_id = ?")->execute([$id]);
        $db->prepare("DELETE FROM planning_submissions WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['enrollments'] as $id) {
        $db->prepare("DELETE FROM enrollments WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['courses'] as $id) {
        $db->prepare("DELETE FROM courses WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['students'] as $id) {
        $db->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['classrooms'] as $id) {
        $db->prepare("DELETE FROM classrooms WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['schools'] as $id) {
        $db->prepare("DELETE FROM schools WHERE id = ?")->execute([$id]);
    }
    foreach ($cleanupIds['users'] as $id) {
        $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    }
}

// ===================== RESULTADO FINAL =====================

$total = $passed + $failed + $warned + $skipped;

echo "\n\033[1m╔═══════════════════════════════════════════════════╗\033[0m\n";
echo "\033[1m║                  RESULTADO FINAL                  ║\033[0m\n";
echo "\033[1m╠═══════════════════════════════════════════════════╣\033[0m\n";
echo "\033[1m║\033[0m  Total de testes executados: \033[1m{$total}\033[0m\n";
printf("\033[1m║\033[0m  \033[32m[PASS]\033[0m %-3d  \033[31m[FAIL]\033[0m %-3d  \033[33m[WARN]\033[0m %-3d  \033[90m[SKIP]\033[0m %-3d\n", $passed, $failed, $warned, $skipped);
echo "\033[1m╚═══════════════════════════════════════════════════╝\033[0m\n";

if (!empty($errors)) {
    echo "\n\033[1;31m FALHAS CONFIRMADAS (" . count($errors) . "):\033[0m\n";
    foreach ($errors as $i => $err) {
        echo "  \033[31m" . ($i + 1) . ".\033[0m {$err}\n";
    }
}

if (!empty($warns)) {
    echo "\n\033[1;33m AVISOS / POSSÍVEIS DEFEITOS (" . count($warns) . "):\033[0m\n";
    foreach ($warns as $i => $w) {
        echo "  \033[33m" . ($i + 1) . ".\033[0m {$w}\n";
    }
}

if ($failed === 0 && count($warns) === 0) {
    echo "\n\033[1;32m Todos os testes passaram sem avisos. \033[0m\n\n";
} elseif ($failed === 0) {
    echo "\n\033[1;33m Nenhuma falha crítica — verifique os avisos acima. \033[0m\n\n";
} else {
    echo "\n\033[1;31m Há defeitos que precisam ser corrigidos. \033[0m\n\n";
}

exit($failed > 0 ? 1 : 0);
