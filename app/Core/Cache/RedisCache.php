<?php

namespace App\Core\Cache;

use App\Core\Database\Connection;

class RedisCache
{
    private static $prefix = 'hansen:';
    private static $ttl = 300; // 5 minutos
    
    /**
     * Definir prefixo para cache
     */
    public static function setPrefix($prefix)
    {
        self::$prefix = $prefix;
    }
    
    /**
     * Definir tempo de vida padrão (segundos)
     */
    public static function setTtl($seconds)
    {
        self::$ttl = $seconds;
    }
    
    /**
     * Obter valor do cache
     */
    public static function get($key, $default = null)
    {
        if (!Connection::hasRedis()) {
            return $default;
        }
        
        try {
            $redis = Connection::getRedis();
            $key = self::$prefix . $key;
            $value = $redis->get($key);
            
            return $value !== false ? json_decode($value, true) : $default;
        } catch (\Exception $e) {
            error_log("Erro ao ler do Redis: " . $e->getMessage());
            return $default;
        }
    }
    
    /**
     * Definir valor no cache
     */
    public static function set($key, $value, $ttl = null)
    {
        if (!Connection::hasRedis()) {
            return false;
        }
        
        try {
            $redis = Connection::getRedis();
            $key = self::$prefix . $key;
            $serialized = json_encode($value);
            $ttl = $ttl !== null ? $ttl : self::$ttl;
            
            return $redis->setex($key, $ttl, $serialized);
        } catch (\Exception $e) {
            error_log("Erro ao escrever no Redis: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deletar valor do cache
     */
    public static function delete($key)
    {
        if (!Connection::hasRedis()) {
            return false;
        }
        
        try {
            $redis = Connection::getRedis();
            $key = self::$prefix . $key;
            return $redis->del($key) > 0;
        } catch (\Exception $e) {
            error_log("Erro ao deletar do Redis: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Limpar todo o cache (usar com cautela!)
     */
    public static function flush()
    {
        if (!Connection::hasRedis()) {
            return false;
        }
        
        try {
            $redis = Connection::getRedis();
            $keys = $redis->keys(self::$prefix . '*');
            
            if (!empty($keys)) {
                $redis->del($keys);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao limpar Redis: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obter ou definir valor (caching)
     */
    public static function remember($key, $callback, $ttl = null)
    {
        $value = self::get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = is_callable($callback) ? $callback() : $callback;
        self::set($key, $value, $ttl);
        
        return $value;
    }
}
