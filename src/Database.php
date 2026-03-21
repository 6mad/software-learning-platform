<?php

namespace App;

use PDO;
use PDOException;

/**
 * 数据库连接类
 */
class Database
{
    private static ?PDO $instance = null;
    private array $config;
    private static bool $envLoaded = false;

    /**
     * 加载 .env 文件
     */
    private static function loadEnv(): void
    {
        if (self::$envLoaded) {
            return;
        }

        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                if (!array_key_exists($name, $_SERVER)) {
                    $_SERVER[$name] = $value;
                    putenv("$name=$value");
                    $_ENV[$name] = $value;
                }
            }
        }

        self::$envLoaded = true;
    }

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 获取数据库单例实例
     */
    public static function getInstance(array $config = []): PDO
    {
        // 加载 .env 文件
        self::loadEnv();

        if (self::$instance === null) {
            try {
                $db = new self($config);
                self::$instance = $db->createConnection();
            } catch (PDOException $e) {
                throw new \Exception("数据库连接失败: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * 创建数据库连接
     */
    private function createConnection(): PDO
    {
        $dsn = sprintf(
            "mysql:unix_socket=/data/data/com.termux/files/usr/var/run/mysqld.sock;dbname=%s;charset=%s",
            $this->config['name'] ?? 'software_learning_platform',
            $this->config['charset'] ?? 'utf8mb4'
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO(
            $dsn,
            $this->config['user'] ?? 'root',
            $this->config['password'] ?? '',
            $options
        );

        return $pdo;
    }

    /**
     * 关闭数据库连接
     */
    public static function close(): void
    {
        self::$instance = null;
    }

    /**
     * 测试数据库连接
     */
    public static function testConnection(array $config): bool
    {
        try {
            $db = new self($config);
            $pdo = $db->createConnection();
            $pdo->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}