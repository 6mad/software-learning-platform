# 软件学习平台

一个通过模拟操作学习软件使用的交互式学习平台，内置论坛交流和用户系统。

## 🎯 项目简介

软件学习平台旨在解决特定硬件软件学习困难的问题。许多专业硬件设备的配套软件不方便进行实机操作学习，本平台通过 Web 界面模拟真实的软件操作流程，让用户可以在浏览器中安全、高效地学习软件使用方法。

**适用场景：**
- 特定硬件软件的预培训
- 软件操作流程教学
- 新员工快速上手培训
- 软件操作规范学习
- 用户交流和经验分享

**三种使用方式：**
- 🌐 **Web 版本**（推荐）：通过浏览器访问，界面友好，支持可视化学习
- 💬 **论坛交流**：内置论坛系统，用户可以发帖、回复、交流经验
- 👤 **用户系统**：完整的用户认证，支持注册、登录、权限管理
- 💻 **CLI 版本**：命令行交互，适合终端用户学习

## ✨ 功能特性

### Web 版本（推荐）
- 🎨 **可视化界面学习** - 通过浏览器界面学习软件操作
- 📊 **分步引导** - 工作流程逐步引导完成
- 🔄 **实时进度追踪** - 实时显示学习进度和完成情况
- 💡 **操作提示** - 提供详细的操作说明和技巧
- 🖱️ **模拟按钮交互** - 可点击的模拟按钮触发操作
- ⚙️ **可配置扩展** - 通过配置文件轻松添加新的软件学习内容

### 论坛系统
- 📝 **发帖功能** - 发布学习心得、提问、分享经验
- 💬 **回复功能** - 对帖子进行回复和讨论
- 👍 **点赞功能** - 对帖子和回复进行点赞
- 📂 **分类管理** - 支持帖子分类（公告、综合讨论、问题求助、教程分享、硬件相关）
- 🔍 **搜索功能** - 快速查找相关帖子
- 📊 **统计信息** - 显示用户数、帖子数、回复数

### 用户认证系统
- 🔐 **用户注册** - 支持用户名、邮箱注册
- 🔑 **用户登录** - 安全的密码加密和验证
- 🚪 **用户登出** - 安全的会话管理
- 👨‍💼 **权限管理** - 支持普通用户和管理员权限
- 📋 **用户信息管理** - 更新个人资料和密码
- 🛡️ **操作保护** - 敏感操作需要登录验证

### CLI 版本
- 💻 **命令行交互** - 适合终端用户学习
- 📋 **界面识别学习** - 学习软件界面的各个组成部分
- 🔧 **基础操作学习** - 学习软件的基本操作命令和快捷键
- 📝 **工作流程模拟** - 模拟完整的软件使用工作流程
- 🎯 **练习模式** - 通过练习模式巩固学习

## 📋 系统要求

### 基础要求
- PHP 8.0 或更高版本
- Composer

### 数据库要求（可选，用于论坛功能）
- MySQL 5.7+ 或 MariaDB 10.2+

### 推荐环境
- Termux（Android）
- Linux / macOS / Windows

## 🚀 安装和配置

### 1. 克隆或下载项目

```bash
# 如果使用 Git
git clone git@github.com:6mad/software-learning-platform.git
cd php-project-template

# 或直接下载并解压
```

### 2. 安装 PHP 依赖

```bash
composer install
```

### 3. 配置数据库（可选，用于论坛功能）

#### 3.1 安装数据库

**Termux（Android）：**
```bash
pkg install mariadb
```

**Ubuntu/Debian：**
```bash
sudo apt-get install mysql-server
```

**macOS：**
```bash
brew install mysql
```

**Windows：**
从 [MySQL官网](https://dev.mysql.com/downloads/mysql/) 下载安装

#### 3.2 启动数据库服务

**Termux：**
```bash
mariadbd --user=root --datadir=$PREFIX/var/lib/mysql &
```

**Linux/macOS：**
```bash
sudo systemctl start mysql
# 或
sudo service mysql start
```

**Windows：**
通过服务管理器启动 MySQL 服务

#### 3.3 创建数据库配置文件

```bash
cp .env.database.example .env
```

编辑 `.env` 文件，配置数据库连接信息：

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=software_learning_platform
DB_USER=root
DB_PASSWORD=
```

#### 3.4 初始化数据库

```bash
php init-db.php
```

这将创建：
- 数据库 `software_learning_platform`
- 所有必要的数据表（用户、帖子、回复、点赞等）
- 默认管理员账户：`admin` / `admin123`

### 4. 启动应用

```bash
composer start
```

或直接使用 PHP：

```bash
php -S localhost:8000 -t public/
```

### 5. 访问应用

在浏览器中打开：`http://localhost:8000`

**可用页面：**
- 主页：http://localhost:8000
- 软件学习：http://localhost:8000/software/{软件ID}
- 论坛列表：http://localhost:8000/forum
- 帖子详情：http://localhost:8000/forum/post/{帖子ID}
- 登录注册：http://localhost:8000/login

## 💡 使用指南

### 学习软件操作

1. 访问主页查看可用的软件配置
2. 选择要学习的软件
3. 按照工作流程逐步学习
4. 完成学习后可以查看进度

### 使用论坛

1. **登录系统**
   - 访问 http://localhost:8000/login
   - 使用已有账户登录或注册新账户

2. **发帖**
   - 登录后访问论坛
   - 点击"发帖"按钮
   - 填写标题、内容、选择分类
   - 提交发布

3. **回复**
   - 打开感兴趣的帖子
   - 在回复框中输入内容
   - 提交回复

4. **点赞**
   - 点击帖子或回复的点赞按钮
   - 再次点击取消点赞

### 创建新的学习内容

1. 复制配置模板：
   ```bash
   cp config/software_template.php config/your_software.php
   ```

2. 编辑配置文件，添加：
   - 软件基本信息
   - 界面元素（含模拟按钮）
   - 操作步骤
   - 工作流程

3. 重启服务器后即可使用

详细配置说明请参考：
- `config/software_template.php` - 配置模板
- `config/hardware_software.php` - 完整示例
- `WEB_GUIDE.md` - Web 版本详细文档

## 📁 项目结构

```
php-project-template/
├── composer.json                  # 项目依赖
├── composer.lock                  # 锁定的依赖版本
├── phpunit.xml                    # 测试配置
├── .env.database.example          # 数据库配置示例
├── .env                           # 数据库配置（本地）
├── init-db.php                    # 数据库初始化脚本
├── start.sh                       # CLI 启动脚本
├── README.md                      # 项目说明文档
├── STARTUP_GUIDE.md               # 启动指南
├── WEB_GUIDE.md                   # Web 使用指南
├── AGENTS.md                      # AI 上下文文档
│
├── src/                           # 源代码目录
│   ├── Controllers/               # Web API 控制器
│   │   ├── SoftwareController.php # 软件学习控制器
│   │   ├── ForumController.php    # 论坛控制器
│   │   └── UserController.php     # 用户认证控制器
│   ├── Middleware/                # 中间件
│   │   └── AuthMiddleware.php     # 认证中间件
│   ├── SoftwareInfo.php           # 软件信息模型
│   ├── Config.php                 # 配置管理
│   ├── Logger.php                 # 日志功能
│   ├── InterfaceRecognizer.php    # 界面识别模块
│   ├── BasicOperations.php        # 基础操作模块
│   ├── WorkflowSimulator.php      # 工作流程模块
│   ├── Calculator.php             # 计算器工具类
│   ├── Database.php               # 数据库连接类
│   ├── ForumModel.php             # 论坛数据模型
│   └── AuthService.php            # 用户认证服务
│
├── config/                        # 配置文件目录
│   ├── app.php                    # 应用配置
│   ├── gimp.php                   # GIMP 软件配置
│   ├── hardware_software.php      # 硬件软件配置示例
│   └── software_template.php      # 软件配置模板
│
├── public/                        # Web 公共目录
│   ├── index.html                 # 主页
│   ├── software.html              # 软件学习界面
│   ├── forum.html                 # 论坛列表页
│   ├── forum-post.html            # 帖子详情页
│   ├── login.html                 # 登录注册页
│   └── index.php                  # Web 入口文件（API 路由）
│
├── database/                      # 数据库脚本
│   └── schema.sql                 # 数据库表结构
│
├── tests/                         # 测试目录
│   ├── Unit/                      # 单元测试
│   └── Integration/               # 集成测试
│
├── docs/                          # 文档目录
├── logs/                          # 日志文件目录
└── vendor/                        # Composer 依赖
```

## 🔧 开发指南

### 运行测试

```bash
composer test
```

### 代码规范检查

```bash
composer cs-check    # 检查
composer cs-fix      # 自动修复
```

### 调试

```bash
# 查看日志
tail -f logs/app.log

# 测试 API
curl http://localhost:8000/api/software
curl http://localhost:8000/api/forum/posts
curl http://localhost:8000/api/auth/check
```

## 🔒 安全特性

- ✅ 密码使用 `password_hash()` 加密存储
- ✅ SQL 注入防护（使用 PDO 预处理语句）
- ✅ XSS 防护（前端输入转义）
- ✅ CSRF 防护（会话验证）
- ✅ 操作权限验证（认证中间件）

## 📊 数据库表结构

- `users` - 用户表
- `forum_posts` - 帖子表
- `forum_replies` - 回复表
- `post_likes` - 帖子点赞表
- `reply_likes` - 回复点赞表

## ❓ 常见问题

### 安装和配置

**Q: 数据库连接失败？**
```bash
# 1. 检查数据库服务是否启动
ps aux | grep mysql

# 2. 检查配置文件
cat .env

# 3. 测试连接
php init-db.php
```

**Q: 端口被占用？**
```bash
# 使用其他端口
php -S localhost:8080 -t public/
```

### 使用问题

**Q: 无法发帖？**
- 确认已登录
- 检查浏览器控制台错误
- 查看网络请求状态

**Q: 如何重置管理员密码？**
```bash
mysql -u root
USE software_learning_platform;
UPDATE users SET password_hash = '$2y$12$YourHashedPassword' WHERE username = 'admin';
```

### 开发问题

**Q: 如何添加新的 API 端点？**
1. 在控制器中添加方法
2. 在 `public/index.php` 中注册路由
3. 添加适当的中间件（如需要认证）

**Q: 如何修改前端样式？**
编辑 `public/` 目录下的 HTML 文件中的 `<style>` 标签。

## 📝 许可证

MIT License

## 🤝 贡献

欢迎提交 Issue 和 Pull Request！

## 📧 联系方式

如有问题或建议，请通过 Issue 联系。

---

**版本：** 2.0.0  
**最后更新：** 2026-03-13