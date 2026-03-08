# 软件学习平台

一个通过模拟操作学习软件使用的交互式学习平台。

## 🎯 项目简介

软件学习平台旨在解决特定硬件软件学习困难的问题。许多专业硬件设备的配套软件不方便进行实机操作学习，本平台通过 Web 界面模拟真实的软件操作流程，让用户可以在浏览器中安全、高效地学习软件使用方法。

**适用场景：**
- 特定硬件软件的预培训
- 软件操作流程教学
- 新员工快速上手培训
- 软件操作规范学习

**两种使用方式：**
- 🌐 **Web 版本**（推荐）：通过浏览器访问，界面友好，支持可视化学习
- 💻 **CLI 版本**：命令行交互，适合终端用户

## ✨ 功能特性

### Web 版本（推荐）
- 🎨 **可视化界面学习** - 通过浏览器界面学习软件操作
- 📊 **分步引导** - 工作流程逐步引导完成
- 🔄 **实时进度追踪** - 实时显示学习进度和完成情况
- 💡 **操作提示** - 提供详细的操作说明和技巧
- 🖱️ **模拟按钮交互** - 可点击的模拟按钮触发操作
- ⚙️ **可配置扩展** - 通过配置文件轻松添加新的软件学习内容

### CLI 版本
- 💻 **命令行交互** - 适合终端用户学习
- 📋 **界面识别学习** - 学习软件界面的各个组成部分
- 🔧 **基础操作学习** - 学习软件的基本操作命令和快捷键
- 📝 **工作流程模拟** - 模拟完整的软件使用工作流程
- 🎯 **练习模式** - 通过练习模式巩固学习

## 系统要求

- PHP 8.0 或更高版本
- Composer

## 安装

1. 进入项目目录：
   ```bash
   cd php-project-template
   ```

2. 安装依赖：
   ```bash
   composer install
   ```

3. （可选）复制环境变量文件：
   ```bash
   cp .env.example .env
   ```

## 🚀 快速开始

### Web 版本（推荐）

#### 1. 安装依赖
```bash
composer install
```

#### 2. 启动 Web 服务器
```bash
# 方式一：使用 composer 脚本
composer start

# 方式二：直接使用 PHP
php -S localhost:8000 -t public/
```

#### 3. 访问应用
在浏览器中打开：`http://localhost:8000`

### CLI 版本

#### 使用启动脚本
```bash
bash start.sh
```

#### 直接运行
```bash
php public/index.php [软件配置名称]
```

示例：
```bash
php public/index.php gimp
php public/index.php photoshop
```

## 使用说明

### 创建软件配置

1. 复制模板配置文件：
   ```bash
   cp config/software_template.php config/your_software.php
   ```

2. 编辑配置文件，填写软件信息：
   ```php
   return [
       'name' => '您的软件名称',
       'version' => '1.0.0',
       'description' => '软件描述',
       'type' => 'image_processing', // 软件类型

       // 定义界面元素
       'interface_elements' => [
           // ... 界面元素配置
       ],

       // 定义操作
       'operations' => [
           // ... 操作配置
       ],

       // 定义工作流程
       'workflows' => [
           // ... 工作流程配置
       ],
   ];
   ```

3. 运行平台：
   ```bash
   php public/index.php your_software
   ```

### 主要学习模块

#### 1. 界面识别学习

- 了解软件界面的主要组成部分
- 学习每个界面元素的功能和位置
- 掌握界面元素的快捷键

#### 2. 基础操作学习

- 学习软件的基本操作命令
- 掌握常用的快捷键
- 通过练习模式巩固学习

#### 3. 工作流程模拟

- 学习完整的软件使用流程
- 按难度分级（初级/中级/高级）
- 逐步引导完成实际任务

## 📁 项目结构

```
php-project-template/
├── composer.json              # 项目依赖和自动加载
├── composer.lock              # 锁定的依赖版本
├── phpunit.xml                # 测试配置
├── .env.example               # 环境变量示例
├── start.sh                   # CLI 启动脚本
├── README.md                  # 项目说明文档
├── STARTUP_GUIDE.md           # 启动指南
├── WEB_GUIDE.md               # Web 使用指南
├── AGENTS.md                  # AI 上下文文档
│
├── src/                       # 源代码目录 (App 命名空间)
│   ├── Controllers/           # Web API 控制器
│   │   └── SoftwareController.php
│   ├── SoftwareInfo.php       # 软件信息模型
│   ├── Config.php             # 配置管理
│   ├── Logger.php             # 日志功能
│   ├── InterfaceRecognizer.php # 界面识别模块
│   ├── BasicOperations.php    # 基础操作模块
│   ├── WorkflowSimulator.php  # 工作流程模块
│   └── Calculator.php         # 计算器工具类
│
├── config/                    # 配置文件目录
│   ├── app.php                # 应用配置（数据库、日志等）
│   ├── gimp.php               # GIMP 软件配置示例
│   ├── hardware_software.php  # 硬件软件配置示例
│   └── software_template.php  # 软件配置模板
│
├── public/                    # Web 公共入口目录
│   ├── index.html             # 软件列表主页
│   ├── software.html          # 学习界面
│   └── index.php              # Web 入口文件（API 路由）
│
├── tests/                     # 测试目录
│   ├── Unit/                  # 单元测试
│   │   ├── CalculatorTest.php
│   │   └── LoggerTest.php
│   └── Integration/           # 集成测试
│
├── docs/                      # 文档目录
├── logs/                      # 日志文件目录（不在 Git 中）
└── vendor/                    # Composer 依赖目录
```

## 📝 配置文件说明

### config/software_template.php

配置模板文件，包含以下部分：

1. **基本信息**: 软件名称、版本、描述、类型
2. **界面元素**: 定义界面的各个组成部分
   - `simulated_elements`: Web 版本的模拟界面元素
3. **操作**: 定义软件的基本操作命令
   - `simulated_action`: 模拟操作配置
4. **工作流程**: 定义完整的使用流程
   - `simulated_element`: 关联的模拟元素

### Web 版本配置示例

```php
// 界面元素（Web 版本）
'interface_elements' => [
    [
        'name' => '工具栏',
        'simulated_elements' => [
            'button_1' => [
                'label' => '按钮1',
                'action' => 'action_name',
                'position' => ['x' => 10, 'y' => 10]
            ],
        ]
    ]
]

// 操作（Web 版本）
'operations' => [
    [
        'name' => '操作名称',
        'simulated_action' => [
            'type' => 'click_button',
            'target' => 'button_1',
            'feedback' => '操作成功'
        ]
    ]
]

// 工作流程（Web 版本）
'workflows' => [
    [
        'steps' => [
            [
                'description' => '步骤描述',
                'simulated_element' => 'button_1'
            ]
        ]
    ]
]
```

详细配置说明请参考：
- `config/software_template.php` - 配置模板
- `config/hardware_software.php` - 完整示例
- `WEB_GUIDE.md` - Web 版本详细文档

## 🔧 开发指南

### 启动开发服务器

#### Web 版本
```bash
# 启动 PHP 内置服务器
composer start

# 或直接使用
php -S localhost:8000 -t public/

# 使用其他端口
php -S localhost:8080 -t public/
```

#### CLI 版本
```bash
bash start.sh
```

### 运行测试

```bash
# 运行所有测试
composer test

# 运行特定测试
vendor/bin/phpunit tests/Unit/CalculatorTest.php

# 查看测试覆盖率
vendor/bin/phpunit --coverage-html coverage/
```

### 代码规范检查

```bash
# 检查代码规范
composer cs-check

# 自动修复代码规范
composer cs-fix
```

### 调试方法

#### 查看日志
```bash
# 查看应用日志
cat logs/app.log

# 实时监控日志
tail -f logs/app.log
```

#### 测试 API
```bash
# 测试软件列表 API
curl http://localhost:8000/api/software

# 测试工作流程 API
curl http://localhost:8000/api/software/hardware_software/workflows
```

#### 检查配置文件语法
```bash
php -l config/your_file.php
```

## ➕ 添加新的学习内容

### Web 版本：添加界面元素

```php
'interface_elements' => [
    [
        'name' => '元素名称',
        'type' => '元素类型',
        'description' => '元素描述',
        'simulated_elements' => [
            'element_id' => [
                'label' => '显示标签',
                'action' => '对应的操作',
                'position' => ['x' => 10, 'y' => 10]  // 可选：位置坐标
            ],
        ],
    ]
]
```

### CLI 版本：添加界面元素

```php
'interface_elements' => [
    [
        'name' => '元素名称',
        'type' => '元素类型',
        'description' => '元素描述',
        'components' => ['组件1', '组件2'],
        'usage' => '使用方法说明',
        'shortcuts' => [
            '快捷键' => '对应操作',
        ],
    ],
]
```

### 添加操作（Web 版本）

```php
'operations' => [
    [
        'name' => '操作名称',
        'category' => '操作分类',
        'description' => '操作描述',
        'shortcut' => '快捷键',
        'menu_path' => '菜单路径',
        'steps' => ['步骤1', '步骤2'],
        'tips' => ['提示1', '提示2'],
        'simulated_action' => [
            'type' => 'click_button',  // 或 'open_dialog', 'select_option'
            'target' => 'element_id',
            'feedback' => '操作成功',
            'state_change' => [        // 可选：状态变化
                'status' => 'active'
            ]
        ],
    ],
]
```

### 添加工作流程（Web 版本）

```php
'workflows' => [
    [
        'name' => '工作流程名称',
        'description' => '流程描述',
        'difficulty' => 'beginner', // beginner, intermediate, advanced
        'estimated_time' => '预计时间',
        'prerequisites' => ['前置条件'],
        'steps' => [
            [
                'description' => '步骤描述',
                'actions' => ['操作1', '操作2'],
                'tips' => ['提示'],
                'simulated_element' => 'element_id',  // Web 版本：关联的模拟元素
            ],
        ],
        'expected_result' => '预期结果',
        'next_workflows' => ['后续流程'],
    ],
]
```

## 许可证

本项目采用 MIT 许可证。

## 贡献

欢迎提交 Issue 和 Pull Request！

## 联系方式

如有问题或建议，请通过 Issue 联系。

## ❓ 常见问题 (FAQ)

### 安装和运行

#### Q: 依赖安装失败怎么办？
```bash
# 清理缓存重新安装
rm -rf vendor composer.lock
composer install
```

#### Q: 端口 8000 被占用怎么办？
```bash
# 使用其他端口
php -S localhost:8080 -t public/

# 或查看占用进程
lsof -i :8000  # Linux/Mac
netstat -ano | findstr :8000  # Windows
```

#### Q: 如何在 Windows 上运行？
```bash
# 1. 安装 PHP（从 php.net 下载）
# 2. 安装 Composer（从 getcomposer.org 下载）
# 3. 进入项目目录
cd php-project-template
# 4. 安装依赖
composer install
# 5. 启动服务器
php -S localhost:8000 -t public
```

### 配置问题

#### Q: 如何添加真实软件界面截图？
1. 在 `public/images/` 目录下创建图片
2. 在配置文件中引用：
```php
'interface_simulation' => [
    'layout' => [
        'main' => [
            'background_image' => '/images/my_software_interface.png'
        ]
    ]
]
```

#### Q: 配置文件语法错误怎么办？
```bash
# 检查 PHP 语法
php -l config/your_file.php

# 查看错误日志
cat logs/app.log
```

### Web 版本问题

#### Q: API 返回 404 错误？
- 确认服务器已启动
- 检查 URL 路径是否正确
- 查看浏览器控制台错误信息

#### Q: 前端无法连接后端？
- 确认 CORS 配置正确（已在 `public/index.php` 中配置）
- 检查服务器是否正常运行
- 查看浏览器网络请求

#### Q: 如何调试 Web 版本？
```bash
# 1. 查看服务器日志
tail -f logs/app.log

# 2. 测试 API
curl http://localhost:8000/api/software

# 3. 使用浏览器开发者工具
# - Network 标签查看 API 请求
# - Console 标签查看 JavaScript 错误
```

### CLI 版本问题

#### Q: CLI 版本无法运行？
```bash
# 检查 PHP 版本
php --version  # 需要 8.0+

# 检查文件权限
chmod +x start.sh

# 直接运行 PHP 文件
php public/index.php
```

#### Q: 如何查看学习进度？
学习进度实时显示在 CLI 界面中，选择"4. 查看学习进度"即可查看。

### 开发问题

#### Q: 如何添加新的 API 端点？
1. 在 `src/Controllers/SoftwareController.php` 中添加方法
2. 在 `public/index.php` 中注册路由：
```php
$app->get('/api/new-endpoint', [$controller, 'newMethod']);
```

#### Q: 如何修改前端样式？
编辑 `public/index.html` 和 `public/software.html` 中的 `<style>` 标签内容。

#### Q: 如何添加用户认证？
```php
// 在 public/index.php 中添加中间件
$app->add(new AuthenticationMiddleware());
```

### 其他问题

#### Q: 如何备份配置文件？
```bash
# 备份单个配置
cp config/my_software.php config/my_software.php.bak

# 备份所有配置
tar -czf config_backup.tar.gz config/
```

#### Q: 如何更新到最新版本？
```bash
# 如果使用 Git
git pull origin main
composer install

# 如果手动下载
# 1. 备份配置文件
cp -r config/ config_backup/
# 2. 下载新版本
# 3. 恢复配置文件
cp -r config_backup/* config/
# 4. 安装依赖
composer install
```

如果以上 FAQ 没有解决你的问题，请：
1. 查看日志文件：`logs/app.log`
2. 搜索已有 Issue
3. 创建新的 Issue 并提供详细信息