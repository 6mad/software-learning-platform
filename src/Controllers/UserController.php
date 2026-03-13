<?php

namespace App\Controllers;

use App\AuthService;
use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * 用户控制器
 * 处理用户注册、登录、登出等操作
 */
class UserController
{
    private AuthService $authService;

    public function __construct()
    {
        $configFile = __DIR__ . '/../../config/app.php';
        $appConfig = require $configFile;

        $db = Database::getInstance($appConfig['database']);
        $this->authService = new AuthService($db);
    }

    /**
     * 用户注册
     */
    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $email = trim($data['email'] ?? '');

        // 验证输入
        if (strlen($username) < 3) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '用户名至少需要3个字符'
            ], 400);
        }

        if (strlen($password) < 6) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '密码至少需要6个字符'
            ], 400);
        }

        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '邮箱格式不正确'
            ], 400);
        }

        // 注册用户
        $result = $this->authService->register($username, $password, $email);

        return $this->jsonResponse($response, $result, $result['success'] ? 201 : 400);
    }

    /**
     * 用户登录
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        // 验证输入
        if (!$username || !$password) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '用户名和密码不能为空'
            ], 400);
        }

        // 登录
        $result = $this->authService->login($username, $password);

        return $this->jsonResponse($response, $result, $result['success'] ? 200 : 401);
    }

    /**
     * 用户登出
     */
    public function logout(Request $request, Response $response): Response
    {
        $this->authService->logout();

        return $this->jsonResponse($response, [
            'success' => true,
            'message' => '登出成功'
        ]);
    }

    /**
     * 获取当前登录用户信息
     */
    public function getCurrentUser(Request $request, Response $response): Response
    {
        $user = $this->authService->getCurrentUser();

        if (!$user) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '未登录'
            ], 401);
        }

        return $this->jsonResponse($response, [
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * 检查登录状态
     */
    public function checkLogin(Request $request, Response $response): Response
    {
        $isLoggedIn = $this->authService->isLoggedIn();
        $user = $isLoggedIn ? $this->authService->getCurrentUser() : null;

        return $this->jsonResponse($response, [
            'success' => true,
            'data' => [
                'logged_in' => $isLoggedIn,
                'user' => $user
            ]
        ]);
    }

    /**
     * 更新用户信息
     */
    public function updateUser(Request $request, Response $response): Response
    {
        $user = $this->authService->getCurrentUser();

        if (!$user) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '未登录'
            ], 401);
        }

        $data = $request->getParsedBody();
        $result = $this->authService->updateUser($user['id'], $data);

        return $this->jsonResponse($response, $result, $result['success'] ? 200 : 400);
    }

    /**
     * 修改密码
     */
    public function changePassword(Request $request, Response $response): Response
    {
        $user = $this->authService->getCurrentUser();

        if (!$user) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '未登录'
            ], 401);
        }

        $data = $request->getParsedBody();
        $oldPassword = $data['old_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (strlen($newPassword) < 6) {
            return $this->jsonResponse($response, [
                'success' => false,
                'message' => '新密码至少需要6个字符'
            ], 400);
        }

        $result = $this->authService->changePassword($user['id'], $oldPassword, $newPassword);

        return $this->jsonResponse($response, $result, $result['success'] ? 200 : 400);
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