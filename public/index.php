<?php
// Deploy test - 2026-02-21
session_start();

// Gerar CSRF token se nao existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Autoloader manual robusto
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router\Router;
use App\Controllers\PageController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\StudentController as AdminStudentController;
use App\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Controllers\Admin\ObservationController as AdminObservationController;
use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\AuthController;
use App\Controllers\Api\VideoTrackingController;
use App\Controllers\CourseController;
use App\Controllers\Admin\VideoAdminController;
use App\Controllers\Admin\CourseAdminController;
use App\Controllers\Admin\ModuleAdminController;
use App\Controllers\Admin\SectionAdminController;
use App\Controllers\Admin\LessonAdminController;
use App\Controllers\StudentPanelController;
use App\Controllers\CertificateController;
use App\Controllers\QuizController;
use App\Controllers\Admin\QuizAdminController;
use App\Controllers\Admin\ReportsController;
use App\Controllers\Admin\EnrollmentAdminController;
use App\Controllers\Admin\ContactController as AdminContactController;
use App\Controllers\Admin\CourseMaterialController;
use App\Controllers\StudentMaterialController;
use App\Controllers\ParentPanelController;
use App\Controllers\Admin\ParentLinkController;
use App\Controllers\CourseMessageController;
use App\Controllers\Admin\MessageController as AdminMessageController;
use App\Controllers\Admin\ClassroomController as AdminClassroomController;
use App\Controllers\Admin\PlanningTemplateController as AdminPlanningTemplateController;
use App\Controllers\Admin\PlanningController as AdminPlanningController;
use App\Controllers\Admin\HelpController as AdminHelpController;

$router = new Router();

// Rotas Públicas
$router->get('/', [PageController::class, 'home']);
$router->get('/programas', [PageController::class, 'programas']);
$router->get('/palestras', [PageController::class, 'palestras']);
$router->get('/cursos', [PageController::class, 'cursos']);
$router->get('/livros', [PageController::class, 'livros']);
$router->get('/contato', [PageController::class, 'contato']);
$router->post('/contato', [PageController::class, 'contato']);
$router->get('/termos-de-uso', [PageController::class, 'termosDeUso']);
$router->get('/politica-privacidade', [PageController::class, 'politicaPrivacidade']);

// Autenticação
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/registro', [AuthController::class, 'showRegister']);
$router->post('/registro', [AuthController::class, 'register']);
$router->get('/esqueci-senha', [AuthController::class, 'showForgotPassword']);
$router->post('/esqueci-senha', [AuthController::class, 'sendResetLink']);
$router->get('/redefinir-senha/{token}', [AuthController::class, 'showResetForm']);
$router->post('/redefinir-senha', [AuthController::class, 'resetPassword']);

// Rotas API (Protegidas por ApiAuthMiddleware)
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (strpos($uri, '/api/') === 0) {
    \App\Middleware\ApiAuthMiddleware::handle();
}

// Rotas Admin (Protegidas)
if (strpos($uri, '/admin/') === 0) {
    \App\Middleware\AuthMiddleware::handle();
}

// Rotas Minha Conta (Protegidas - qualquer usuário logado)
if (strpos($uri, '/minha-conta') === 0) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}

// Rotas Minha Area (Protegidas - apenas parent)
if (strpos($uri, '/minha-area') === 0) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}

$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// Rotas de Alunos
$router->get('/admin/students', [AdminStudentController::class, 'index']);
$router->get('/admin/students/create', [AdminStudentController::class, 'create']);
$router->post('/admin/students/create', [AdminStudentController::class, 'store']);
$router->get('/admin/students/{id}', [AdminStudentController::class, 'show']);
$router->get('/admin/students/{id}/edit', [AdminStudentController::class, 'edit']);
$router->post('/admin/students/{id}/update', [AdminStudentController::class, 'update']);
$router->post('/admin/students/{id}/delete', [AdminStudentController::class, 'delete']);
$router->get('/admin/students/{id}/ai-summary', [AdminStudentController::class, 'generateAiSummary']);

// Rotas de Escolas
$router->get('/admin/schools', [AdminSchoolController::class, 'index']);
$router->get('/admin/schools/create', [AdminSchoolController::class, 'create']);
$router->post('/admin/schools', [AdminSchoolController::class, 'store']);
$router->get('/admin/schools/{id}', [AdminSchoolController::class, 'show']);
$router->get('/admin/schools/{id}/edit', [AdminSchoolController::class, 'edit']);
$router->post('/admin/schools/{id}/update', [AdminSchoolController::class, 'update']);
$router->post('/admin/schools/{id}/delete', [AdminSchoolController::class, 'delete']);

// Rotas de Observações
$router->get('/admin/observations', [AdminObservationController::class, 'index']);
$router->get('/admin/observations/create', [AdminObservationController::class, 'create']);
$router->post('/admin/observations', [AdminObservationController::class, 'store']);
$router->get('/admin/observations/{id}', [AdminObservationController::class, 'show']);
$router->get('/admin/observations/{id}/edit', [AdminObservationController::class, 'edit']);
$router->post('/admin/observations/{id}/update', [AdminObservationController::class, 'update']);
$router->post('/admin/observations/{id}/delete', [AdminObservationController::class, 'delete']);

// Rotas de Usuários
$router->get('/admin/users', [AdminUserController::class, 'index']);
$router->get('/admin/users/create', [AdminUserController::class, 'create']);
$router->post('/admin/users', [AdminUserController::class, 'store']);
$router->get('/admin/users/{id}/edit', [AdminUserController::class, 'edit']);
$router->post('/admin/users/{id}/update', [AdminUserController::class, 'update']);
$router->post('/admin/users/{id}/delete', [AdminUserController::class, 'delete']);

// Rotas Admin - Cursos
$router->get('/admin/courses', [CourseAdminController::class, 'index']);
$router->get('/admin/courses/create', [CourseAdminController::class, 'create']);
$router->post('/admin/courses', [CourseAdminController::class, 'store']);
$router->get('/admin/courses/{id}', [CourseAdminController::class, 'show']);
$router->get('/admin/courses/{id}/edit', [CourseAdminController::class, 'edit']);
$router->post('/admin/courses/{id}/update', [CourseAdminController::class, 'update']);
$router->post('/admin/courses/{id}/delete', [CourseAdminController::class, 'delete']);

// Rotas Admin - Materiais de Apoio
$router->get('/admin/courses/{courseId}/materials', [CourseMaterialController::class, 'index']);
$router->get('/admin/courses/{courseId}/materials/create', [CourseMaterialController::class, 'create']);
$router->post('/admin/courses/{courseId}/materials/create', [CourseMaterialController::class, 'store']);
$router->post('/admin/materials/{id}/delete', [CourseMaterialController::class, 'delete']);
$router->get('/admin/materials/{id}/download', [CourseMaterialController::class, 'download']);

// Rotas Admin - Módulos
$router->post('/admin/courses/{courseId}/modules', [ModuleAdminController::class, 'store']);
$router->post('/admin/modules/{id}/update', [ModuleAdminController::class, 'update']);
$router->post('/admin/modules/{id}/delete', [ModuleAdminController::class, 'delete']);
$router->post('/admin/modules/{id}/reorder', [ModuleAdminController::class, 'reorder']);

// Rotas Admin - Seções
$router->post('/admin/courses/{courseId}/sections', [SectionAdminController::class, 'store']);
$router->post('/admin/sections/move-module', [SectionAdminController::class, 'moveModule']);
$router->post('/admin/sections/{id}/update', [SectionAdminController::class, 'update']);
$router->post('/admin/sections/{id}/delete', [SectionAdminController::class, 'delete']);
$router->post('/admin/sections/{id}/reorder', [SectionAdminController::class, 'reorder']);

// Rotas Admin - Lições
$router->get('/admin/sections/{sectionId}/lessons/create', [LessonAdminController::class, 'create']);
$router->post('/admin/sections/{sectionId}/lessons', [LessonAdminController::class, 'store']);
$router->get('/admin/lessons/{id}/edit', [LessonAdminController::class, 'edit']);
$router->post('/admin/lessons/{id}/update', [LessonAdminController::class, 'update']);
$router->post('/admin/lessons/{id}/delete', [LessonAdminController::class, 'delete']);
$router->post('/admin/lessons/{id}/reorder', [LessonAdminController::class, 'reorder']);

// Rotas Admin - Video Dashboard
$router->get('/admin/video-dashboard', [VideoAdminController::class, 'index']);
$router->get('/admin/video-dashboard/aluno/{enrollmentId}', [VideoAdminController::class, 'studentDetail']);

// Rotas Admin - Quizzes
$router->get('/admin/courses/{courseId}/quizzes', [QuizAdminController::class, 'index']);
$router->post('/admin/courses/{courseId}/quizzes', [QuizAdminController::class, 'store']);
$router->get('/admin/quizzes/{id}/edit', [QuizAdminController::class, 'edit']);
$router->post('/admin/quizzes/{id}/update', [QuizAdminController::class, 'update']);
$router->post('/admin/quizzes/{id}/delete', [QuizAdminController::class, 'delete']);
$router->post('/admin/quizzes/{quizId}/questions', [QuizAdminController::class, 'addQuestion']);
$router->post('/admin/questions/{id}/delete', [QuizAdminController::class, 'deleteQuestion']);

// Rotas Admin - Contatos
$router->get('/admin/contacts', [AdminContactController::class, 'index']);
$router->get('/admin/contacts/{id}', [AdminContactController::class, 'show']);
$router->post('/admin/contacts/{id}/delete', [AdminContactController::class, 'delete']);

// Rotas Admin - Relatórios
$router->get('/admin/reports', [ReportsController::class, 'index']);
$router->get('/admin/reports/low-scores', [ReportsController::class, 'lowScores']);
$router->post('/admin/quiz/{quizId}/reset-attempts', [ReportsController::class, 'resetAttempts']);

// Rotas Admin - Matrículas
$router->get('/admin/enrollments', [EnrollmentAdminController::class, 'index']);
$router->post('/admin/enrollments/store', [EnrollmentAdminController::class, 'store']);
$router->post('/admin/enrollments/{id}/activate', [EnrollmentAdminController::class, 'activate']);
$router->post('/admin/enrollments/{id}/deactivate', [EnrollmentAdminController::class, 'deactivate']);
$router->post('/admin/enrollments/{id}/delete', [EnrollmentAdminController::class, 'delete']);

// Rotas Materiais (Aluno)
$router->get('/curso/{slug}/materiais', [StudentMaterialController::class, 'index']);
$router->get('/material/{id}/download', [StudentMaterialController::class, 'download']);

// Rotas de Cursos (Player)
$router->get('/curso/{slug}', [CourseController::class, 'show']);
$router->post('/curso/{slug}/matricular', [CourseController::class, 'enroll']);
$router->get('/curso/{slug}/licao/{lessonId}', [CourseController::class, 'player']);

// Rotas Quiz (Aluno)
$router->get('/curso/{slug}/quiz/{quizId}', [QuizController::class, 'show']);
$router->post('/curso/{slug}/quiz/{quizId}/submit', [QuizController::class, 'submit']);

// Rotas API - Video Tracking
$router->get('/api/video-progress/{enrollmentId}/{lessonId}', [VideoTrackingController::class, 'getProgress']);
$router->post('/api/video-progress', [VideoTrackingController::class, 'saveProgress']);
$router->post('/api/video-start-session', [VideoTrackingController::class, 'startSession']);
$router->post('/api/video-end-session', [VideoTrackingController::class, 'endSession']);
$router->post('/api/course-progress/{enrollmentId}', [VideoTrackingController::class, 'calculateCourseProgress']);

// Rotas Certificado
$router->get('/certificado/gerar/{enrollmentId}', [CertificateController::class, 'generate']);
$router->get('/certificado/{code}', [CertificateController::class, 'verify']);

// Rotas Painel do Aluno
$router->get('/minha-conta', [StudentPanelController::class, 'dashboard']);
$router->get('/minha-conta/perfil', [StudentPanelController::class, 'profile']);
$router->post('/minha-conta/perfil', [StudentPanelController::class, 'updateProfile']);
$router->post('/minha-conta/senha', [StudentPanelController::class, 'changePassword']);

// Rotas Painel dos Pais
$router->get('/minha-area', [ParentPanelController::class, 'dashboard']);
$router->get('/minha-area/perfil', [ParentPanelController::class, 'profile']);
$router->post('/minha-area/perfil', [ParentPanelController::class, 'updateProfile']);
$router->get('/minha-area/filho/{studentId}', [ParentPanelController::class, 'childDetail']);

// Course Q&A (student + staff)
$router->get('/curso/{slug}/perguntas', [CourseMessageController::class, 'index']);
$router->get('/curso/{slug}/pergunta/{messageId}', [CourseMessageController::class, 'thread']);
$router->post('/curso/{slug}/perguntas/nova', [CourseMessageController::class, 'ask']);
$router->post('/curso/{slug}/pergunta/{messageId}/responder', [CourseMessageController::class, 'reply']);

// Admin messages
$router->get('/admin/messages', [AdminMessageController::class, 'index']);

// Rotas Admin - Responsaveis
$router->get('/admin/parents', [ParentLinkController::class, 'index']);
$router->get('/admin/parents/{parentId}/link', [ParentLinkController::class, 'linkForm']);
$router->post('/admin/parents/{parentId}/link', [ParentLinkController::class, 'link']);
$router->post('/admin/parents/unlink/{linkId}', [ParentLinkController::class, 'unlink']);

// Rotas Admin - Turmas
$router->get('/admin/classrooms', [AdminClassroomController::class, 'index']);
$router->get('/admin/classrooms/create', [AdminClassroomController::class, 'create']);
$router->post('/admin/classrooms', [AdminClassroomController::class, 'store']);
$router->get('/admin/classrooms/{id}/edit', [AdminClassroomController::class, 'edit']);
$router->post('/admin/classrooms/{id}/update', [AdminClassroomController::class, 'update']);
$router->post('/admin/classrooms/{id}/delete', [AdminClassroomController::class, 'delete']);

// Rotas Admin - Templates de Planejamento
$router->get('/admin/planning-templates', [AdminPlanningTemplateController::class, 'index']);
$router->get('/admin/planning-templates/create', [AdminPlanningTemplateController::class, 'create']);
$router->post('/admin/planning-templates', [AdminPlanningTemplateController::class, 'store']);
$router->get('/admin/planning-templates/{id}/edit', [AdminPlanningTemplateController::class, 'edit']);
$router->post('/admin/planning-templates/{id}/update', [AdminPlanningTemplateController::class, 'update']);
$router->post('/admin/planning-templates/{id}/delete', [AdminPlanningTemplateController::class, 'delete']);
$router->post('/admin/planning-templates/{id}/sections', [AdminPlanningTemplateController::class, 'addSection']);
$router->post('/admin/planning-templates/sections/{id}/update', [AdminPlanningTemplateController::class, 'updateSection']);
$router->post('/admin/planning-templates/sections/{id}/delete', [AdminPlanningTemplateController::class, 'deleteSection']);
$router->post('/admin/planning-templates/sections/{id}/fields', [AdminPlanningTemplateController::class, 'addField']);
$router->post('/admin/planning-templates/fields/{id}/delete', [AdminPlanningTemplateController::class, 'deleteField']);

// Rotas Admin - Planejamentos
$router->get('/admin/planning', [AdminPlanningController::class, 'index']);
$router->get('/admin/planning/create', [AdminPlanningController::class, 'create']);
$router->post('/admin/planning', [AdminPlanningController::class, 'store']);
$router->get('/admin/planning/{id}', [AdminPlanningController::class, 'show']);
$router->get('/admin/planning/{id}/edit', [AdminPlanningController::class, 'edit']);
$router->post('/admin/planning/{id}/update', [AdminPlanningController::class, 'update']);
$router->post('/admin/planning/{id}/delete', [AdminPlanningController::class, 'delete']);

// Rotas Admin - Central de Ajuda
$router->get('/admin/help', [AdminHelpController::class, 'index']);
$router->get('/admin/help/{category}', [AdminHelpController::class, 'category']);
$router->get('/admin/help/{category}/{article}', [AdminHelpController::class, 'article']);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($method, $uri);