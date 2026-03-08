<?php

namespace App;

/**
 * 基础操作模块
 * 用于学习和模拟软件的基本操作
 */
class BasicOperations
{
    private SoftwareInfo $software;
    private Logger $logger;
    private array $learnedOperations;

    public function __construct(SoftwareInfo $software, Logger $logger)
    {
        $this->software = $software;
        $this->logger = $logger;
        $this->learnedOperations = [];
    }

    /**
     * 显示所有可用操作
     */
    public function showAvailableOperations(): void
    {
        $this->logger->info("Displaying available operations for {$this->software->getName()}");

        echo "\n=== {$this->software->getName()} 可用操作 ===\n\n";

        $operations = $this->software->getOperations();

        if (empty($operations)) {
            echo "【提示】操作尚未定义，请在配置文件中添加。\n";
            echo "\n常见操作类别：\n";
            echo "- 文件操作 (file): 新建、打开、保存、导出等\n";
            echo "- 编辑操作 (edit): 撤销、重做、复制、粘贴等\n";
            echo "- 视图操作 (view): 缩放、全屏、显示/隐藏面板等\n";
            echo "- 工具操作 (tools): 选择、画笔、橡皮擦等\n";
            return;
        }

        $categories = [];
        foreach ($operations as $operation) {
            $category = $operation['category'] ?? 'general';
            $categories[$category][] = $operation;
        }

        foreach ($categories as $category => $ops) {
            $categoryName = $this->translateCategory($category);
            echo "【{$categoryName}】\n";

            foreach ($ops as $index => $op) {
                $name = $op['name'] ?? '未命名';
                $shortcut = $op['shortcut'] ?? '无';
                $status = in_array($name, $this->learnedOperations) ? '✓' : ' ';

                echo "  {$status} " . ($index + 1) . ". {$name}";
                if ($shortcut !== '无') {
                    echo " ({$shortcut})";
                }
                echo "\n";
            }
            echo "\n";
        }
    }

    /**
     * 翻译操作类别
     */
    private function translateCategory(string $category): string
    {
        $translations = [
            'file' => '文件操作',
            'edit' => '编辑操作',
            'view' => '视图操作',
            'image' => '图像操作',
            'layer' => '图层操作',
            'filter' => '滤镜操作',
            'tools' => '工具操作',
            'window' => '窗口操作',
            'help' => '帮助',
            'general' => '通用操作',
        ];

        return $translations[$category] ?? $category;
    }

    /**
     * 学习并模拟执行特定操作
     */
    public function performOperation(string $operationName): void
    {
        $this->logger->info("Performing operation: {$operationName}");

        $operations = $this->software->getOperations();

        foreach ($operations as $operation) {
            if (isset($operation['name']) && $operation['name'] === $operationName) {
                echo "\n=== 操作: {$operationName} ===\n";

                if (isset($operation['description'])) {
                    echo "描述: {$operation['description']}\n\n";
                }

                if (isset($operation['steps'])) {
                    echo "操作步骤:\n";
                    foreach ($operation['steps'] as $index => $step) {
                        echo "  " . ($index + 1) . ". {$step}\n";
                    }
                }

                if (isset($operation['shortcut'])) {
                    echo "\n快捷键: {$operation['shortcut']}\n";
                }

                if (isset($operation['menu_path'])) {
                    echo "菜单路径: {$operation['menu_path']}\n";
                }

                if (isset($operation['tips'])) {
                    echo "\n提示:\n";
                    foreach ($operation['tips'] as $tip) {
                        echo "  • {$tip}\n";
                    }
                }

                $this->learnedOperations[] = $operationName;
                echo "\n✓ 操作已学习: {$operationName}\n";
                return;
            }
        }

        echo "✗ 未找到操作: {$operationName}\n";
    }

    /**
     * 交互式操作学习模式
     */
    public function interactiveMode(): void
    {
        $this->logger->info("Starting interactive operation learning mode");

        echo "\n=== 交互式操作学习模式 ===\n";
        echo "输入 'help' 查看可用命令\n";
        echo "输入 'exit' 退出学习模式\n\n";

        while (true) {
            echo "\n操作> ";
            $input = trim(fgets(STDIN));

            if ($input === 'exit') {
                echo "退出操作学习模式\n";
                break;
            }

            if ($input === 'help') {
                $this->showHelp();
                continue;
            }

            if ($input === 'list') {
                $this->showAvailableOperations();
                continue;
            }

            if ($input === 'progress') {
                $this->showProgress();
                continue;
            }

            if ($input === 'practice') {
                $this->startPracticeMode();
                continue;
            }

            if (!empty($input)) {
                $this->performOperation($input);
            }
        }
    }

    /**
     * 练习模式
     */
    private function startPracticeMode(): void
    {
        echo "\n=== 练习模式 ===\n";
        echo "系统将随机选择一个已定义的操作，请尝试使用快捷键或菜单执行它。\n\n";

        $operations = $this->software->getOperations();

        if (empty($operations)) {
            echo "【提示】没有可用的操作进行练习，请先在配置文件中添加操作。\n";
            return;
        }

        $randomOperation = $operations[array_rand($operations)];
        $operationName = $randomOperation['name'] ?? '未命名';

        echo "请执行以下操作:\n";
        echo "操作名称: {$operationName}\n";

        if (isset($randomOperation['shortcut'])) {
            echo "快捷键提示: {$randomOperation['shortcut']}\n";
        }

        if (isset($randomOperation['menu_path'])) {
            echo "菜单提示: {$randomOperation['menu_path']}\n";
        }

        echo "\n按 Enter 键继续下一个练习...\n";
        fgets(STDIN);
    }

    private function showHelp(): void
    {
        echo "\n可用命令:\n";
        echo "  list        - 显示所有可用操作\n";
        echo "  [操作名]    - 学习指定的操作\n";
        echo "  practice    - 进入练习模式\n";
        echo "  progress    - 显示学习进度\n";
        echo "  help        - 显示帮助信息\n";
        echo "  exit        - 退出学习模式\n";
    }

    private function showProgress(): void
    {
        $total = count($this->software->getOperations());
        $learned = count($this->learnedOperations);
        $percentage = $total > 0 ? round(($learned / $total) * 100, 1) : 0;

        echo "\n=== 学习进度 ===\n";
        echo "已学习: {$learned}/{$total}\n";
        echo "完成度: {$percentage}%\n";

        if (!empty($this->learnedOperations)) {
            echo "\n已学习的操作:\n";
            foreach ($this->learnedOperations as $operation) {
                echo "  ✓ {$operation}\n";
            }
        }
    }

    /**
     * 获取已学习的操作
     */
    public function getLearnedOperations(): array
    {
        return $this->learnedOperations;
    }
}
