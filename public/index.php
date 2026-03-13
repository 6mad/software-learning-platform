<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\SoftwareController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

// 创建 Slim 应用
$app = AppFactory::create();

// 添加中间件
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// 错误处理中间件
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// CORS 中间件
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// API 路由
$app->group('/api', function (RouteCollectorProxy $group) {
    $controller = new SoftwareController();

    // 软件列表
    $group->get('/software', [$controller, 'getSoftwareList']);

    // 软件详情
    $group->get('/software/{id}', [$controller, 'getSoftwareInfo']);

    // 界面元素
    $group->get('/software/{id}/interface', [$controller, 'getInterfaceElements']);

    // 操作列表
    $group->get('/software/{id}/operations', [$controller, 'getOperations']);

    // 工作流程列表
    $group->get('/software/{id}/workflows', [$controller, 'getWorkflows']);

    // 工作流程详情
    $group->get('/software/{id}/workflows/{workflow}', [$controller, 'getWorkflowDetail']);

    // 模拟执行操作
    $group->post('/software/{id}/simulate/{operation}', [$controller, 'simulateOperation']);

    // 用户认证路由
    $userController = new \App\Controllers\UserController();

    // 注册
    $group->post('/auth/register', [$userController, 'register']);

    // 登录
    $group->post('/auth/login', [$userController, 'login']);

    // 登出
    $group->post('/auth/logout', [$userController, 'logout']);

    // 检查登录状态
    $group->get('/auth/check', [$userController, 'checkLogin']);

    // 获取当前用户信息（需要登录）
    $group->get('/auth/me', [$userController, 'getCurrentUser']);

    // 更新用户信息（需要登录）
    $group->put('/auth/user', [$userController, 'updateUser']);

    // 修改密码（需要登录）
    $group->post('/auth/change-password', [$userController, 'changePassword']);

    // 论坛路由
    $forumController = new \App\Controllers\ForumController();

    // 论坛帖子列表
    $group->get('/forum/posts', [$forumController, 'getPosts']);

    // 论坛帖子详情
    $group->get('/forum/posts/{id}', [$forumController, 'getPostById']);

    // 创建帖子（需要登录）
    $group->post('/forum/posts', [$forumController, 'createPost'])
          ->add(new AuthMiddleware());

    // 更新帖子（需要登录）
    $group->put('/forum/posts/{id}', [$forumController, 'updatePost'])
          ->add(new AuthMiddleware());

    // 删除帖子（需要登录）
    $group->delete('/forum/posts/{id}', [$forumController, 'deletePost'])
          ->add(new AuthMiddleware());

    // 获取帖子回复
    $group->get('/forum/posts/{id}/replies', [$forumController, 'getReplies']);

    // 创建回复（需要登录）
    $group->post('/forum/posts/{id}/replies', [$forumController, 'createReply'])
          ->add(new AuthMiddleware());

    // 删除回复（需要登录）
    $group->delete('/forum/replies/{reply_id}', [$forumController, 'deleteReply'])
          ->add(new AuthMiddleware());

    // 点赞/取消点赞帖子（需要登录）
    $group->post('/forum/posts/{id}/like', [$forumController, 'togglePostLike'])
          ->add(new AuthMiddleware());

    // 获取论坛统计
    $group->get('/forum/stats', [$forumController, 'getStats']);

    // 获取分类列表
    $group->get('/forum/categories', [$forumController, 'getCategories']);

    // 管理员路由（需要管理员权限）
    $adminController = new AdminController();

    // 获取统计信息
    $group->get('/admin/stats', [$adminController, 'getStats'])
          ->add(new AdminMiddleware());

    // 获取所有用户
    $group->get('/admin/users', [$adminController, 'getUsers'])
          ->add(new AdminMiddleware());

    // 删除用户
    $group->delete('/admin/users/{id}', [$adminController, 'deleteUser'])
          ->add(new AdminMiddleware());

    // 修改用户角色
    $group->put('/admin/users/{id}/role', [$adminController, 'updateUserRole'])
          ->add(new AdminMiddleware());

    // 获取所有帖子
    $group->get('/admin/posts', [$adminController, 'getAllPosts'])
          ->add(new AdminMiddleware());

    // 删除帖子
    $group->delete('/admin/posts/{id}', [$adminController, 'deletePost'])
          ->add(new AdminMiddleware());
});

// 前端页面路由（服务静态文件）
$app->get('/', function (Request $request, Response $response) {
    $indexPath = __DIR__ . '/index.html';
    if (file_exists($indexPath)) {
        $html = file_get_contents($indexPath);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
    $response->getBody()->write('Software Learning Platform Web Interface');
    return $response->withHeader('Content-Type', 'text/plain');
});

$app->get('/software/{id}', function (Request $request, Response $response, array $args) {
    $htmlPath = __DIR__ . '/software.html';
    if (file_exists($htmlPath)) {
        $html = file_get_contents($htmlPath);
        // 替换软件 ID
        $html = str_replace('{{SOFTWARE_ID}}', $args['id'], $html);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
    $response->getBody()->write('Software Learning Interface');
    return $response->withHeader('Content-Type', 'text/plain');
});

// 论坛页面路由
$app->get('/forum', function (Request $request, Response $response) {
    $htmlPath = __DIR__ . '/forum.html';
    if (file_exists($htmlPath)) {
        $html = file_get_contents($htmlPath);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
    $response->getBody()->write('Forum');
    return $response->withHeader('Content-Type', 'text/plain');
});

// 论坛帖子详情
$app->get('/forum/post/{id}', function (Request $request, Response $response, array $args) {
    $htmlPath = __DIR__ . '/forum-post.html';
    if (file_exists($htmlPath)) {
        $html = file_get_contents($htmlPath);
        // 替换帖子 ID
        $html = str_replace('{{POST_ID}}', $args['id'], $html);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
    $response->getBody()->write('Forum Post');
    return $response->withHeader('Content-Type', 'text/plain');
});

// 登录注册页面
$app->get('/login', function (Request $request, Response $response) {
    $htmlPath = __DIR__ . '/login.html';
    if (file_exists($htmlPath)) {
        $html = file_get_contents($htmlPath);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
    $response->getBody()->write('Login');
    return $response->withHeader('Content-Type', 'text/plain');
});

// 管理后台页面
$app->get('/admin', function (Request $request, Response $response) {
    $htmlPath = __DIR__ . '/admin.html';
    if (file_exists($htmlPath)) {
        $html = file_get_contents($htmlPath);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
    $response->getBody()->write('Admin Panel');
    return $response->withHeader('Content-Type', 'text/plain');
});

$app->run();