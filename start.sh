#!/bin/bash

# 软件学习平台启动脚本

echo "软件学习平台启动脚本"
echo "====================="

# 检查PHP是否已安装
if ! command -v php &> /dev/null; then
    echo "错误: PHP未安装，请先安装PHP 8.0或更高版本"
    echo "在Termux中，您可以运行: pkg install php"
    exit 1
fi

# 检查Composer是否已安装
if ! command -v composer &> /dev/null; then
    echo "错误: Composer未安装，请先安装Composer"
    echo "在Termux中，您可以运行: pkg install php-composer 或手动安装Composer"
    exit 1
fi

echo "PHP版本: $(php --version)"
echo "开始安装项目依赖..."

# 安装依赖
composer install

if [ $? -ne 0 ]; then
    echo "错误: 依赖安装失败"
    exit 1
fi

echo "依赖安装完成"

# 检查并创建logs目录
if [ ! -d "logs" ]; then
    mkdir logs
    echo "创建logs目录"
fi

# 提示用户选择软件配置
echo ""
echo "可用的软件配置:"
echo ""

# 列出可用的配置文件
CONFIG_DIR="config"
if [ -d "$CONFIG_DIR" ]; then
    count=0
    for config_file in "$CONFIG_DIR"/*.php; do
        if [ -f "$config_file" ]; then
            filename=$(basename "$config_file" .php)
            # 跳过 app 和 template 文件
            if [ "$filename" != "app" ] && [ "$filename" != "software_template" ]; then
                count=$((count + 1))
                echo "  $count. $filename"
            fi
        fi
    done

    if [ $count -eq 0 ]; then
        echo "  无可用配置文件"
        echo ""
        echo "提示: 使用 config/software_template.php 作为模板创建新配置"
        echo "      例如: cp config/software_template.php config/gimp.php"
        echo "      然后编辑 gimp.php 填写软件信息"
        exit 0
    fi

    echo ""
    echo "请输入要学习的软件配置名称，或直接运行: php public/index.php [配置名]"
    echo ""
else
    echo "  配置目录不存在"
    exit 1
fi

# 运行示例应用
echo "运行软件学习平台..."
echo ""

# 如果有命令行参数，使用该参数
if [ $# -gt 0 ]; then
    php public/index.php "$1"
else
    # 否则显示帮助
    php public/index.php
fi