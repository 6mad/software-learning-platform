<?php

namespace App\Middleware;

use App\AuthService;
use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * 管理员权限中间件
 * 检查用户是否已登录且具有管理员权限
 */
class AdminMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $configFile = __DIR__ . '/../../config/app.php';
        $appConfig = require $configFile;

        $db = Database::getInstance($appConfig['database']);
        $this->authService = new AuthService($db);
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // 检查是否登录
        if (!$this->authService->isLoggedIn()) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => '未登录'
            ], JSON_UNESCAPED_UNICODE));
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
        }

        // 检查是否为管理员
        $user = $this->authService->getCurrentUser();

        if (!$user || $user['role'] !== 'admin') {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => '需要管理员权限'
            ], JSON_UNESCAPED_UNICODE));
            return $response
                ->withStatus(403)
                ->withHeader('Content-Type', 'application/json');
        }

        // 将用户信息添加到请求属性中
        $request = $request->withAttribute('user', $user);

        // 继续处理请求
        return $handler->handle($request);
    }
}