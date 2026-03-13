# 应用图标和启动画面配置

## 方法一：使用 Capacitor Assets（推荐）

1. 安装 Capacitor Assets 工具：

```bash
npm install -g @capacitor/assets
```

2. 准备一个 1024x1024 像素的图标文件（PNG 或 SVG）

3. 生成图标和启动画面：

```bash
capacitor-assets generate
```

4. 同步到 Android：

```bash
npx cap sync android
```

## 方法二：手动配置

### 图标

将图标文件放置到：

- Android: `android/app/src/main/res/mipmap-*/ic_launcher.png`

支持的尺寸：
- mipmap-mdpi: 48x48
- mipmap-hdpi: 72x72
- mipmap-xhdpi: 96x96
- mipmap-xxhdpi: 144x144
- mipmap-xxxhdpi: 192x192

### 启动画面

将启动画面放置到：

- Android: `android/app/src/main/res/drawable/splash.png`

推荐的尺寸：
- 竖屏: 1284x2778
- 横屏: 2778x1284

### 背景颜色

在 `capacitor.config.json` 中配置：

```json
{
  "plugins": {
    "SplashScreen": {
      "backgroundColor": "#667eea"
    }
  }
}
```

## 设计建议

### 图标设计
- 使用简洁、清晰的图标
- 避免过多的细节
- 确保在小尺寸下仍可识别
- 使用品牌主色调

### 启动画面设计
- 显示应用 Logo 或名称
- 简洁大方，不要过于复杂
- 使用品牌主色调
- 考虑不同设备的屏幕比例

## 在线工具

可以使用以下在线工具生成图标：

- [App Icon Generator](https://appicon.co/)
- [MakeAppIcon](https://makeappicon.com/)
- [Android Asset Studio](https://romannurik.github.io/AndroidAssetStudio/)