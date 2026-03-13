# 软件学习平台 - App 打包指南

## 概述

本项目的 `app/` 目录是一个独立的移动应用子项目，使用 **Capacitor** 框架将 Web 应用打包成 Android APK。

### 为什么使用 Capacitor？

- 🚀 性能优秀，接近原生体验
- 🔄 自动同步 Web 内容
- 📱 支持 Android 平台
- 🛠️ 配置简单，易于使用
- 🔌 丰富的插件生态

### 项目结构

```
php-project-template/
├── public/              # Web 应用源码
├── app/                 # 移动应用子项目
│   ├── android/         # Android 原生项目（构建后生成）
│   ├── node_modules/    # 依赖包
│   ├── capacitor.config.json     # Capacitor 配置
│   ├── capacitor.config.local.json # 本地开发配置
│   ├── package.json     # 项目依赖
│   ├── build.sh         # 构建脚本
│   └── README.md        # 详细文档
└── ...
```

## 快速开始

### 方法一：使用构建脚本（推荐）

```bash
cd app
./build.sh
```

脚本会自动完成：
1. 检查和安装依赖
2. 初始化 Capacitor
3. 添加 Android 平台
4. 同步 Web 内容

### 方法二：手动构建

```bash
# 1. 进入 app 目录
cd app

# 2. 安装依赖
npm install

# 3. 添加 Android 平台
npx cap add android

# 4. 同步 Web 内容
npx cap sync android

# 5. 打开 Android Studio
npx cap open android
```

## 开发流程

### 修改 Web 应用

所有 Web 应用的修改都在 `public/` 目录中进行：

1. 修改 Web 应用代码
2. 修改后同步到 App：
   ```bash
   cd app
   npx cap sync android
   ```

### 本地开发调试

1. 启动 Web 服务器：
   ```bash
   bash web-server.sh start --tunnel
   ```

2. 获取本地 IP 地址：
   ```bash
   ip addr show wlan0 | grep 'inet ' | awk '{print $2}' | cut -d/ -f1
   ```

3. 修改 `app/capacitor.config.local.json`：
   ```json
   {
     "server": {
       "url": "http://YOUR_IP:8080",
       "cleartext": true
     }
   }
   ```

4. 同步并运行：
   ```bash
   npx cap sync android
   npx cap run android
   ```

## 构建 APK

### Debug 版本（调试用）

在 Android Studio 中：
1. 选择 `Build > Build Bundle(s) / APK(s) > Build APK(s)`
2. APK 位置：`app/android/app/build/outputs/apk/debug/`

### Release 版本（发布用）

在 Android Studio 中：
1. 选择 `Build > Generate Signed Bundle / APK`
2. 选择 APK
3. 创建或选择密钥库
4. 选择 `release` 构建变体
5. APK 位置：`app/android/app/build/outputs/apk/release/`

## 配置说明

### 应用信息

编辑 `app/capacitor.config.json`：

```json
{
  "appId": "com.software.learning",    // 应用包名
  "appName": "软件学习平台",           // 应用名称
  "webDir": "../public"               // Web 目录
}
```

### 图标和启动画面

详细配置请参考 `app/ASSETS.md`。

### 权限配置

在 `app/android/app/src/main/AndroidManifest.xml` 中添加所需权限。

## 常用命令

```bash
# 进入 app 目录
cd app

# 安装依赖
npm install

# 同步 Web 内容
npx cap sync android

# 打开 Android Studio
npx cap open android

# 运行应用
npx cap run android

# 查看日志
adb logcat

# 清理构建
cd android
./gradlew clean

# 构建 Release APK
./gradlew assembleRelease
```

## 注意事项

1. **独立项目**：`app/` 目录是完全独立的子项目，不影响原 Web 项目
2. **自动同步**：Web 内容的修改需要手动同步到 App（`npx cap sync android`）
3. **平台要求**：需要在电脑上安装 Android Studio 和 Android SDK
4. **密钥管理**：发布版本需要使用签名密钥，请妥善保管
5. **版本更新**：Web 应用更新后需要重新构建 APK

## 文档索引

- `app/README.md` - 详细文档
- `app/QUICKSTART.md` - 快速开始指南
- `app/ASSETS.md` - 图标和启动画面配置
- `app/build.sh` - 构建脚本

## 技术支持

- Capacitor 官方文档：https://capacitorjs.com/docs
- Android 开发文档：https://developer.android.com/

## 许可证

MIT License