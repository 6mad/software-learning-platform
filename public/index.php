<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\SoftwareController;

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

$app->run();