<?php
/**
 * 数据库初始化脚本
 * 用于创建数据库表结构
 */

// 加载 .env 文件
$envFile = __DIR__ . '/.env';
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

// 加载配置
$configFile = __DIR__ . '/config/app.php';
$config = require $configFile;

$dbConfig = $config['database'];

// 连接到 MySQL 服务器（不指定数据库）
try {
    $dsn = sprintf(
        "mysql:host=%s;port=%d;charset=%s",
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['charset']
    );

    $pdo = new PDO(
        $dsn,
        $dbConfig['user'],
        $dbConfig['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "✓ 成功连接到 MySQL 服务器\n";
    echo "MySQL 版本: " . $pdo->query("SELECT VERSION()")->fetchColumn() . "\n\n";

    // 创建数据库（如果不存在）
    $dbName = $dbConfig['name'];
    echo "检查/创建数据库 '$dbName'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ 数据库准备就绪\n\n";

    // 读取并执行 SQL 脚本
    $sqlFile = __DIR__ . '/database/schema.sql';
    if (!file_exists($sqlFile)) {
        die("✗ SQL 文件不存在: $sqlFile\n");
    }

    $sql = file_get_contents($sqlFile);

    // 分割 SQL 语句（按分号分割）
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    echo "开始执行 SQL 语句...\n\n";

    foreach ($statements as $statement) {
        if (empty($statement)) {
            continue;
        }

        try {
            $pdo->exec($statement);
            echo "✓ 执行成功\n";
        } catch (PDOException $e) {
            echo "✗ 执行失败: " . $e->getMessage() . "\n";
            echo "SQL: " . substr($statement, 0, 100) . "...\n\n";
        }
    }

    echo "\n✓ 数据库初始化完成！\n";
    echo "\n默认管理员账户:\n";
    echo "  用户名: admin\n";
    echo "  密码: admin123\n";

} catch (PDOException $e) {
    die("✗ 数据库连接失败: " . $e->getMessage() . "\n");
}