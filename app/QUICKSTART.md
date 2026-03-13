# 快速开始 - 打包 Android APK

## 前提条件

1. 电脑上已安装：
   - Node.js (v16+)
   - Android Studio
   - Android SDK

2. 已配置好 Java 环境（Android Studio 自带）

## 步骤一：安装依赖

```bash
cd app
npm install
```

## 步骤二：添加 Android 平台

```bash
npx cap add android
```

## 步骤三：同步 Web 内容

```bash
npx cap sync android
```

## 步骤四：打开 Android Studio

```bash
npx cap open android
```

## 步骤五：在 Android Studio 中构建

1. 选择要连接的设备或启动模拟器
2. 点击运行按钮（绿色三角形）

## 构建 APK

### Debug 版本（调试用）

1. 在 Android Studio 中选择 `Build > Build Bundle(s) / APK(s) > Build APK(s)`
2. APK 位置：`android/app/build/outputs/apk/debug/`

### Release 版本（发布用）

1. 选择 `Build > Generate Signed Bundle / APK`
2. 选择 APK
3. 创建新的密钥库（如果还没有）：
   - Key store path: 选择保存位置
   - Password: 设置密码
   - Key alias: 设置别名
   - Key password: 设置密钥密码
   - Validity: 25年或更长
4. 选择 `release` 构建变体
5. 点击 `Finish`
6. APK 位置：`android/app/build/outputs/apk/release/`

## 本地开发

### 1. 启动 Web 服务器

```bash
cd ..
bash web-server.sh start --tunnel
```

### 2. 获取本地 IP

```bash
ip addr show wlan0 | grep 'inet ' | awk '{print $2}' | cut -d/ -f1
```

### 3. 修改配置

编辑 `app/capacitor.config.local.json`：

```json
{
  "server": {
    "url": "http://YOUR_IP:8080",
    "cleartext": true
  }
}
```

### 4. 同步并运行

```bash
cd app
npx cap sync android
npx cap open android
```

## 常见问题

### Q: 构建失败，提示 SDK 版本问题

A: 在 Android Studio 中：
1. 打开 SDK Manager
2. 安装所需的 SDK 版本
3. 确保已安装 Android SDK Build-Tools

### Q: 无法连接到本地服务器

A: 确保：
1. 设备和电脑在同一网络
2. 电脑防火墙允许 8080 端口
3. 使用 `http://` 而不是 `https://`

### Q: 应用闪退

A: 检查：
1. 日志：`adb logcat`
2. 网络连接是否正常
3. 配置文件是否正确

## 有用的命令

```bash
# 查看日志
adb logcat

# 清理构建
cd android
./gradlew clean

# 重新构建
./gradlew assembleDebug

# 安装到设备
adb install -r android/app/build/outputs/apk/debug/app-debug.apk

# 卸载应用
adb uninstall com.software.learning
```

## 提示

- 首次构建可能需要较长时间（下载依赖）
- Debug 版本可以快速测试
- Release 版本需要签名才能发布
- 建议使用 Git 管理代码版本
- 定期同步 Web 内容到 App