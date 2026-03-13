<?php
/**
 * 重置管理员密码脚本
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

// 连接到数据库
try {
    $dsn = sprintf(
        "mysql:host=%s;port=%d;dbname=%s;charset=%s",
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['name'],
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

    echo "✓ 成功连接到数据库\n\n";

    // 生成新的密码哈希
    $newPassword = 'admin123';
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    echo "更新 admin 账户密码...\n";
    echo "新密码: $newPassword\n";
    echo "密码哈希: $passwordHash\n\n";

    // 检查 admin 用户是否存在
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $user = $stmt->fetch();

    if ($user) {
        // 更新密码
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
        $stmt->execute([$passwordHash, 'admin']);
        echo "✓ admin 账户密码已更新\n";
    } else {
        // 创建 admin 用户
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@example.com', $passwordHash, 'admin']);
        echo "✓ admin 账户已创建\n";
    }

    echo "\n现在可以使用以下信息登录:\n";
    echo "  用户名: admin\n";
    echo "  密码: admin123\n";

} catch (PDOException $e) {
    die("✗ 数据库操作失败: " . $e->getMessage() . "\n");
}