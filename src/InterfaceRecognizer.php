<?php

namespace App;

/**
 * 界面识别模块
 * 用于学习和识别软件界面的各个组成部分
 */
class InterfaceRecognizer
{
    private SoftwareInfo $software;
    private Logger $logger;
    private array $recognizedElements;

    public function __construct(SoftwareInfo $software, Logger $logger)
    {
        $this->software = $software;
        $this->logger = $logger;
        $this->recognizedElements = [];
    }

    /**
     * 显示软件界面的主要组成部分
     */
    public function showInterfaceStructure(): void
    {
        $this->logger->info("Displaying interface structure for {$this->software->getName()}");

        echo "\n=== {$this->software->getName()} 界面结构 ===\n\n";

        $elements = $this->software->getInterfaceElements();

        if (empty($elements)) {
            echo "【提示】界面元素尚未定义，请在配置文件中添加。\n";
            echo "\n支持的界面元素类型：\n";
            echo "- 菜单栏\n";
            echo "- 工具栏\n";
            echo "- 面板\n";
            echo "- 状态栏\n";
            echo "- 工具箱\n";
            echo "- 画布\n";
            echo "- 属性栏\n";
            echo "- 对话框\n";
            return;
        }

        foreach ($elements as $index => $element) {
            $this->displayElement($element, $index + 1);
        }
    }

    /**
     * 显示单个界面元素详情
     */
    private function displayElement(array $element, int $index): void
    {
        $name = $element['name'] ?? '未命名元素';
        $type = $element['type'] ?? 'unknown';
        $description = $element['description'] ?? '暂无描述';
        $components = $element['components'] ?? [];

        echo "{$index}. 【{$type}】{$name}\n";
        echo "   描述: {$description}\n";

        if (!empty($components)) {
            echo "   包含组件:\n";
            foreach ($components as $component) {
                echo "     - {$component}\n";
            }
        }
        echo "\n";
    }

    /**
     * 学习特定的界面元素
     */
    public function learnElement(string $elementName): void
    {
        $this->logger->info("Learning element: {$elementName}");

        $elements = $this->software->getInterfaceElements();

        foreach ($elements as $element) {
            if (isset($element['name']) && $element['name'] === $elementName) {
                echo "\n=== 学习: {$elementName} ===\n";
                echo "类型: {$element['type']}\n";
                echo "描述: {$element['description']}\n";

                if (isset($element['usage'])) {
                    echo "\n使用方法:\n";
                    echo $element['usage'] . "\n";
                }

                if (isset($element['shortcuts'])) {
                    echo "\n快捷键:\n";
                    foreach ($element['shortcuts'] as $shortcut => $action) {
                        echo "  {$shortcut}: {$action}\n";
                    }
                }

                $this->recognizedElements[] = $elementName;
                echo "\n✓ 已识别并学习: {$elementName}\n";
                return;
            }
        }

        echo "✗ 未找到界面元素: {$elementName}\n";
    }

    /**
     * 获取已识别的界面元素列表
     */
    public function getRecognizedElements(): array
    {
        return $this->recognizedElements;
    }

    /**
     * 交互式界面学习模式
     */
    public function interactiveLearning(): void
    {
        $this->logger->info("Starting interactive interface learning mode");

        echo "\n=== 交互式界面学习模式 ===\n";
        echo "输入 'help' 查看可用命令\n";
        echo "输入 'exit' 退出学习模式\n\n";

        while (true) {
            echo "\n> ";
            $input = trim(fgets(STDIN));

            if ($input === 'exit') {
                echo "退出学习模式\n";
                break;
            }

            if ($input === 'help') {
                $this->showHelp();
                continue;
            }

            if ($input === 'list') {
                $this->showInterfaceStructure();
                continue;
            }

            if ($input === 'progress') {
                $this->showProgress();
                continue;
            }

            if (!empty($input)) {
                $this->learnElement($input);
            }
        }
    }

    private function showHelp(): void
    {
        echo "\n可用命令:\n";
        echo "  list        - 显示所有界面元素\n";
        echo "  [元素名]    - 学习指定的界面元素\n";
        echo "  progress    - 显示学习进度\n";
        echo "  help        - 显示帮助信息\n";
        echo "  exit        - 退出学习模式\n";
    }

    private function showProgress(): void
    {
        $total = count($this->software->getInterfaceElements());
        $learned = count($this->recognizedElements);
        $percentage = $total > 0 ? round(($learned / $total) * 100, 1) : 0;

        echo "\n=== 学习进度 ===\n";
        echo "已学习: {$learned}/{$total}\n";
        echo "完成度: {$percentage}%\n";

        if (!empty($this->recognizedElements)) {
            echo "\n已识别的元素:\n";
            foreach ($this->recognizedElements as $element) {
                echo "  ✓ {$element}\n";
            }
        }
    }
}
