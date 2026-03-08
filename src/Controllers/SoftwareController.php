<?php

namespace App\Controllers;

use App\SoftwareInfo;
use App\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * 软件学习平台 API 控制器
 */
class SoftwareController
{
    private string $configDir;

    public function __construct()
    {
        $this->configDir = __DIR__ . '/../../config/';
    }

    /**
     * 获取所有可用的软件配置列表
     */
    public function getSoftwareList(Request $request, Response $response): Response
    {
        $softwareList = [];
        $files = glob($this->configDir . '*.php');

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            // 跳过 app 和 template 文件
            if ($filename !== 'app' && $filename !== 'software_template') {
                $config = require $file;
                $softwareList[] = [
                    'id' => $filename,
                    'name' => $config['name'] ?? $filename,
                    'version' => $config['version'] ?? '1.0.0',
                    'description' => $config['description'] ?? '',
                    'type' => $config['type'] ?? 'general'
                ];
            }
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $softwareList
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取指定软件的详细信息
     */
    public function getSoftwareInfo(Request $request, Response $response, array $args): Response
    {
        $softwareId = $args['id'];
        $configFile = $this->configDir . $softwareId . '.php';

        if (!file_exists($configFile)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Software configuration not found'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $config = require $configFile;
        $software = new SoftwareInfo($config);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $software->toArray()
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取软件的界面元素
     */
    public function getInterfaceElements(Request $request, Response $response, array $args): Response
    {
        $softwareId = $args['id'];
        $configFile = $this->configDir . $softwareId . '.php';

        if (!file_exists($configFile)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Software configuration not found'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $config = require $configFile;
        $software = new SoftwareInfo($config);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $software->getInterfaceElements()
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取软件的操作列表
     */
    public function getOperations(Request $request, Response $response, array $args): Response
    {
        $softwareId = $args['id'];
        $configFile = $this->configDir . $softwareId . '.php';

        if (!file_exists($configFile)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Software configuration not found'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $config = require $configFile;
        $software = new SoftwareInfo($config);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $software->getOperations()
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取软件的工作流程
     */
    public function getWorkflows(Request $request, Response $response, array $args): Response
    {
        $softwareId = $args['id'];
        $configFile = $this->configDir . $softwareId . '.php';

        if (!file_exists($configFile)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Software configuration not found'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $config = require $configFile;
        $software = new SoftwareInfo($config);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $software->getWorkflows()
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * 获取指定工作流程的详细信息
     */
    public function getWorkflowDetail(Request $request, Response $response, array $args): Response
    {
        $softwareId = $args['id'];
        $workflowName = $args['workflow'];
        $configFile = $this->configDir . $softwareId . '.php';

        if (!file_exists($configFile)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Software configuration not found'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $config = require $configFile;
        $software = new SoftwareInfo($config);
        $workflows = $software->getWorkflows();

        foreach ($workflows as $workflow) {
            if (isset($workflow['name']) && $workflow['name'] === $workflowName) {
                $response->getBody()->write(json_encode([
                    'success' => true,
                    'data' => $workflow
                ]));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode([
            'success' => false,
            'error' => 'Workflow not found'
        ]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }

    /**
     * 模拟执行操作（用于前端按钮点击）
     */
    public function simulateOperation(Request $request, Response $response, array $args): Response
    {
        $softwareId = $args['id'];
        $operationName = $args['operation'];
        $configFile = $this->configDir . $softwareId . '.php';

        if (!file_exists($configFile)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Software configuration not found'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $config = require $configFile;
        $software = new SoftwareInfo($config);
        $operations = $software->getOperations();

        foreach ($operations as $operation) {
            if (isset($operation['name']) && $operation['name'] === $operationName) {
                $response->getBody()->write(json_encode([
                    'success' => true,
                    'data' => $operation,
                    'message' => "操作 '{$operationName}' 执行成功"
                ]));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode([
            'success' => false,
            'error' => 'Operation not found'
        ]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
}