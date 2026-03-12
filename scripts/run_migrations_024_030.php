<?php
/**
 * Executa migrations 024 a 030
 * Uso: php scripts/run_migrations_024_030.php
 */

require_once __DIR__ . '/../app/Core/Database/Connection.php';

// Carregar .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            putenv(trim($line));
        }
    }
}

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$db   = getenv('DB_DATABASE') ?: 'hansen_educacional';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Conectado ao MySQL ({$host}:{$port}/{$db})\n\n";
} catch (PDOException $e) {
    die("Erro de conexao: " . $e->getMessage() . "\n");
}

$migrations = [
    '024_create_classroom_students.sql',
    '025_alter_observations_add_axes.sql',
    '026_create_descriptive_reports.sql',
    '027_create_image_bank.sql',
    '028_create_portfolios.sql',
    '029_create_support_materials.sql',
    '030_create_notifications.sql',
];

$migrationDir = __DIR__ . '/../migrations/';

foreach ($migrations as $file) {
    $path = $migrationDir . $file;
    if (!file_exists($path)) {
        echo "[SKIP] {$file} - arquivo nao encontrado\n";
        continue;
    }

    $sql = file_get_contents($path);
    if (empty(trim($sql))) {
        echo "[SKIP] {$file} - arquivo vazio\n";
        continue;
    }

    try {
        $pdo->exec($sql);
        echo "[OK]   {$file}\n";
    } catch (PDOException $e) {
        $msg = $e->getMessage();
        // Ignorar erros de tabela/coluna ja existente
        if (str_contains($msg, 'already exists') || str_contains($msg, 'Duplicate column')) {
            echo "[SKIP] {$file} - ja existe\n";
        } else {
            echo "[ERRO] {$file} - {$msg}\n";
        }
    }
}

echo "\nMigrations concluidas!\n";
