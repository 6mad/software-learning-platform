<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

/**
 * 认证中间件
 * 检查用户是否已登录
 */
class AuthMiddleware
{
    /**
     * 处理请求
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // 检查 session 中是否有用户信息
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // 未登录，返回 401 错误
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => '未登录，请先登录'
            ], JSON_UNESCAPED_UNICODE));
            
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
        }

        // 将用户信息添加到请求属性中
        $request = $request->withAttribute('user_id', $_SESSION['user_id']);
        $request = $request->withAttribute('username', $_SESSION['username'] ?? '');
        $request = $request->withAttribute('is_admin', $_SESSION['is_admin'] ?? false);

        // 继续处理请求
        return $handler->handle($request);
    }
}