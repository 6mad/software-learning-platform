# 软件学习平台 - 移动应用

这是软件学习平台的移动应用版本，使用 Capacitor 框架构建。

## 功能特性

- 📱 原生移动应用体验
- 🌐 基于 Web 应用，实时同步最新功能
- 🎨 原生 UI 组件和性能
- 🔔 支持推送通知（可选）
- 📸 支持相机和文件访问（可选）

## 技术栈

- **框架**: Capacitor 6.x
- **平台**: Android
- **基础**: Web 应用（指向 ../public）
- **开发语言**: JavaScript/HTML/CSS

## 安装步骤

### 1. 安装依赖

```bash
cd app
npm install
```

### 2. 初始化 Capacitor

```bash
npx cap init "软件学习平台" com.software.learning
```

### 3. 添加 Android 平台

```bash
npx cap add android
```

### 4. 同步 Web 内容

```bash
npx cap sync android
```

### 5. 运行应用

需要在电脑上安装 Android Studio 和 Android SDK。

```bash
npx cap open android
```

然后在 Android Studio 中运行应用。

## 开发流程

### 修改 Web 应用

所有 Web 应用的修改都在 `../public` 目录中进行，修改后需要同步到 Android：

```bash
npx cap sync android
```

### 本地开发

如果需要本地开发服务器，先启动 Web 服务器：

```bash
cd ..
bash web-server.sh start
```

然后修改 `app/capacitor.config.json` 中的 `server.url` 指向本地服务器：

```json
{
  "server": {
    "url": "http://YOUR_IP:8080",
    "cleartext": true
  }
}
```

## 构建发布版

### 1. 在 Android Studio 中

1. 打开 Android Studio
2. 选择 `Build > Generate Signed Bundle / APK`
3. 选择 APK 或 App Bundle
4. 创建或选择密钥库
5. 选择发布版本
6. 构建完成

### 2. 使用命令行

```bash
cd android
./gradlew assembleRelease
```

APK 文件位于 `android/app/build/outputs/apk/release/`

## 配置说明

### 应用信息

在 `capacitor.config.json` 中修改：

```json
{
  "appId": "com.software.learning",
  "appName": "软件学习平台",
  "webDir": "../public"
}
```

### 图标和启动画面

使用 `@capacitor/assets` 生成图标和启动画面：

```bash
npm install -g @capacitor/assets
capacitor-assets generate
```

### 权限配置

在 `android/app/src/main/AndroidManifest.xml` 中配置所需权限。

## 项目结构

```
app/
├── android/           # Android 原生项目
├── capacitor.config.json  # Capacitor 配置
├── package.json       # 依赖配置
└── README.md          # 本文件
```

## 常见问题

### Q: 为什么无法连接到服务器？

A: 检查 `capacitor.config.json` 中的 `server.url` 配置，确保网络连接正常。

### Q: 如何调试应用？

A: 使用 Chrome DevTools：
1. 连接设备或启动模拟器
2. 在 Chrome 中输入 `chrome://inspect`
3. 选择你的应用

### Q: 如何更新应用？

A: 修改 Web 应用后运行 `npx cap sync android`，然后重新构建。

## 许可证

MIT License