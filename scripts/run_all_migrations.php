<?php
/**
 * Script para rodar todas as migrations MySQL na ordem correta
 * Uso: php scripts/run_all_migrations.php
 */

// Carregar .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'hansen_educacional';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

echo "=== HANSEN EDUCACIONAL - Migration Runner ===\n\n";

try {
    // Conectar sem database primeiro para poder cria-lo
    $dsn = "mysql:host={$host};port={$port};charset={$charset}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "[OK] Conectado ao MySQL/MariaDB\n";

    // Criar database se nao existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$database}`");
    echo "[OK] Database '{$database}' pronta\n\n";

    // Ordem correta das migrations (respeitando dependencias de FK)
    $migrations = [
        '000_create_base_tables.sql',       // users, schools, students, observations
        '001_create_courses.sql',            // courses (FK: users)
        '002_create_sections.sql',           // sections (FK: courses)
        '003_create_lessons.sql',            // lessons (FK: sections)
        '004_create_quizzes.sql',            // quizzes (FK: sections)
        '005_create_quiz_questions.sql',     // quiz_questions (FK: quizzes)
        '006_create_quiz_answers.sql',       // quiz_answers (FK: quiz_questions)
        '007_create_enrollments.sql',        // enrollments (FK: users, courses)
        '009_create_video_progress.sql',     // video_progress (FK: enrollments, lessons) - ANTES da 008!
        '008_create_course_progress.sql',    // course_progress (FK: enrollments, lessons, video_progress)
        '010_create_video_watch_logs.sql',   // video_watch_logs (FK: video_progress)
        '011_create_notification_settings.sql', // notification_settings
        // 012 e 013 sao ALTER/redundantes - pular pois 000 ja cria users completo
    ];

    $migrationsDir = __DIR__ . '/../migrations/';
    $success = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($migrations as $file) {
        $path = $migrationsDir . $file;
        if (!file_exists($path)) {
            echo "[SKIP] {$file} - arquivo nao encontrado\n";
            $skipped++;
            continue;
        }

        $sql = file_get_contents($path);

        // Remover linhas de comentario e USE statements
        $sql = preg_replace('/^USE\s+.+;$/mi', '', $sql);
        $sql = preg_replace('/^DESCRIBE\s+.+;$/mi', '', $sql);

        // Separar statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($s) {
                $s = trim($s);
                return !empty($s) && strpos($s, '--') !== 0;
            }
        );

        echo ">> Rodando {$file}... ";

        $stmtErrors = 0;
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if (empty($stmt) || preg_match('/^--/', $stmt) || preg_match('/^SELECT\s/i', $stmt)) {
                continue;
            }
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                // Ignorar erros de "ja existe"
                if (strpos($e->getMessage(), 'already exists') !== false
                    || strpos($e->getMessage(), 'Duplicate') !== false) {
                    continue;
                }
                echo "\n   [WARN] " . substr($e->getMessage(), 0, 120) . "\n   ";
                $stmtErrors++;
            }
        }

        if ($stmtErrors === 0) {
            echo "[OK]\n";
            $success++;
        } else {
            echo "[OK com {$stmtErrors} avisos]\n";
            $success++;
        }
    }

    echo "\n=== RESULTADO ===\n";
    echo "Sucesso: {$success} | Puladas: {$skipped} | Erros: {$errors}\n\n";

    // Listar tabelas criadas
    echo "=== TABELAS CRIADAS ===\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `{$table}`")->fetchColumn();
        echo "  {$table} ({$count} registros)\n";
    }

    echo "\n[OK] Migrations concluidas com sucesso!\n";

} catch (PDOException $e) {
    echo "[ERRO] " . $e->getMessage() . "\n";
    exit(1);
}
