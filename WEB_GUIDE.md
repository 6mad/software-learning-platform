# 软件学习平台 - Web 版本使用指南

## 概述

软件学习平台已成功改造为 Web 应用，支持通过浏览器进行软件操作流程的模拟学习。特别适用于不方便实机学习的特定硬件软件。

## 快速开始

### 1. 安装依赖

```bash
composer install
```

### 2. 启动 Web 服务器

```bash
# 方式一：使用 composer 脚本
composer start

# 方式二：直接使用 PHP
php -S localhost:8000 -t public/
```

### 3. 访问应用

在浏览器中打开：`http://localhost:8000`

## 功能特性

### 1. 软件列表浏览
- 查看所有可用的软件配置
- 每个软件显示名称、版本、描述和类型
- 点击卡片开始学习

### 2. 交互式学习界面
- **左侧边栏**：显示学习内容目录
  - 工作流程列表
  - 界面元素列表
  - 操作列表

- **中间模拟区**：显示软件界面模拟
  - 顶部工具栏
  - 主工作区
  - 底部状态栏

- **底部指令面板**：显示当前操作步骤
  - 步骤说明
  - 操作提示
  - 进度条
  - 上一步/下一步按钮

### 3. 工作流程学习
- 选择工作流程开始学习
- 分步骤引导完成操作
- 实时显示学习进度
- 提供操作提示和技巧
- 完成后显示预期结果

## API 接口

### 获取软件列表
```
GET /api/software
```

### 获取软件详情
```
GET /api/software/{id}
```

### 获取界面元素
```
GET /api/software/{id}/interface
```

### 获取操作列表
```
GET /api/software/{id}/operations
```

### 获取工作流程列表
```
GET /api/software/{id}/workflows
```

### 获取工作流程详情
```
GET /api/software/{id}/workflows/{workflow}
```

### 模拟执行操作
```
POST /api/software/{id}/simulate/{operation}
```

## 添加新软件配置

### 1. 创建配置文件

在 `config/` 目录下创建新的配置文件，例如 `my_software.php`：

```php
<?php

return [
    // 基本信息
    'name' => '您的软件名称',
    'version' => '1.0.0',
    'description' => '软件描述',
    'type' => 'hardware_control', // 或其他类型

    // 界面元素（包含模拟数据）
    'interface_elements' => [
        [
            'name' => '主界面',
            'type' => 'main_interface',
            'description' => '主要操作界面',
            'simulated_elements' => [
                'button_1' => [
                    'label' => '按钮1',
                    'action' => 'action_name',
                    'position' => ['x' => 10, 'y' => 10]
                ],
            ]
        ],
    ],

    // 操作
    'operations' => [
        [
            'name' => '操作名称',
            'category' => 'general',
            'description' => '操作描述',
            'shortcut' => 'Ctrl+N',
            'steps' => ['步骤1', '步骤2'],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'button_1',
                'feedback' => '操作成功'
            ]
        ],
    ],

    // 工作流程
    'workflows' => [
        [
            'name' => '工作流程名称',
            'description' => '流程描述',
            'difficulty' => 'beginner',
            'steps' => [
                [
                    'description' => '步骤描述',
                    'actions' => ['操作1', '操作2'],
                    'tips' => ['提示'],
                    'simulated_element' => 'button_1'
                ],
            ],
            'expected_result' => '预期结果',
        ],
    ],
];
```

### 2. 配置文件说明

#### 基本信息
- `name`: 软件名称
- `version`: 版本号
- `description`: 描述
- `type`: 软件类型（hardware_control, image_processing 等）

#### 界面元素
- `name`: 元素名称
- `type`: 元素类型
- `simulated_elements`: 模拟的界面元素
  - `label`: 显示标签
  - `action`: 对应的操作
  - `position`: 位置坐标

#### 操作
- `name`: 操作名称
- `category`: 操作分类
- `simulated_action`: 模拟操作配置
  - `type`: 操作类型（click_button, open_dialog 等）
  - `target`: 目标元素
  - `feedback`: 操作反馈

#### 工作流程
- `name`: 流程名称
- `difficulty`: 难度（beginner, intermediate, advanced）
- `steps`: 步骤列表
  - `simulated_element`: 关联的模拟元素
- `expected_result`: 预期结果

## 模拟界面替换

当前使用的是占位符界面，您可以：

1. **添加界面截图**：在 `public/images/` 目录下放置真实软件界面截图
2. **更新 CSS**：修改 `public/software.html` 中的样式以匹配真实界面
3. **配置界面元素**：在配置文件中指定每个按钮的位置和样式

### 示例：添加真实界面

```php
'interface_simulation' => [
    'layout' => [
        'header' => [
            'height' => 60,
            'background' => '#1a1a2e',
            'image' => '/images/header.png' // 添加图片
        ],
        // ...
    ]
]
```

## 示例配置

项目包含一个完整的示例配置 `config/hardware_software.php`，演示了：

- 硬件控制软件的界面元素
- 设备启动/停止流程
- 参数调整流程
- 模拟按钮和显示区域

## 开发说明

### 后端架构
- **框架**：Slim Framework 4.x
- **PSR-7**：使用 nyholm/psr7
- **控制器**：`src/Controllers/SoftwareController.php`
- **路由**：定义在 `public/index.php`

### 前端架构
- **技术**：纯 HTML + CSS + JavaScript
- **无依赖**：无需额外前端框架
- **响应式**：支持不同屏幕尺寸

### 扩展功能

#### 1. 添加用户认证
```php
// 在 public/index.php 中添加中间件
$app->add(new AuthenticationMiddleware());
```

#### 2. 添加学习进度持久化
```php
// 创建数据库表存储用户进度
// 在控制器中添加进度保存和加载方法
```

#### 3. 添加多语言支持
```php
// 创建语言文件
// 在前端添加语言切换功能
```

## 故障排除

### 依赖安装失败
```bash
# 清理缓存重新安装
rm -rf vendor composer.lock
composer install
```

### 服务器启动失败
```bash
# 检查端口是否被占用
lsof -i :8000

# 使用其他端口
php -S localhost:8080 -t public/
```

### API 返回错误
```bash
# 检查 PHP 错误日志
tail -f logs/app.log

# 检查配置文件语法
php -l config/hardware_software.php
```

## 测试

### 测试 API
```bash
# 测试软件列表
curl http://localhost:8000/api/software

# 测试工作流程
curl http://localhost:8000/api/software/hardware_software/workflows
```

### 测试前端
1. 启动服务器
2. 访问 `http://localhost:8000`
3. 点击软件卡片
4. 选择工作流程
5. 按步骤完成操作

## 贡献

欢迎提交 Issue 和 Pull Request！

## 许可证

MIT License