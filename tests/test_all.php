<?php
/**
 * ============================================================
 * Hansen Educacional - Bateria de Testes Automatizados
 * ============================================================
 *
 * Testa todos os Models, CRUD, Views, Rotas e Servicos do projeto.
 * Nao requer PHPUnit - roda com PHP puro via CLI.
 *
 * USO:
 *   php tests/test_all.php
 *
 * COBERTURA:
 *   - Conexao com banco de dados
 *   - Estrutura de tabelas (migrations)
 *   - Model Course   (find, findBySlug, allActive, getSections, create, update, delete)
 *   - Model Section  (find, getByCourse, create, update, delete)
 *   - Model Lesson   (find, getBySection, create, update, delete + joins)
 *   - Model User     (findByEmail, create com hash, email duplicado, roles)
 *   - Model Enrollment (getByCourse, getByUser, create, isEnrolled)
 *   - Password Reset (token create, token validate, token expiry)
 *   - Existencia de Views (admin/courses, admin/lessons, pages, student, layouts)
 *   - Existencia de Controllers (CourseAdmin, SectionAdmin, LessonAdmin, StudentPanel)
 *   - Existencia de Servicos (MailerService)
 *   - Rotas registradas em index.php (21 rotas)
 *   - Sidebar admin (link Cursos)
 *   - Login page (links registro e esqueci-senha)
 *
 * NOTAS:
 *   - Todos os dados de teste sao criados e removidos (cleanup) ao final.
 *   - Exit code 0 = todos passaram, 1 = algum falhou.
 *   - Requer banco MySQL rodando com as migrations aplicadas.
 *
 * HISTORICO:
 *   2026-02-11 - Criacao inicial (Fases 5, 6 e 7)
 * ============================================================
 */

// ===================== BOOTSTRAP =====================

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
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

use App\Core\Database\Connection;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Enrollment;

// ===================== TEST RUNNER =====================

$passed = 0;
$failed = 0;
$errors = [];
$cleanupCallbacks = [];

function test($name, $condition, &$passed, &$failed, &$errors) {
    if ($condition) {
        echo "  \033[32m[PASS]\033[0m $name\n";
        $passed++;
    } else {
        echo "  \033[31m[FAIL]\033[0m $name\n";
        $failed++;
        $errors[] = $name;
    }
}

function section($title) {
    echo "\n\033[36m--- $title ---\033[0m\n";
}

echo "\n\033[1m============================================\033[0m\n";
echo "\033[1m TESTES AUTOMATIZADOS - Hansen Educacional \033[0m\n";
echo "\033[1m============================================\033[0m\n";
echo " Data: " . date('d/m/Y H:i:s') . "\n";

// ===================== 1. CONEXAO =====================

section("1. Conexao com Banco de Dados");
try {
    $db = Connection::getInstance();
    test("Conexao MySQL ativa", $db !== null, $passed, $failed, $errors);
} catch (Exception $e) {
    test("Conexao MySQL ativa", false, $passed, $failed, $errors);
    die("\n  \033[31mIMPOSSIVEL CONTINUAR: " . $e->getMessage() . "\033[0m\n");
}

// ===================== 2. ESTRUTURA DE TABELAS =====================

section("2. Estrutura de Tabelas");

$requiredTables = ['courses', 'sections', 'lessons', 'users', 'enrollments', 'password_resets', 'video_progress', 'quizzes', 'quiz_questions', 'quiz_answers', 'quiz_attempts', 'contacts'];
foreach ($requiredTables as $table) {
    $stmt = $db->query("SHOW TABLES LIKE '{$table}'");
    test("Tabela '{$table}' existe", $stmt->rowCount() > 0, $passed, $failed, $errors);
}

// Verificar colunas criticas da tabela password_resets
$stmt = $db->query("DESCRIBE password_resets");
$columns = array_column($stmt->fetchAll(), 'Field');
test("password_resets: coluna 'email'", in_array('email', $columns), $passed, $failed, $errors);
test("password_resets: coluna 'token'", in_array('token', $columns), $passed, $failed, $errors);
test("password_resets: coluna 'created_at'", in_array('created_at', $columns), $passed, $failed, $errors);

// ===================== 3. MODEL COURSE =====================

section("3. Model Course - Leitura");
$courseModel = new Course();

$courses = $courseModel->allActive();
test("allActive() retorna array", is_array($courses), $passed, $failed, $errors);

$course = $courseModel->find(1);
test("find(1) retorna curso", $course !== null && !empty($course['title']), $passed, $failed, $errors);

$slug = $course['slug'] ?? 'php-para-iniciantes';
$courseBySlug = $courseModel->findBySlug($slug);
test("findBySlug('{$slug}') retorna curso", $courseBySlug !== null, $passed, $failed, $errors);

$courseSections = $courseModel->getSections(1);
test("getSections(1) retorna array", is_array($courseSections), $passed, $failed, $errors);
test("getSections(1) tem secoes", count($courseSections) > 0, $passed, $failed, $errors);

$activeCount = $courseModel->countActive();
test("countActive() retorna inteiro >= 0", is_int($activeCount) && $activeCount >= 0, $passed, $failed, $errors);

// ===================== 4. MODEL SECTION =====================

section("4. Model Section - Leitura");
$sectionModel = new Section();

$section = $sectionModel->find(1);
test("find(1) retorna secao", $section !== null && !empty($section['title']), $passed, $failed, $errors);

$sectionsByCourse = $sectionModel->getByCourse(1);
test("getByCourse(1) retorna array com dados", is_array($sectionsByCourse) && count($sectionsByCourse) > 0, $passed, $failed, $errors);

// ===================== 5. MODEL LESSON =====================

section("5. Model Lesson - Leitura");
$lessonModel = new Lesson();

$lesson = $lessonModel->find(1);
test("find(1) retorna licao", $lesson !== null && !empty($lesson['title']), $passed, $failed, $errors);
test("find(1) inclui course_id (via JOIN)", isset($lesson['course_id']), $passed, $failed, $errors);
test("find(1) inclui section_title (via JOIN)", isset($lesson['section_title']), $passed, $failed, $errors);
test("find(1) inclui course_title (via JOIN)", isset($lesson['course_title']), $passed, $failed, $errors);

$lessonsBySection = $lessonModel->getBySection(1);
test("getBySection(1) retorna array com dados", is_array($lessonsBySection) && count($lessonsBySection) > 0, $passed, $failed, $errors);

// ===================== 6. MODEL USER =====================

section("6. Model User - Leitura");
$userModel = new User();

$admin = $userModel->findByEmail('admin@hansen.com');
test("findByEmail('admin@hansen.com') retorna user", $admin !== null, $passed, $failed, $errors);
test("Admin tem role = 'admin'", ($admin['role'] ?? '') === 'admin', $passed, $failed, $errors);

$allUsers = $userModel->all();
test("all() retorna array", is_array($allUsers), $passed, $failed, $errors);

$adminCount = $userModel->countByRole('admin');
test("countByRole('admin') >= 1", $adminCount >= 1, $passed, $failed, $errors);

// ===================== 7. MODEL ENROLLMENT =====================

section("7. Model Enrollment - Leitura");
$enrollmentModel = new Enrollment();

$enrollByCourse = $enrollmentModel->getByCourse(1);
test("getByCourse(1) retorna array", is_array($enrollByCourse), $passed, $failed, $errors);

// ===================== 8. CRUD COURSE =====================

section("8. CRUD Completo - Course (Create/Update/Delete)");
$testSlug = 'curso-teste-auto-' . time();
$testData = [
    ':title' => 'Curso de Teste Automatizado',
    ':slug' => $testSlug,
    ':description' => 'Descricao do curso de teste',
    ':short_description' => 'Teste automatizado',
    ':cover_image' => null,
    ':price' => 99.90,
    ':is_free' => 0,
    ':is_active' => 1,
    ':category' => 'teste',
    ':level' => 'beginner',
    ':duration_hours' => 10,
    ':instructor_id' => null,
];

$created = $courseModel->create($testData);
test("create() - Criar curso", $created, $passed, $failed, $errors);

$stmt = $db->prepare("SELECT id FROM courses WHERE slug = ?");
$stmt->execute([$testSlug]);
$testCourse = $stmt->fetch();
$testCourseId = $testCourse ? $testCourse['id'] : null;
test("Curso criado tem ID no banco", $testCourseId !== null, $passed, $failed, $errors);

if ($testCourseId) {
    $testData[':title'] = 'Curso Atualizado';
    $updated = $courseModel->update($testCourseId, $testData);
    test("update() - Atualizar titulo", $updated, $passed, $failed, $errors);

    $updatedCourse = $courseModel->find($testCourseId);
    test("Titulo atualizado corretamente", ($updatedCourse['title'] ?? '') === 'Curso Atualizado', $passed, $failed, $errors);

    // ===================== 9. CRUD SECTION =====================

    section("9. CRUD Completo - Section (Create/Update/Delete)");
    $sectionData = [
        ':course_id' => $testCourseId,
        ':title' => 'Secao de Teste',
        ':description' => 'Descricao da secao de teste',
        ':sort_order' => 1,
    ];
    $sectionCreated = $sectionModel->create($sectionData);
    test("create() - Criar secao", $sectionCreated, $passed, $failed, $errors);

    $stmt = $db->prepare("SELECT id FROM sections WHERE course_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$testCourseId]);
    $testSection = $stmt->fetch();
    $testSectionId = $testSection ? $testSection['id'] : null;
    test("Secao criada tem ID no banco", $testSectionId !== null, $passed, $failed, $errors);

    if ($testSectionId) {
        $sectionData[':title'] = 'Secao Atualizada';
        $sectionUpdated = $sectionModel->update($testSectionId, $sectionData);
        test("update() - Atualizar secao", $sectionUpdated, $passed, $failed, $errors);

        // ===================== 10. CRUD LESSON =====================

        section("10. CRUD Completo - Lesson (Create/Update/Delete)");
        $lessonData = [
            ':section_id' => $testSectionId,
            ':title' => 'Licao de Teste',
            ':description' => 'Descricao da licao',
            ':content' => '<p>Conteudo HTML de teste</p>',
            ':video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ':video_duration' => 180,
            ':material_file' => null,
            ':sort_order' => 1,
            ':duration_minutes' => 3,
            ':is_preview' => 1,
        ];
        $lessonCreated = $lessonModel->create($lessonData);
        test("create() - Criar licao", $lessonCreated, $passed, $failed, $errors);

        $stmt = $db->prepare("SELECT id FROM lessons WHERE section_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$testSectionId]);
        $testLesson = $stmt->fetch();
        $testLessonId = $testLesson ? $testLesson['id'] : null;
        test("Licao criada tem ID no banco", $testLessonId !== null, $passed, $failed, $errors);

        if ($testLessonId) {
            $lessonData[':title'] = 'Licao Atualizada';
            $lessonUpdated = $lessonModel->update($testLessonId, $lessonData);
            test("update() - Atualizar licao", $lessonUpdated, $passed, $failed, $errors);

            $foundLesson = $lessonModel->find($testLessonId);
            test("Licao atualizada inclui course_id", isset($foundLesson['course_id']), $passed, $failed, $errors);

            $lessonDeleted = $lessonModel->delete($testLessonId);
            test("delete() - Deletar licao", $lessonDeleted, $passed, $failed, $errors);

            $deletedLesson = $lessonModel->find($testLessonId);
            test("Licao deletada nao encontrada", $deletedLesson === false || $deletedLesson === null, $passed, $failed, $errors);
        }

        $sectionDeleted = $sectionModel->delete($testSectionId);
        test("delete() - Deletar secao (CASCADE nas licoes)", $sectionDeleted, $passed, $failed, $errors);
    }

    $deleted = $courseModel->delete($testCourseId);
    test("delete() - Deletar curso", $deleted, $passed, $failed, $errors);

    $deletedCheck = $courseModel->find($testCourseId);
    test("Curso deletado nao encontrado no banco", $deletedCheck === false || $deletedCheck === null, $passed, $failed, $errors);
}

// ===================== 11. REGISTRO DE USUARIO =====================

section("11. Registro de Usuario (Fase 6)");
$testEmail = 'teste_auto_' . time() . '@teste.com';

$registerResult = $userModel->create([
    'name' => 'Aluno Teste Automatizado',
    'email' => $testEmail,
    'password' => 'senha123',
    'role' => 'student',
]);
test("create() - Registrar aluno", $registerResult, $passed, $failed, $errors);

$newUser = $userModel->findByEmail($testEmail);
test("findByEmail() - Encontrar aluno criado", $newUser !== null, $passed, $failed, $errors);
test("Role do aluno = 'student'", ($newUser['role'] ?? '') === 'student', $passed, $failed, $errors);
test("Senha armazenada como hash bcrypt", strpos($newUser['password'] ?? '', '$2y$') === 0, $passed, $failed, $errors);
test("password_verify() confirma senha", password_verify('senha123', $newUser['password'] ?? ''), $passed, $failed, $errors);

$duplicateResult = $userModel->create([
    'name' => 'Duplicado',
    'email' => $testEmail,
    'password' => 'outrasenha',
    'role' => 'student',
]);
test("Email duplicado retorna false (nao crash)", $duplicateResult === false, $passed, $failed, $errors);

// ===================== 12. PASSWORD RESET =====================

section("12. Password Reset Token (Fase 6)");
$resetToken = bin2hex(random_bytes(32));

$stmt = $db->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
$insertOk = $stmt->execute([$testEmail, $resetToken]);
test("Inserir token de reset", $insertOk, $passed, $failed, $errors);

$stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
$stmt->execute([$resetToken]);
$resetRecord = $stmt->fetch();
test("Token valido encontrado (dentro de 1h)", $resetRecord !== null && $resetRecord['email'] === $testEmail, $passed, $failed, $errors);

$stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 0 SECOND)");
$stmt->execute(['token_inexistente_xyz']);
$invalidToken = $stmt->fetch();
test("Token invalido nao encontrado", $invalidToken === false, $passed, $failed, $errors);

// Cleanup
$db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$testEmail]);

// ===================== 13. ENROLLMENT + PAINEL ALUNO =====================

section("13. Enrollment e Painel do Aluno (Fase 7)");
if ($newUser) {
    $enrollData = [
        ':user_id' => $newUser['id'],
        ':course_id' => 1,
        ':status' => 'active',
        ':payment_status' => 'free',
    ];
    $enrolled = $enrollmentModel->create($enrollData);
    test("Matricular aluno no curso 1", $enrolled, $passed, $failed, $errors);

    $isEnrolled = $enrollmentModel->isEnrolled($newUser['id'], 1);
    test("isEnrolled() retorna true", $isEnrolled === true, $passed, $failed, $errors);

    $notEnrolled = $enrollmentModel->isEnrolled($newUser['id'], 99999);
    test("isEnrolled() em curso inexistente retorna false", $notEnrolled === false, $passed, $failed, $errors);

    $userEnrollments = $enrollmentModel->getByUser($newUser['id']);
    test("getByUser() retorna matriculas", is_array($userEnrollments) && count($userEnrollments) > 0, $passed, $failed, $errors);
    test("Matricula inclui course_title", !empty($userEnrollments[0]['course_title'] ?? ''), $passed, $failed, $errors);
    test("Matricula inclui course_slug", !empty($userEnrollments[0]['course_slug'] ?? ''), $passed, $failed, $errors);

    // Cleanup
    $db->prepare("DELETE FROM enrollments WHERE user_id = ?")->execute([$newUser['id']]);
}

// ===================== 14. ARQUIVOS DE VIEW =====================

section("14. Views - Arquivos existem");
$viewBase = __DIR__ . '/../views/';
$requiredViews = [
    // Fase 5 - Admin Cursos
    'admin/courses/index.php'   => 'Listagem de cursos',
    'admin/courses/create.php'  => 'Form criar curso',
    'admin/courses/edit.php'    => 'Form editar curso',
    'admin/courses/show.php'    => 'Detalhe do curso',
    'admin/courses/_form.php'   => 'Form reutilizavel curso',
    'admin/lessons/create.php'  => 'Form criar licao',
    'admin/lessons/edit.php'    => 'Form editar licao',
    'admin/lessons/_form.php'   => 'Form reutilizavel licao',
    // Fase 6 - Registro e Senha
    'pages/register.php'        => 'Pagina de registro',
    'pages/forgot-password.php' => 'Pagina esqueci senha',
    'pages/reset-password.php'  => 'Pagina redefinir senha',
    // Fase 7 - Painel Aluno
    'student/dashboard.php'     => 'Dashboard do aluno',
    'student/profile.php'       => 'Perfil do aluno',
    'layouts/student.php'       => 'Layout do aluno',
    // Quiz
    'admin/quizzes/index.php'   => 'Admin lista quizzes',
    'admin/quizzes/edit.php'    => 'Admin editar quiz',
    'pages/quiz-responder.php'  => 'Aluno responder quiz',
    'pages/quiz-resultado.php'  => 'Resultado do quiz',
    // Certificado
    'pages/certificado-verificar.php' => 'Verificar certificado',
    // Relatorios
    'admin/reports/index.php'   => 'Relatorios admin',
    // Matriculas
    'admin/enrollments/index.php' => 'Admin matriculas',
    // Contatos
    'admin/contacts/index.php'  => 'Admin contatos lista',
    'admin/contacts/show.php'   => 'Admin contato detalhe',
];
foreach ($requiredViews as $file => $desc) {
    test("{$file} ({$desc})", file_exists($viewBase . $file), $passed, $failed, $errors);
}

// ===================== 15. ARQUIVOS DE CONTROLLER =====================

section("15. Controllers - Arquivos existem");
$controllerBase = __DIR__ . '/../app/Controllers/';
$requiredControllers = [
    'Admin/CourseAdminController.php'   => 'CRUD Admin Cursos',
    'Admin/SectionAdminController.php'  => 'CRUD Admin Secoes',
    'Admin/LessonAdminController.php'   => 'CRUD Admin Licoes',
    'Admin/QuizAdminController.php'     => 'CRUD Admin Quizzes',
    'Admin/ReportsController.php'       => 'Relatorios Admin',
    'Admin/EnrollmentAdminController.php' => 'CRUD Admin Matriculas',
    'Admin/ContactController.php'       => 'Admin Contatos',
    'QuizController.php'                => 'Quiz Aluno',
    'CertificateController.php'         => 'Certificados',
    'StudentPanelController.php'        => 'Painel do Aluno',
];
foreach ($requiredControllers as $file => $desc) {
    test("{$file} ({$desc})", file_exists($controllerBase . $file), $passed, $failed, $errors);
}

// ===================== 16. SERVICOS =====================

section("16. Servicos e Assets");
test("MailerService.php", file_exists(__DIR__ . '/../app/Services/MailerService.php'), $passed, $failed, $errors);
test("CertificateService.php", file_exists(__DIR__ . '/../app/Services/CertificateService.php'), $passed, $failed, $errors);
test("darkmode.css", file_exists(__DIR__ . '/../public/assets/css/darkmode.css'), $passed, $failed, $errors);
test("darkmode.js", file_exists(__DIR__ . '/../public/assets/js/darkmode.js'), $passed, $failed, $errors);

// ===================== 17. ROTAS =====================

section("17. Rotas registradas em index.php");
$indexContent = file_get_contents(__DIR__ . '/../public/index.php');
$requiredRoutes = [
    // Fase 5
    '/admin/courses'                        => 'GET  Listar cursos',
    '/admin/courses/create'                 => 'GET  Form criar curso',
    '/admin/courses/{id}'                   => 'GET  Detalhe curso',
    '/admin/courses/{id}/edit'              => 'GET  Form editar curso',
    '/admin/courses/{id}/update'            => 'POST Atualizar curso',
    '/admin/courses/{id}/delete'            => 'POST Deletar curso',
    '/admin/courses/{courseId}/sections'     => 'POST Criar secao',
    '/admin/sections/{id}/update'           => 'POST Atualizar secao',
    '/admin/sections/{id}/delete'           => 'POST Deletar secao',
    '/admin/sections/{sectionId}/lessons/create' => 'GET  Form criar licao',
    '/admin/sections/{sectionId}/lessons'   => 'POST Criar licao',
    '/admin/lessons/{id}/edit'              => 'GET  Form editar licao',
    '/admin/lessons/{id}/update'            => 'POST Atualizar licao',
    '/admin/lessons/{id}/delete'            => 'POST Deletar licao',
    // Fase 6
    '/registro'                             => 'GET/POST Registro',
    '/esqueci-senha'                        => 'GET/POST Esqueci senha',
    '/redefinir-senha/{token}'              => 'GET  Form nova senha',
    '/redefinir-senha'                      => 'POST Redefinir senha',
    // Fase 7
    '/minha-conta'                          => 'GET  Dashboard aluno',
    '/minha-conta/perfil'                   => 'GET/POST Perfil aluno',
    '/minha-conta/senha'                    => 'POST Alterar senha',
    // Quiz
    '/admin/courses/{courseId}/quizzes'      => 'GET Admin Quizzes',
    '/admin/quizzes/{id}/edit'              => 'GET Editar Quiz',
    '/admin/quizzes/{quizId}/questions'     => 'POST Add Questao',
    '/curso/{slug}/quiz/{quizId}'           => 'GET Quiz Aluno',
    '/curso/{slug}/quiz/{quizId}/submit'    => 'POST Submit Quiz',
    // Matricula
    '/curso/{slug}/matricular'              => 'POST Matricular',
    // Certificado
    '/certificado/gerar/{enrollmentId}'     => 'GET Gerar Certificado',
    '/certificado/{code}'                   => 'GET Verificar Certificado',
    // Relatorios
    '/admin/reports'                        => 'GET Relatorios',
    // Admin Matriculas
    '/admin/enrollments'                    => 'GET Admin Matriculas',
    '/admin/enrollments/store'              => 'POST Criar Matricula',
    '/admin/enrollments/{id}/activate'      => 'POST Ativar',
    '/admin/enrollments/{id}/delete'        => 'POST Deletar Matricula',
    // Admin Contatos
    '/admin/contacts'                       => 'GET Admin Contatos',
    '/admin/contacts/{id}'                  => 'GET Ver Contato',
    '/admin/contacts/{id}/delete'           => 'POST Deletar Contato',
];
foreach ($requiredRoutes as $route => $desc) {
    $found = strpos($indexContent, "'{$route}'") !== false || strpos($indexContent, "\"{$route}\"") !== false;
    test("{$route} ({$desc})", $found, $passed, $failed, $errors);
}

// ===================== 18. INTEGRACAO - SIDEBAR E LOGIN =====================

section("18. Integracao - Sidebar Admin e Login Page");

$adminLayout = file_get_contents($viewBase . 'layouts/admin.php');
test("Sidebar admin tem link '/admin/courses'", strpos($adminLayout, '/admin/courses') !== false, $passed, $failed, $errors);
test("Sidebar admin tem link '/admin/enrollments'", strpos($adminLayout, '/admin/enrollments') !== false, $passed, $failed, $errors);
test("Sidebar admin tem link '/admin/reports'", strpos($adminLayout, '/admin/reports') !== false, $passed, $failed, $errors);
test("Admin layout tem darkmode.css", strpos($adminLayout, 'darkmode.css') !== false, $passed, $failed, $errors);
test("Admin layout tem darkmode.js", strpos($adminLayout, 'darkmode.js') !== false, $passed, $failed, $errors);
test("Admin layout tem dark-mode-toggle", strpos($adminLayout, 'dark-mode-toggle') !== false, $passed, $failed, $errors);

$publicLayout = file_get_contents($viewBase . 'layouts/public.php');
test("Public layout tem darkmode.css", strpos($publicLayout, 'darkmode.css') !== false, $passed, $failed, $errors);
test("Public layout tem dark-mode-toggle", strpos($publicLayout, 'dark-mode-toggle') !== false, $passed, $failed, $errors);

$studentLayout = file_get_contents($viewBase . 'layouts/student.php');
test("Student layout tem darkmode.css", strpos($studentLayout, 'darkmode.css') !== false, $passed, $failed, $errors);
test("Student layout tem dark-mode-toggle", strpos($studentLayout, 'dark-mode-toggle') !== false, $passed, $failed, $errors);

$courseShowAdmin = file_get_contents($viewBase . 'admin/courses/show.php');
test("Admin course show tem botao Quizzes", strpos($courseShowAdmin, '/quizzes') !== false, $passed, $failed, $errors);

$cursoDetalhe = file_get_contents($viewBase . 'pages/curso-detalhe.php');
test("Curso detalhe mostra quizzes", strpos($cursoDetalhe, 'quiz') !== false, $passed, $failed, $errors);

$loginPage = file_get_contents($viewBase . 'pages/login.php');
test("Sidebar admin tem link '/admin/contacts'", strpos($adminLayout, '/admin/contacts') !== false, $passed, $failed, $errors);

$loginPage = file_get_contents($viewBase . 'pages/login.php');
test("Login tem link '/registro'", strpos($loginPage, '/registro') !== false, $passed, $failed, $errors);
test("Login tem link '/esqueci-senha'", strpos($loginPage, '/esqueci-senha') !== false, $passed, $failed, $errors);

// Dashboard dinamico
$dashboardView = file_get_contents($viewBase . 'admin/dashboard.php');
test("Dashboard usa dados dinamicos (stats)", strpos($dashboardView, "\$stats['total_students']") !== false, $passed, $failed, $errors);
test("Dashboard nao tem dados hardcoded '1.250'", strpos($dashboardView, '1.250') === false, $passed, $failed, $errors);

// Cursos dinamicos
$cursosView = file_get_contents($viewBase . 'pages/cursos.php');
test("Cursos page usa foreach dinamico", strpos($cursosView, 'foreach ($courses') !== false, $passed, $failed, $errors);

// PageController busca cursos do DB
$pageController = file_get_contents(__DIR__ . '/../app/Controllers/PageController.php');
test("PageController::cursos() busca do banco", strpos($pageController, 'SELECT * FROM courses') !== false, $passed, $failed, $errors);

// Contatos salvam no DB
test("PageController::contato() salva no banco", strpos($pageController, 'INSERT INTO contacts') !== false, $passed, $failed, $errors);

// .env.example
test(".env.example existe", file_exists(__DIR__ . '/../.env.example'), $passed, $failed, $errors);

// ===================== CLEANUP =====================

if (isset($newUser) && $newUser) {
    $userModel->delete($newUser['id']);
}

// ===================== RESULTADO FINAL =====================

echo "\n\033[1m============================================\033[0m\n";
if ($failed === 0) {
    echo "\033[1;32m RESULTADO: {$passed} passed, {$failed} failed ✓\033[0m\n";
} else {
    echo "\033[1;31m RESULTADO: {$passed} passed, {$failed} failed ✗\033[0m\n";
}
echo "\033[1m============================================\033[0m\n";

if (!empty($errors)) {
    echo "\n\033[31mFalhas:\033[0m\n";
    foreach ($errors as $err) {
        echo "  \033[31m✗\033[0m {$err}\n";
    }
}

echo "\n";
exit($failed > 0 ? 1 : 0);
