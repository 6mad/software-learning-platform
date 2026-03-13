#!/bin/bash

# Web 服务器管理脚本
# 支持 nginx + PHP 内置服务器，支持 IPv4 和 IPv6

# 配置
NGINX_CONF="${NGINX_CONF:-nginx.conf}"
NGINX_PORT="${NGINX_PORT:-8080}"
PHP_HOST="${PHP_HOST:-127.0.0.1}"
PHP_PORT="${PHP_PORT:-8000}"
DOCUMENT_ROOT="${DOCUMENT_ROOT:-public}"
NGINX_PID_FILE=".nginx.pid"
PHP_PID_FILE=".php.pid"
TUNNEL_PID_FILE=".tunnel.pid"
NGINX_LOG_FILE="logs/nginx_error.log"
PHP_LOG_FILE="logs/web_server.log"
TUNNEL_LOG_FILE="logs/tunnel.log"

# nginx 进程匹配关键词
NGINX_PROCESS_KEYWORD="nginx.*$(pwd)/nginx.conf"
# 内网隧道标志
START_TUNNEL=false

# 颜色输出
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# 打印带颜色的消息
print_message() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${NC}"
}

# 检查并创建日志目录
ensure_log_dir() {
    if [ ! -d "logs" ]; then
        mkdir -p logs
    fi
}

# 启动服务器
start_server() {
    # 检查是否已经在运行
    if [ -f "$NGINX_PID_FILE" ] || [ -f "$PHP_PID_FILE" ]; then
        if [ -f "$NGINX_PID_FILE" ]; then
            local nginx_pid=$(cat "$NGINX_PID_FILE")
            if ps -p $nginx_pid > /dev/null 2>&1; then
                print_message "$YELLOW" "Nginx 服务器已经在运行 (PID: $nginx_pid)"
                print_message "$BLUE" "使用 '$0 status' 查看状态"
                print_message "$BLUE" "使用 '$0 logs' 查看日志"
                return 1
            fi
        fi
        if [ -f "$PHP_PID_FILE" ]; then
            local php_pid=$(cat "$PHP_PID_FILE")
            if ps -p $php_pid > /dev/null 2>&1; then
                print_message "$YELLOW" "PHP 服务器已经在运行 (PID: $php_pid)"
                print_message "$BLUE" "使用 '$0 status' 查看状态"
                print_message "$BLUE" "使用 '$0 logs' 查看日志"
                return 1
            fi
        fi
        # 清理过期的 PID 文件
        rm -f "$NGINX_PID_FILE" "$PHP_PID_FILE"
    fi

    # 确保日志目录存在
    ensure_log_dir

    # 启动 PHP 内置服务器
    print_message "$BLUE" "启动 PHP 内置服务器..."
    nohup php -S "${PHP_HOST}:${PHP_PORT}" -t "$DOCUMENT_ROOT" >> "$PHP_LOG_FILE" 2>&1 &
    local php_pid=$!
    echo $php_pid > "$PHP_PID_FILE"

    # 等待 PHP 服务器启动
    sleep 2

    if ! ps -p $php_pid > /dev/null 2>&1; then
        print_message "$RED" "✗ PHP 服务器启动失败"
        print_message "$RED" "请查看日志文件: $PHP_LOG_FILE"
        rm -f "$PHP_PID_FILE"
        return 1
    fi

    print_message "$GREEN" "✓ PHP 服务器启动成功! (PID: $php_pid)"

    # 启动 Nginx

        print_message "$BLUE" "启动 Nginx 服务器..."

        nginx -c "$(pwd)/$NGINX_CONF" -p "$PREFIX" > "$NGINX_LOG_FILE" 2>&1 &

    

        # 获取 nginx 主进程 PID

        sleep 2

        local nginx_pid=$(pgrep -f "nginx.*master.*$(pwd)/nginx.conf" | head -n 1)

    

        if [ -z "$nginx_pid" ]; then

            print_message "$RED" "✗ Nginx 服务器启动失败"

            print_message "$RED" "请查看日志文件: $NGINX_LOG_FILE"

            # 停止 PHP 服务器

            kill $php_pid 2>/dev/null

            rm -f "$NGINX_PID_FILE" "$PHP_PID_FILE"

            return 1

        fi

    

        echo $nginx_pid > "$NGINX_PID_FILE"

    print_message "$GREEN" "✓ Nginx 服务器启动成功!"
    print_message "$GREEN" "  Nginx PID: $nginx_pid"
    print_message "$GREEN" "  PHP PID: $php_pid"
    print_message "$GREEN" "\n访问地址:"
    print_message "$GREEN" "  IPv4: http://127.0.0.1:${NGINX_PORT}"
    print_message "$GREEN" "  IPv6: http://[240a:42c8:9000:12:e8f8:7bff:fe77:682c]"
    print_message "$GREEN" "  本地: http://localhost"
    print_message "$GREEN" "\n日志文件:"
    print_message "$GREEN" "  Nginx: $NGINX_LOG_FILE"
    print_message "$GREEN" "  PHP: $PHP_LOG_FILE"
    print_message "$BLUE" "\n常用命令:"
    print_message "$BLUE" "  查看日志: $0 logs"
    print_message "$BLUE" "  查看状态: $0 status"
    print_message "$BLUE" "  停止服务: $0 stop"
    print_message "$BLUE" "  重启服务: $0 restart"

    # 启动内网隧道
    if [ "$START_TUNNEL" = true ]; then
        print_message "$BLUE" "\n启动内网穿透隧道..."
        print_message "$BLUE" "  使用 serveo.net (基于 SSH)"
        print_message "$YELLOW" "  提示: 首次连接可能需要手动输入 'yes' 接受 SSH 密钥"
        print_message "$BLUE" ""

        nohup ssh -o ServerAliveInterval=60 -o ServerAliveCountMax=3 -o ExitOnForwardFailure=yes -R 80:localhost:${NGINX_PORT} serveo.net >> "$TUNNEL_LOG_FILE" 2>&1 &
        local tunnel_pid=$!
        echo $tunnel_pid > "$TUNNEL_PID_FILE"

        # 等待隧道连接建立
        sleep 5

        if ps -p $tunnel_pid > /dev/null 2>&1; then
            print_message "$GREEN" "✓ 内网隧道已启动 (PID: $tunnel_pid)"

            # 尝试从日志中提取公网地址
            local tunnel_url=$(grep -o "https://[a-z0-9-]*\.serveousercontent\.com" "$TUNNEL_LOG_FILE" 2>/dev/null | tail -n 1)

            if [ -n "$tunnel_url" ]; then
                print_message "$GREEN" "\n════════════════════════════════════════"
                print_message "$GREEN" "  🌐 公网访问地址:"
                print_message "$GREEN" "  $tunnel_url"
                print_message "$GREEN" "════════════════════════════════════════"

                # 尝试复制到粘贴板
                if command -v termux-clipboard-set >/dev/null 2>&1; then
                    echo "$tunnel_url" | timeout 2 termux-clipboard-set 2>/dev/null
                    if [ $? -eq 0 ]; then
                        print_message "$GREEN" "  ✓ 地址已复制到粘贴板"
                    fi
                fi
                print_message "$GREEN" ""
            else
                print_message "$YELLOW" "  正在获取公网地址，请稍候..."
                print_message "$YELLOW" "  查看: $0 logs tunnel"
            fi
        else
            print_message "$RED" "✗ 内网隧道启动失败"
            print_message "$RED" "  请查看日志: $TUNNEL_LOG_FILE"
            rm -f "$TUNNEL_PID_FILE"
        fi
    fi
}

# 停止服务器
stop_server() {
    local stopped=0

    # 停止 Nginx
    if [ -f "$NGINX_PID_FILE" ]; then
        local nginx_pid=$(cat "$NGINX_PID_FILE")
        if ps -p $nginx_pid > /dev/null 2>&1; then
            print_message "$BLUE" "停止 Nginx 服务器 (PID: $nginx_pid)..."
            kill $nginx_pid
            sleep 1
            if ! ps -p $nginx_pid > /dev/null 2>&1; then
                print_message "$GREEN" "✓ Nginx 服务器已停止"
            else
                print_message "$YELLOW" "强制停止 Nginx 服务器..."
                kill -9 $nginx_pid
            fi
        fi
        rm -f "$NGINX_PID_FILE"
        stopped=1
    fi

    # 停止 PHP 服务器
    if [ -f "$PHP_PID_FILE" ]; then
        local php_pid=$(cat "$PHP_PID_FILE")
        if ps -p $php_pid > /dev/null 2>&1; then
            print_message "$BLUE" "停止 PHP 服务器 (PID: $php_pid)..."
            kill $php_pid
            sleep 1
            if ! ps -p $php_pid > /dev/null 2>&1; then
                print_message "$GREEN" "✓ PHP 服务器已停止"
            else
                print_message "$YELLOW" "强制停止 PHP 服务器..."
                kill -9 $php_pid
            fi
        fi
        rm -f "$PHP_PID_FILE"
        stopped=1
    fi

    # 停止内网隧道
    if [ -f "$TUNNEL_PID_FILE" ]; then
        local tunnel_pid=$(cat "$TUNNEL_PID_FILE")
        if ps -p $tunnel_pid > /dev/null 2>&1; then
            print_message "$BLUE" "停止内网隧道 (PID: $tunnel_pid)..."
            kill $tunnel_pid
            sleep 1
            if ! ps -p $tunnel_pid > /dev/null 2>&1; then
                print_message "$GREEN" "✓ 内网隧道已停止"
            else
                print_message "$YELLOW" "强制停止内网隧道..."
                kill -9 $tunnel_pid
            fi
        fi
        rm -f "$TUNNEL_PID_FILE"
        stopped=1
    fi

    if [ $stopped -eq 0 ]; then
        print_message "$YELLOW" "服务器未运行"
    fi
}

# 重启服务器
restart_server() {
    print_message "$BLUE" "重启 Web 服务器..."
    stop_server
    sleep 2
    start_server
}

# 查看状态
show_status() {
    local running=0

    print_message "$BLUE" "服务器状态:"
    echo ""

    if [ -f "$NGINX_PID_FILE" ]; then
        local nginx_pid=$(cat "$NGINX_PID_FILE")
        if ps -p $nginx_pid > /dev/null 2>&1; then
            print_message "$GREEN" "Nginx 服务器: 运行中"
            print_message "$GREEN" "  PID: $nginx_pid"
            ps -p $nginx_pid -o pid,%cpu,%mem,etime,cmd --no-headers
            running=1
        else
            print_message "$RED" "Nginx 服务器: 未运行 (PID 文件过期)"
            rm -f "$NGINX_PID_FILE"
        fi
    else
        print_message "$YELLOW" "Nginx 服务器: 未运行"
    fi

    echo ""

    if [ -f "$PHP_PID_FILE" ]; then
        local php_pid=$(cat "$PHP_PID_FILE")
        if ps -p $php_pid > /dev/null 2>&1; then
            print_message "$GREEN" "PHP 服务器: 运行中"
            print_message "$GREEN" "  PID: $php_pid"
            ps -p $php_pid -o pid,%cpu,%mem,etime,cmd --no-headers
            running=1
        else
            print_message "$RED" "PHP 服务器: 未运行 (PID 文件过期)"
            rm -f "$PHP_PID_FILE"
        fi
    else
        print_message "$YELLOW" "PHP 服务器: 未运行"
    fi

    echo ""

    if [ -f "$TUNNEL_PID_FILE" ]; then
        local tunnel_pid=$(cat "$TUNNEL_PID_FILE")
        if ps -p $tunnel_pid > /dev/null 2>&1; then
            print_message "$GREEN" "内网隧道: 运行中"
            print_message "$GREEN" "  PID: $tunnel_pid"
            ps -p $tunnel_pid -o pid,%cpu,%mem,etime,cmd --no-headers

            # 提取并显示公网地址
            local tunnel_url=$(grep -o "https://[a-z0-9-]*\.serveousercontent\.com" "$TUNNEL_LOG_FILE" 2>/dev/null | tail -n 1)
            if [ -n "$tunnel_url" ]; then
                print_message "$GREEN" "  公网地址: $tunnel_url"
            fi
            running=1
        else
            print_message "$RED" "内网隧道: 未运行 (PID 文件过期)"
            rm -f "$TUNNEL_PID_FILE"
        fi
    else
        print_message "$YELLOW" "内网隧道: 未运行"
    fi

    if [ $running -eq 0 ]; then
        echo ""
        print_message "$YELLOW" "没有服务器在运行"
    fi
}

# 查看日志
show_logs() {
    local log_type=${2:-all}

    if [ "$log_type" = "tunnel" ]; then
        print_message "$BLUE" "内网隧道日志 (最近 30 行):"
        if [ -f "$TUNNEL_LOG_FILE" ]; then
            tail -n 30 "$TUNNEL_LOG_FILE"

            # 提取并显示公网地址
            local tunnel_url=$(grep -o "https://[a-z0-9-]*\.serveousercontent\.com" "$TUNNEL_LOG_FILE" 2>/dev/null | tail -n 1)
            if [ -n "$tunnel_url" ]; then
                echo ""
                print_message "$GREEN" "════════════════════════════════════════"
                print_message "$GREEN" "  🌐 当前公网地址:"
                print_message "$GREEN" "  $tunnel_url"
                print_message "$GREEN" "════════════════════════════════════════"
            fi
        else
            echo "  (文件不存在)"
        fi
        return 0
    fi

    if [ ! -f "$NGINX_LOG_FILE" ] && [ ! -f "$PHP_LOG_FILE" ]; then
        print_message "$YELLOW" "日志文件不存在"
        return 0
    fi

    local nginx_lines=${2:-20}
    local php_lines=${3:-20}

    print_message "$BLUE" "Nginx 日志 (最近 $nginx_lines 行):"
    if [ -f "$NGINX_LOG_FILE" ]; then
        tail -n "$nginx_lines" "$NGINX_LOG_FILE"
    else
        echo "  (文件不存在)"
    fi

    echo ""
    print_message "$BLUE" "PHP 日志 (最近 $php_lines 行):"
    if [ -f "$PHP_LOG_FILE" ]; then
        tail -n "$php_lines" "$PHP_LOG_FILE"
    else
        echo "  (文件不存在)"
    fi
}

# 清理日志
clear_logs() {
    local cleared=0

    if [ -f "$NGINX_LOG_FILE" ]; then
        print_message "$YELLOW" "清理 Nginx 日志: $NGINX_LOG_FILE"
        > "$NGINX_LOG_FILE"
        print_message "$GREEN" "✓ Nginx 日志已清理"
        cleared=1
    fi

    if [ -f "$PHP_LOG_FILE" ]; then
        print_message "$YELLOW" "清理 PHP 日志: $PHP_LOG_FILE"
        > "$PHP_LOG_FILE"
        print_message "$GREEN" "✓ PHP 日志已清理"
        cleared=1
    fi

    if [ -f "$TUNNEL_LOG_FILE" ]; then
        print_message "$YELLOW" "清理隧道日志: $TUNNEL_LOG_FILE"
        > "$TUNNEL_LOG_FILE"
        print_message "$GREEN" "✓ 隧道日志已清理"
        cleared=1
    fi

    if [ $cleared -eq 0 ]; then
        print_message "$YELLOW" "日志文件不存在"
    fi
}

# 显示帮助
show_help() {
    echo "Web 服务器管理脚本 (Nginx + PHP)"
    echo ""
    echo "用法: $0 [命令] [选项]"
    echo ""
    echo "命令:"
    echo "  start           启动 Web 服务器（后台运行，支持 IPv4 和 IPv6）"
    echo "  start --tunnel  启动 Web 服务器并启用内网穿透（serveo.net）"
    echo "  stop            停止 Web 服务器"
    echo "  restart         重启 Web 服务器"
    echo "  status          查看服务器状态（包含公网地址）"
    echo "  logs            查看服务器日志（Nginx 20 行，PHP 20 行）"
    echo "  logs tunnel     查看内网隧道日志和公网地址（无需 Ctrl+C）"
    echo "  logs [n] [m]    查看日志（Nginx n 行，PHP m 行）"
    echo "  clear           清理日志文件"
    echo "  help            显示此帮助信息"
    echo ""
    echo "架构:"
    echo "  Nginx:  监听 IPv4 和 IPv6"
    echo "  PHP:   监听 127.0.0.1:8000（仅本地）"
    echo "  隧道:  serveo.net (基于 SSH 的免费内网穿透)"
    echo ""
    echo "环境变量:"
    echo "  NGINX_CONF      Nginx 配置文件（默认: nginx.conf）"
    echo "  NGINX_PORT      Nginx 监听端口（默认: 8080）"
    echo "  PHP_HOST       PHP 服务器地址（默认: 127.0.0.1）"
    echo "  PHP_PORT       PHP 服务器端口（默认: 8000）"
    echo "  DOCUMENT_ROOT  文档根目录（默认: public）"
    echo ""
    echo "示例:"
    echo "  $0 start              # 启动服务器"
    echo "  $0 start --tunnel     # 启动服务器并启用内网穿透（自动显示公网地址）"
    echo "  NGINX_PORT=8080 $0 start # 使用端口 8080"
    echo "  $0 logs               # 查看日志"
    echo "  $0 logs tunnel        # 查看内网隧道日志和公网地址"
    echo "  $0 logs 50 100         # 查看 Nginx 50 行、PHP 100 行日志"
    echo "  $0 stop               # 停止服务器"
}

# 主函数
main() {
    local command=${1:-help}

    # 检查是否启动内网隧道
    if [ "$2" = "--tunnel" ]; then
        START_TUNNEL=true
        print_message "$BLUE" "将启动内网穿透隧道"
    fi

    case $command in
        start)
            start_server
            ;;
        stop)
            stop_server
            ;;
        restart)
            restart_server
            ;;
        status)
            show_status
            ;;
        logs)
            show_logs "$@"
            ;;
        clear)
            clear_logs
            ;;
        help|--help|-h)
            show_help
            ;;
        *)
            print_message "$RED" "未知命令: $command"
            echo ""
            show_help
            exit 1
            ;;
    esac
}

# 执行主函数
main "$@"