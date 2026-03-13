<?php

namespace App;

use PDO;
use PDOException;

/**
 * 用户认证服务类
 * 处理用户注册、登录、登出等操作
 */
class AuthService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 注册新用户
     */
    public function register(string $username, string $password, string $email = ''): array
    {
        try {
            // 检查用户名是否已存在
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                return [
                    'success' => false,
                    'message' => '用户名已存在'
                ];
            }

            // 检查邮箱是否已存在
            if ($email) {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    return [
                        'success' => false,
                        'message' => '邮箱已被注册'
                    ];
                }
            }

            // 加密密码
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 插入新用户
            $stmt = $this->db->prepare("
                INSERT INTO users (username, password_hash, email, role, created_at)
                VALUES (?, ?, ?, 'user', NOW())
            ");
            $stmt->execute([$username, $hashedPassword, $email]);

            return [
                'success' => true,
                'message' => '注册成功',
                'user_id' => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => '注册失败：' . $e->getMessage()
            ];
        }
    }

    /**
     * 用户登录
     */
    public function login(string $username, string $password): array
    {
        try {
            // 查找用户
            $stmt = $this->db->prepare("
                SELECT id, username, password_hash, role, avatar, bio, created_at
                FROM users
                WHERE username = ?
            ");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => '用户名或密码错误'
                ];
            }

            // 验证密码
            if (!password_verify($password, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => '用户名或密码错误'
                ];
            }

            // 启动 session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // 设置 session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = ($user['role'] === 'admin');

            // 返回用户信息（不包含密码）
            unset($user['password']);
            $user['is_admin'] = ($user['role'] === 'admin');

            return [
                'success' => true,
                'message' => '登录成功',
                'user' => $user
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => '登录失败：' . $e->getMessage()
            ];
        }
    }

    /**
     * 用户登出
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 清除 session
        $_SESSION = [];

        // 删除 session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // 销毁 session
        session_destroy();
    }

    /**
     * 获取当前登录用户信息
     */
    public function getCurrentUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT id, username, email, role, avatar, bio, created_at
                FROM users
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if ($user) {
                $user['is_admin'] = ($user['role'] === 'admin');
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * 检查用户是否已登录
     */
    public function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    /**
     * 检查当前用户是否是管理员
     */
    public function isAdmin(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['is_admin'] ?? false;
    }

    /**
     * 更新用户信息
     */
    public function updateUser(int $userId, array $data): array
    {
        try {
            $updates = [];
            $params = [];

            if (isset($data['email'])) {
                // 检查邮箱是否已被其他用户使用
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$data['email'], $userId]);
                if ($stmt->fetch()) {
                    return [
                        'success' => false,
                        'message' => '邮箱已被其他用户使用'
                    ];
                }
                $updates[] = "email = ?";
                $params[] = $data['email'];
            }

            if (isset($data['avatar'])) {
                $updates[] = "avatar = ?";
                $params[] = $data['avatar'];
            }

            if (isset($data['bio'])) {
                $updates[] = "bio = ?";
                $params[] = $data['bio'];
            }

            if (empty($updates)) {
                return [
                    'success' => false,
                    'message' => '没有需要更新的信息'
                ];
            }

            $params[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return [
                'success' => true,
                'message' => '更新成功'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => '更新失败：' . $e->getMessage()
            ];
        }
    }

    /**
     * 修改密码
     */
    public function changePassword(int $userId, string $oldPassword, string $newPassword): array
    {
        try {
            // 验证旧密码
            $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => '用户不存在'
                ];
            }

            if (!password_verify($oldPassword, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => '旧密码错误'
                ];
            }

            // 更新密码
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $userId]);

            return [
                'success' => true,
                'message' => '密码修改成功'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => '修改失败：' . $e->getMessage()
            ];
        }
    }
}