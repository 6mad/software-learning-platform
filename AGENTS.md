# 软件学习平台 - 项目上下文文档

## 项目概述

这是一个通过模拟操作学习软件使用的交互式学习平台，使用 PHP 8.0+ 开发。该平台允许用户通过交互式方式学习各种软件的界面识别、基础操作和完整工作流程。

**主要特性：**
- 界面识别学习：学习软件界面的各个组成部分
- 基础操作学习：学习软件的基本操作命令和快捷键
- 工作流程模拟：模拟完整的软件使用工作流程
- 学习进度跟踪：实时查看学习进度和完成情况
- 可配置扩展：通过配置文件轻松添加新的软件学习内容

**技术栈：**
- PHP 8.0+
- Composer (依赖管理)
- PHPUnit 9.0 (单元测试)
- PHP_CodeSniffer (代码规范检查)

**项目类型：** 命令行交互式应用程序 (CLI)

**命名空间：** `App\`

## 项目结构

```
php-project-template/
├── src/                              # 源代码目录 (App 命名空间)
│   ├── SoftwareInfo.php              # 软件信息模型
│   ├── Config.php                    # 配置管理
│   ├── Logger.php                    # 日志功能
│   ├── InterfaceRecognizer.php       # 界面识别模块
│   ├── BasicOperations.php           # 基础操作模块
│   ├── WorkflowSimulator.php         # 工作流程模块
│   ├── BasicOperations.php           # 基础操作模块
│   └── Calculator.php                # 计算器工具类
├── config/                           # 配置文件目录
│   ├── app.php                       # 应用配置（数据库、日志等）
│   ├── gimp.php                      # GIMP 软件配置示例
│   └── software_template.php         # 软件配置模板
├── public/                           # 公共入口目录
│   └── index.php                     # 主入口文件
├── tests/                            # 测试目录
│   ├── Unit/                         # 单元测试
│   │   ├── CalculatorTest.php
│   │   └── LoggerTest.php
│   └── Integration/                  # 集成测试
├── logs/                             # 日志文件目录（不在 Git 中）
├── vendor/                           # Composer 依赖目录
├── composer.json                     # Composer 配置文件
├── composer.lock                     # Composer 锁文件
├── phpunit.xml                       # PHPUnit 配置文件
├── .env.example                      # 环境变量示例
├── .env                              # 环境变量文件（本地）
├── .gitignore                        # Git 忽略文件
├── start.sh                          # 启动脚本
├── README.md                         # 项目说明文档
├── STARTUP_GUIDE.md                  # 启动指南
└── AGENTS.md                         # 本文档（AI 上下文）
```

## 构建和运行

### 环境要求
- PHP 8.0 或更高版本
- Composer

### 安装依赖

```bash
composer install
```

### 运行应用

**使用启动脚本：**
```bash
bash start.sh
```

**直接运行：**
```bash
php public/index.php [软件配置名称]
```

示例：
```bash
php public/index.php gimp
php public/index.php photoshop
```

### 运行测试

```bash
composer test
```

### 代码规范检查

```bash
composer cs-check      # 检查代码规范
composer cs-fix        # 自动修复代码规范
```

### 本地开发服务器（可选）

```bash
php -S localhost:8000 -t public/
```

然后在浏览器中访问 `http://localhost:8000`

## 开发约定

### 编码规范

- **PSR-4 自动加载：** 所有类文件位于 `src/` 目录，使用 `App\` 命名空间
- **PSR-12 代码风格：** 使用 PHP_CodeSniffer 强制执行 PSR-12 标准
- **类型提示：** 所有方法参数和返回值使用 PHP 8.0 类型提示
- **文档注释：** 类和方法使用 PHPDoc 注释

### 测试约定

- **单元测试：** 位于 `tests/Unit/` 目录，文件名以 `Test.php` 结尾
- **集成测试：** 位于 `tests/Integration/` 目录
- **测试类命名：** 使用 `Tests\Unit\{ClassName}Test` 格式
- **测试方法命名：** 使用 `test{MethodName}` 格式

### 配置约定

- **应用配置：** `config/app.php` - 数据库、应用设置、日志配置
- **软件配置：** `config/{software_name}.php` - 软件特定的学习内容
- **配置模板：** `config/software_template.php` - 创建新软件配置的模板

### 日志约定

- **日志级别：** INFO, WARNING, ERROR
- **日志位置：** `logs/app.log`
- **日志格式：** `[YYYY-MM-DD HH:MM:SS] LEVEL: message`

## 核心模块说明

### 1. SoftwareInfo (src/SoftwareInfo.php)
软件信息模型，封装软件的基本数据。

### 2. Config (src/Config.php)
配置管理类，加载和访问应用配置。

### 3. Logger (src/Logger.php)
日志记录类，支持 INFO、WARNING、ERROR 级别。

### 4. InterfaceRecognizer (src/InterfaceRecognizer.php)
界面识别学习模块，帮助用户学习软件界面元素。

### 5. BasicOperations (src/BasicOperations.php)
基础操作学习模块，教授软件的基本操作和快捷键。

### 6. WorkflowSimulator (src/WorkflowSimulator.php)
工作流程模拟模块，引导用户完成完整的使用流程。

## 添加新软件配置

### 步骤

1. **复制模板文件：**
   ```bash
   cp config/software_template.php config/your_software.php
   ```

2. **编辑配置文件：**
   - 填写基本信息（名称、版本、描述、类型）
   - 定义界面元素
   - 定义操作
   - 定义工作流程

3. **运行应用：**
   ```bash
   php public/index.php your_software
   ```

### 配置文件结构

```php
return [
    // 基本信息
    'name' => '软件名称',
    'version' => '1.0.0',
    'description' => '软件描述',
    'type' => 'image_processing',

    // 界面元素
    'interface_elements' => [
        [
            'name' => '元素名称',
            'type' => '元素类型',
            'description' => '元素描述',
            'components' => ['组件1', '组件2'],
            'usage' => '使用方法',
            'shortcuts' => [
                '快捷键' => '对应操作',
            ],
        ],
    ],

    // 基础操作
    'operations' => [
        [
            'name' => '操作名称',
            'category' => '操作分类',
            'description' => '操作描述',
            'shortcut' => '快捷键',
            'menu_path' => '菜单路径',
            'steps' => ['步骤1', '步骤2'],
            'tips' => ['提示1', '提示2'],
        ],
    ],

    // 工作流程
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
                ],
            ],
            'expected_result' => '预期结果',
            'next_workflows' => ['后续流程'],
        ],
    ],
];
```

## 关键文件说明

### composer.json
定义项目依赖、自动加载和脚本命令：
- `composer test` - 运行测试
- `composer cs-check` - 检查代码规范
- `composer cs-fix` - 修复代码规范

### phpunit.xml
PHPUnit 配置文件，定义测试套件和代码覆盖率设置。

### public/index.php
主入口文件，处理命令行参数并启动交互式学习界面。

### config/app.php
应用配置文件，包含数据库、应用设置和日志配置。

### .env.example
环境变量示例文件，用于创建本地的 `.env` 文件。

## 开发注意事项

1. **命名空间：** 所有 `src/` 目录下的类必须使用 `App\` 命名空间
2. **类型提示：** 优先使用 PHP 8.0 的类型提示特性
3. **错误处理：** 使用适当的异常处理，避免静默失败
4. **日志记录：** 重要的操作和错误应记录到日志
5. **测试覆盖：** 新功能应包含相应的单元测试
6. **代码规范：** 提交前运行 `composer cs-check` 确保代码符合规范

## 常见命令

```bash
# 安装依赖
composer install

# 运行应用
php public/index.php gimp

# 运行测试
composer test

# 检查代码规范
composer cs-check

# 修复代码规范
composer cs-fix

# 使用启动脚本
bash start.sh

# 启动开发服务器
php -S localhost:8000 -t public/
```

## 项目特定约定

1. **软件类型分类：**
   - `image_processing` - 图像处理软件
   - `video_editor` - 视频编辑软件
   - `audio_editor` - 音频编辑软件
   - `text_editor` - 文本编辑软件

2. **工作流程难度分级：**
   - `beginner` - 初级
   - `intermediate` - 中级
   - `advanced` - 高级

3. **日志级别：**
   - `info` - 一般信息
   - `warning` - 警告信息
   - `error` - 错误信息

4. **配置文件命名：**
   - 使用小写字母和下划线
   - 使用软件的英文名称
   - 例如：`gimp.php`, `photoshop.php`, `vscode.php`

## 扩展建议

- 添加更多软件配置（如 Photoshop、VS Code、Figma 等）
- 实现学习进度的持久化存储
- 添加多语言支持
- 实现用户认证和个性化学习路径
- 添加可视化学习进度图表
- 支持导出学习报告