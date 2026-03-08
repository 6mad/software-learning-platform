# PHP项目模板启动指南

## 环境要求

在启动项目之前，您需要确保系统中安装了以下组件：

1. PHP 8.0 或更高版本
2. Composer (PHP包管理器)

## 在Termux环境中安装依赖

如果您在Termux环境中运行此项目，请按以下步骤安装必要组件：

```bash
# 更新包列表
pkg update

# 安装PHP
pkg install php

# 安装Composer
pkg install php-composer
```

或者，如果php-composer包不可用，您可以手动安装Composer：

```bash
# 下载Composer安装脚本
curl -sS https://getcomposer.org/installer | php

# 移动到全局位置
mv composer.phar $PREFIX/bin/composer
```

## 项目启动步骤

1. **安装项目依赖**：
   ```bash
   cd php-project-template
   composer install
   ```

2. **配置环境变量**：
   ```bash
   cp .env.example .env
   # 编辑 .env 文件以配置您的环境设置
   ```

3. **创建日志目录**（如果不存在）：
   ```bash
   mkdir -p logs
   ```

4. **运行示例应用**：
   ```bash
   php public/index.php
   ```

5. **运行测试**（可选）：
   ```bash
   composer test
   ```

## 使用启动脚本

本项目包含一个启动脚本 `start.sh`，您可以直接运行：

```bash
./start.sh
```

## 手动启动项目

如果您想手动启动项目，请确保已完成上述环境配置，然后运行：

```bash
# 进入项目目录
cd php-project-template

# 安装依赖
composer install

# 运行示例应用
php public/index.php
```

## 本地开发服务器

为了在Web浏览器中测试项目，您可以使用PHP内置的开发服务器：

```bash
cd php-project-template
php -S localhost:8000 -t public/
```

然后在浏览器中访问 `http://localhost:8000`

## 故障排除

如果遇到问题，请检查：

1. PHP版本是否符合要求 (8.0+)
2. Composer是否正确安装
3. 目录权限是否正确
4. 所有依赖是否已正确安装

## 项目结构说明

- `src/` - PHP源代码
- `public/` - 公共入口点
- `config/` - 配置文件
- `tests/` - 测试文件
- `logs/` - 日志文件
- `composer.json` - 项目依赖配置
- `phpunit.xml` - 测试配置

现在您已经准备好启动并运行这个PHP项目了！