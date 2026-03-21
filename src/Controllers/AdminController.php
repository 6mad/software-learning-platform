<?php

namespace App\Controllers;

use App\AuthService;
use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * 管理员控制器
 * 处理用户管理、帖子管理等后台功能
 */
class AdminController
{
    private AuthService $authService;
    private \PDO $db;

    public function __construct()
    {
        $configFile = __DIR__ . '/../../config/app.php';
        $appConfig = require $configFile;

        $this->db = Database::getInstance($appConfig['database']);
        $this->authService = new AuthService($this->db);
    }

    /**
     * 检查管理员权限
     */
    private function checkAdmin(): ?array
    {
        $user = $this->authService->getCurrentUser();

        if (!$user || $user['role'] !== 'admin') {
            return null;
        }

        return $user;
    }

    /**
     * 获取所有用户
     */
    public function getUsers(Request $request, Response $response): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $page = (int)($request->getQueryParams()['page'] ?? 1);
        $limit = (int)($request->getQueryParams()['limit'] ?? 20);
        $search = $request->getQueryParams()['search'] ?? '';

        $offset = ($page - 1) * $limit;

        // 构建查询
        $where = '';
        $params = [];

        if ($search) {
            $where = 'WHERE username LIKE :search OR email LIKE :search';
            $params[':search'] = "%$search%";
        }

        // 获取用户列表
        $sql = "SELECT id, username, email, role, created_at FROM users $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll();

        // 获取总数
        $countSql = "SELECT COUNT(*) FROM users $where";
        $countStmt = $this->db->prepare($countSql);

        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }

        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return $this->jsonResponse($response, [
            'success' => true,
            'data' => [
                'users' => $users,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]
        ]);
    }

    /**
     * 删除用户
     */
    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $userId = (int)$args['id'];

        // 不允许删除自己
        if ($userId === $admin['id']) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '不能删除自己'
            ], 400);
        }

        // 开始事务
        $this->db->beginTransaction();

        try {
            // 删除用户的回复点赞
            $this->db->prepare("DELETE FROM reply_likes WHERE user_id = ?")->execute([$userId]);

            // 删除用户的帖子点赞
            $this->db->prepare("DELETE FROM post_likes WHERE user_id = ?")->execute([$userId]);

            // 删除用户的回复
            $this->db->prepare("DELETE FROM forum_replies WHERE user_id = ?")->execute([$userId]);

            // 删除用户的帖子
            $this->db->prepare("DELETE FROM forum_posts WHERE user_id = ?")->execute([$userId]);

            // 删除用户
            $this->db->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

            $this->db->commit();

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => '用户删除成功'
            ]);
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '删除失败: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 修改用户角色
     */
    public function updateUserRole(Request $request, Response $response, array $args): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $userId = (int)$args['id'];
        $data = $request->getParsedBody();
        $role = $data['role'] ?? '';

        // 验证角色
        if (!in_array($role, ['user', 'admin'])) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '无效的角色'
            ], 400);
        }

        // 不允许修改自己的角色
        if ($userId === $admin['id']) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '不能修改自己的角色'
            ], 400);
        }

        // 更新角色
        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $userId]);

        if ($stmt->rowCount() > 0) {
            return $this->jsonResponse($response, [
                'success' => true,
                'message' => '角色更新成功'
            ]);
        } else {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '用户不存在'
            ], 404);
        }
    }

    /**
     * 获取所有帖子（包括已删除的）
     */
    public function getAllPosts(Request $request, Response $response): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $page = (int)($request->getQueryParams()['page'] ?? 1);
        $limit = (int)($request->getQueryParams()['limit'] ?? 20);
        $search = $request->getQueryParams()['search'] ?? '';

        $offset = ($page - 1) * $limit;

        // 构建查询
        $where = 'WHERE 1=1';
        $params = [];

        if ($search) {
            $where .= ' AND (p.title LIKE :search OR p.content LIKE :search OR u.username LIKE :search)';
            $params[':search'] = "%$search%";
        }

        // 获取帖子列表
        $sql = "SELECT p.*, u.username, 
                       (SELECT COUNT(*) FROM forum_replies WHERE post_id = p.id) as reply_count,
                       (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count
                FROM forum_posts p
                LEFT JOIN users u ON p.user_id = u.id
                $where
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll();

        // 获取总数
        $countSql = "SELECT COUNT(*) FROM forum_posts p LEFT JOIN users u ON p.user_id = u.id $where";
        $countStmt = $this->db->prepare($countSql);

        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }

        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return $this->jsonResponse($response, [
            'success' => true,
            'data' => [
                'posts' => $posts,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]
        ]);
    }

    /**
     * 删除帖子
     */
    public function deletePost(Request $request, Response $response, array $args): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $postId = (int)$args['id'];

        // 开始事务
        $this->db->beginTransaction();

        try {
            // 删除帖子的回复点赞
            $this->db->prepare("DELETE FROM reply_likes WHERE reply_id IN (SELECT id FROM forum_replies WHERE post_id = ?)")->execute([$postId]);

            // 删除帖子的点赞
            $this->db->prepare("DELETE FROM post_likes WHERE post_id = ?")->execute([$postId]);

            // 删除帖子的回复
            $this->db->prepare("DELETE FROM forum_replies WHERE post_id = ?")->execute([$postId]);

            // 删除帖子
            $this->db->prepare("DELETE FROM forum_posts WHERE id = ?")->execute([$postId]);

            $this->db->commit();

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => '帖子删除成功'
            ]);
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '删除失败: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 获取统计信息
     */
    public function getStats(Request $request, Response $response): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        // 获取统计信息
        $stats = [
            'users' => (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'posts' => (int)$this->db->query("SELECT COUNT(*) FROM forum_posts")->fetchColumn(),
            'replies' => (int)$this->db->query("SELECT COUNT(*) FROM forum_replies")->fetchColumn(),
            'post_likes' => (int)$this->db->query("SELECT COUNT(*) FROM post_likes")->fetchColumn(),
            'reply_likes' => (int)$this->db->query("SELECT COUNT(*) FROM reply_likes")->fetchColumn(),
            'admin_users' => (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn(),
            'recent_posts' => $this->db->query("SELECT COUNT(*) FROM forum_posts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn(),
            'recent_replies' => $this->db->query("SELECT COUNT(*) FROM forum_replies WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn()
        ];

        return $this->jsonResponse($response, [
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * 获取所有回复（管理后台）
     */
    public function getReplies(Request $request, Response $response): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $page = (int)($request->getQueryParams()['page'] ?? 1);
        $limit = (int)($request->getQueryParams()['limit'] ?? 20);
        $search = $request->getQueryParams()['search'] ?? '';

        $offset = ($page - 1) * $limit;

        // 构建查询
        $where = 'WHERE 1=1';
        $params = [];

        if ($search) {
            $where .= ' AND (r.content LIKE :search OR u.username LIKE :search OR p.title LIKE :search)';
            $params[':search'] = "%$search%";
        }

        // 获取回复列表
        $sql = "SELECT r.*, u.username, u.avatar, p.title as post_title, p.id as post_id,
                       (SELECT COUNT(*) FROM reply_likes WHERE reply_id = r.id) as like_count
                FROM forum_replies r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN forum_posts p ON r.post_id = p.id
                $where
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $replies = $stmt->fetchAll();

        // 获取总数
        $countSql = "SELECT COUNT(*) FROM forum_replies r
                     LEFT JOIN users u ON r.user_id = u.id
                     LEFT JOIN forum_posts p ON r.post_id = p.id
                     $where";
        $countStmt = $this->db->prepare($countSql);

        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }

        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return $this->jsonResponse($response, [
            'success' => true,
            'data' => [
                'replies' => $replies,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => (int)$total,
                    'pages' => ceil($total / $limit)
                ]
            ]
        ]);
    }

    /**
     * 删除回复（管理后台）
     */
    public function deleteReply(Request $request, Response $response, array $args): Response
    {
        $admin = $this->checkAdmin();

        if (!$admin) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '需要管理员权限'
            ], 403);
        }

        $replyId = (int)$args['id'];

        // 获取回复信息以便更新帖子回复计数
        $reply = $this->db->prepare("SELECT post_id FROM forum_replies WHERE id = ?")->fetchColumn([$replyId]);
        if (!$reply) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '回复不存在'
            ], 404);
        }

        $postId = (int)$reply;

        // 开始事务
        $this->db->beginTransaction();

        try {
            // 删除回复的点赞
            $this->db->prepare("DELETE FROM reply_likes WHERE reply_id = ?")->execute([$replyId]);

            // 删除回复的子回复的点赞
            $this->db->prepare("DELETE FROM reply_likes WHERE reply_id IN (SELECT id FROM forum_replies WHERE parent_reply_id = ?)")->execute([$replyId]);

            // 删除子回复
            $this->db->prepare("DELETE FROM forum_replies WHERE parent_reply_id = ?")->execute([$replyId]);

            // 删除回复本身
            $this->db->prepare("DELETE FROM forum_replies WHERE id = ?")->execute([$replyId]);

            // 更新帖子的回复计数
            $this->db->prepare("UPDATE forum_posts SET reply_count = (SELECT COUNT(*) FROM forum_replies WHERE post_id = ?) WHERE id = ?")->execute([$postId, $postId]);

            $this->db->commit();

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => '回复删除成功'
            ]);
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '删除失败: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 返回 JSON 响应
     */
    private function jsonResponse(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}