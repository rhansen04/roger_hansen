<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class Connection
{
    private static $mysqlInstance = null;
    private static $redisInstance = null;
    
    /**
     * Obter conexão MySQL ou SQLite
     */
    public static function getInstance()
    {
        if (self::$mysqlInstance === null) {
            // Carregar .env se existir
            $envFile = __DIR__ . '/../../../.env';
            if (file_exists($envFile) && !getenv('DB_CONNECTION')) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
                    list($key, $value) = explode('=', $line, 2);
                    putenv(trim($key) . '=' . trim($value));
                }
            }

            $config = require __DIR__ . '/../../Config/database.php';
            $driver = $config['driver'] ?? 'sqlite';

            try {
                if ($driver === 'mysql' && !empty($config['database'])) {
                    // Usar MySQL
                    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
                    self::$mysqlInstance = new PDO($dsn, $config['username'], $config['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE {$config['collation']}"
                    ]);
                } else {
                    // Usar SQLite (desenvolvimento)
                    $dsn = "sqlite:{$config['path']}";
                    self::$mysqlInstance = new PDO($dsn, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    // Habilitar foreign keys no SQLite
                    self::$mysqlInstance->exec("PRAGMA foreign_keys = ON");
                }
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        
        return self::$mysqlInstance;
    }
    
    /**
     * Obter conexão Redis
     */
    public static function getRedis()
    {
        if (self::$redisInstance === null) {
            $config = require __DIR__ . '/../../Config/database.php';
            
            // Verificar se Redis está disponível
            if (isset($config['redis']['host']) && class_exists('Redis')) {
                try {
                    $redisClass = 'Redis';
                    self::$redisInstance = new $redisClass();
                    self::$redisInstance->connect($config['redis']['host'], $config['redis']['port']);
                    
                    // Autenticar se houver senha
                    if (!empty($config['redis']['password'])) {
                        self::$redisInstance->auth($config['redis']['password']);
                    }
                    
                    // Testar conexão
                    self::$redisInstance->ping();
                } catch (\Exception $e) {
                    error_log("Erro ao conectar ao Redis: " . $e->getMessage());
                    self::$redisInstance = null;
                }
            }
        }
        
        return self::$redisInstance;
    }
    
    /**
     * Verificar se Redis está disponível
     */
    public static function hasRedis()
    {
        return self::getRedis() !== null;
    }
    
    /**
     * Limpar todas as conexões (usar em testes)
     */
    public static function reset()
    {
        self::$mysqlInstance = null;
        self::$redisInstance = null;
    }
}
