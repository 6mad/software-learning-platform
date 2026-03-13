#!/bin/bash

# 软件学习平台 - Android App 构建脚本

set -e

echo "======================================"
echo "软件学习平台 - Android App 构建"
echo "======================================"
echo ""

# 检查是否在 app 目录
if [ ! -f "package.json" ]; then
    echo "错误: 请在 app 目录中运行此脚本"
    exit 1
fi

# 检查依赖
if [ ! -d "node_modules" ]; then
    echo "安装依赖..."
    npm install
fi

# 初始化 Capacitor（如果需要）
if [ ! -f "capacitor.config.json" ]; then
    echo "初始化 Capacitor..."
    npx cap init "软件学习平台" com.software.learning
fi

# 添加 Android 平台（如果不存在）
if [ ! -d "android" ]; then
    echo "添加 Android 平台..."
    npx cap add android
fi

# 同步 Web 内容
echo "同步 Web 内容..."
npx cap sync android

echo ""
echo "======================================"
echo "构建完成！"
echo "======================================"
echo ""
echo "下一步操作："
echo "1. 打开 Android Studio:"
echo "   npx cap open android"
echo ""
echo "2. 在 Android Studio 中运行应用"
echo ""
echo "3. 或使用命令行构建:"
echo "   cd android"
echo "   ./gradlew assembleRelease"
echo ""