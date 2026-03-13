<?php

namespace App\Controllers;

use App\Database;
use App\ForumModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * 论坛控制器
 * 处理论坛相关的 API 请求
 */
class ForumController
{
    private ?ForumModel $forumModel = null;
    private array $config;
    private string $dbError = '';

    public function __construct()
    {
        // 加载数据库配置
        $configFile = __DIR__ . '/../../config/app.php';
        $appConfig = require $configFile;

        $this->config = $appConfig['database'];

        // 尝试获取数据库连接
        try {
            $db = Database::getInstance($this->config);
            $this->forumModel = new ForumModel($db);
        } catch (\Exception $e) {
            $this->dbError = '数据库连接失败，请检查 MySQL 服务是否已启动并配置正确的连接信息。';
            error_log('数据库连接失败: ' . $e->getMessage());
        }
    }

    /**
     * 检查数据库是否可用
     */
    private function checkDatabase(): bool
    {
        return $this->forumModel !== null;
    }

    /**
     * 返回数据库错误响应
     */
    private function databaseErrorResponse(Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'success' => false,
            'error' => $this->dbError,
            'message' => '数据库未配置或连接失败',
            'hint' => '请运行 php init-db.php 初始化数据库，或检查 .env 文件中的数据库配置'
        ]));
        return $response->withStatus(503)->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取帖子列表
     */
    public function getPosts(Request $request, Response $response): Response
    {
        if (!$this->checkDatabase()) {
            return $this->databaseErrorResponse($response);
        }

        $params = $request->getQueryParams();
        $page = (int) ($params['page'] ?? 1);
        $limit = (int) ($params['limit'] ?? 20);
        $category = $params['category'] ?? 'all';

        $posts = $this->forumModel->getPosts($page, $limit, $category);
        $total = $this->forumModel->getPostsCount($category);
        $totalPages = (int) ceil($total / $limit);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'posts' => $posts,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取帖子详情
     */
    public function getPostById(Request $request, Response $response, array $args): Response
    {
        $postId = (int) $args['id'];
        $post = $this->forumModel->getPostById($postId);

        if (!$post) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '帖子不存在'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $post
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 创建帖子
     */
    public function createPost(Request $request, Response $response): Response
    {
        if (!$this->checkDatabase()) {
            return $this->databaseErrorResponse($response);
        }

        // 获取用户 ID
        $userId = $request->getAttribute('user_id');
        if (!$userId) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '请先登录'
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $data = $request->getParsedBody();

        // 验证输入
        if (empty($data['title']) || empty($data['content'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '标题和内容不能为空'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $postId = $this->forumModel->createPost(
            $userId,
            $data['title'],
            $data['content'],
            $data['category'] ?? 'general'
        );

        $post = $this->forumModel->getPostById($postId);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $post,
            'message' => '帖子创建成功'
        ]));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    /**
     * 更新帖子
     */
    public function updatePost(Request $request, Response $response, array $args): Response
    {
        $postId = (int) $args['id'];
        $data = $request->getParsedBody();

        // 验证输入
        if (empty($data['title']) || empty($data['content'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '标题和内容不能为空'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $success = $this->forumModel->updatePost(
            $postId,
            $data['title'],
            $data['content'],
            $data['category'] ?? 'general'
        );

        if (!$success) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '更新失败'
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }

        $post = $this->forumModel->getPostById($postId);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $post,
            'message' => '帖子更新成功'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 删除帖子
     */
    public function deletePost(Request $request, Response $response, array $args): Response
    {
        $postId = (int) $args['id'];

        $success = $this->forumModel->deletePost($postId);

        if (!$success) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '删除失败'
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => '帖子删除成功'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取帖子的回复列表
     */
    public function getReplies(Request $request, Response $response, array $args): Response
    {
        if (!$this->checkDatabase()) {
            return $this->databaseErrorResponse($response);
        }

        $postId = (int) $args['id'];
        $params = $request->getQueryParams();
        $page = (int) ($params['page'] ?? 1);
        $limit = (int) ($params['limit'] ?? 20);

        $replies = $this->forumModel->getRepliesByPostId($postId, $page, $limit);
        $total = $this->forumModel->getRepliesCount($postId);
        $totalPages = (int) ceil($total / $limit);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'replies' => $replies,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 创建回复
     */
    public function createReply(Request $request, Response $response, array $args): Response
    {
        // 获取用户 ID
        $userId = $request->getAttribute('user_id');
        if (!$userId) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '请先登录'
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $postId = (int) $args['id'];
        $data = $request->getParsedBody();

        // 验证输入
        if (empty($data['content'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '回复内容不能为空'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $replyId = $this->forumModel->createReply(
            $postId,
            $userId,
            $data['content'],
            $data['parent_reply_id'] ?? null
        );

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['id' => $replyId],
            'message' => '回复成功'
        ]));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    /**
     * 删除回复
     */
    public function deleteReply(Request $request, Response $response, array $args): Response
    {
        $replyId = (int) $args['reply_id'];

        $success = $this->forumModel->deleteReply($replyId);

        if (!$success) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => '删除失败'
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => '回复删除成功'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 点赞/取消点赞帖子
     */
    public function togglePostLike(Request $request, Response $response, array $args): Response
    {
        $postId = (int) $args['id'];
        $data = $request->getParsedBody();
        $action = $data['action'] ?? 'toggle'; // toggle, like, unlike

        // TODO: 从会话中获取用户 ID，暂时使用固定用户
        $userId = 1; // 管理员用户 ID

        if ($action === 'unlike') {
            $this->forumModel->unlikePost($postId, $userId);
            $message = '取消点赞成功';
        } else {
            $this->forumModel->togglePostLike($postId, $userId);
            $message = '点赞成功';
        }

        $post = $this->forumModel->getPostById($postId);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'like_count' => $post['like_count'],
                'has_liked' => $this->forumModel->hasUserLikedPost($postId, $userId)
            ],
            'message' => $message
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取论坛统计信息
     */
    public function getStats(Request $request, Response $response): Response
    {
        if (!$this->checkDatabase()) {
            return $this->databaseErrorResponse($response);
        }

        $stats = $this->forumModel->getForumStats();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $stats
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取分类列表
     */
    public function getCategories(Request $request, Response $response): Response
    {
        // 分类列表不需要数据库，可以正常返回
        $categories = [
            ['id' => 'all', 'name' => '全部'],
            ['id' => 'announcements', 'name' => '公告'],
            ['id' => 'general', 'name' => '综合讨论'],
            ['id' => 'questions', 'name' => '问题求助'],
            ['id' => 'tutorials', 'name' => '教程分享'],
            ['id' => 'hardware', 'name' => '硬件相关'],
        ];

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $categories
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}