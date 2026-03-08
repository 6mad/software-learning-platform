<?php

namespace App;

/**
 * 工作流程模拟器
 * 用于学习和模拟完整的软件使用工作流程
 */
class WorkflowSimulator
{
    private SoftwareInfo $software;
    private Logger $logger;
    private array $completedWorkflows;

    public function __construct(SoftwareInfo $software, Logger $logger)
    {
        $this->software = $software;
        $this->logger = $logger;
        $this->completedWorkflows = [];
    }

    /**
     * 显示所有可用工作流程
     */
    public function showAvailableWorkflows(): void
    {
        $this->logger->info("Displaying available workflows for {$this->software->getName()}");

        echo "\n=== {$this->software->getName()} 可用工作流程 ===\n\n";

        $workflows = $this->software->getWorkflows();

        if (empty($workflows)) {
            echo "【提示】工作流程尚未定义，请在配置文件中添加。\n";
            echo "\n常见工作流程示例：\n";
            echo "- 基础图像编辑流程\n";
            echo "- 图层合成流程\n";
            echo "- 批量处理流程\n";
            echo "- 图片导出流程\n";
            return;
        }

        foreach ($workflows as $index => $workflow) {
            $name = $workflow['name'] ?? '未命名流程';
            $description = $workflow['description'] ?? '暂无描述';
            $difficulty = $workflow['difficulty'] ?? 'beginner';
            $estimatedTime = $workflow['estimated_time'] ?? '未知';
            $status = in_array($name, $this->completedWorkflows) ? '✓' : ' ';

            echo "{$status} " . ($index + 1) . ". {$name}\n";
            echo "     描述: {$description}\n";
            echo "     难度: {$difficulty} | 预计时间: {$estimatedTime}\n";
            echo "\n";
        }
    }

    /**
     * 模拟执行特定工作流程
     */
    public function simulateWorkflow(string $workflowName, bool $interactive = true): bool
    {
        $this->logger->info("Simulating workflow: {$workflowName}");

        $workflows = $this->software->getWorkflows();

        foreach ($workflows as $workflow) {
            if (isset($workflow['name']) && $workflow['name'] === $workflowName) {
                echo "\n" . str_repeat("=", 50) . "\n";
                echo "工作流程: {$workflowName}\n";
                echo str_repeat("=", 50) . "\n\n";

                if (isset($workflow['description'])) {
                    echo "【描述】\n{$workflow['description']}\n\n";
                }

                if (isset($workflow['prerequisites'])) {
                    echo "【前置条件】\n";
                    foreach ($workflow['prerequisites'] as $prereq) {
                        echo "  • {$prereq}\n";
                    }
                    echo "\n";
                }

                echo "【操作步骤】\n";
                $steps = $workflow['steps'] ?? [];

                foreach ($steps as $index => $step) {
                    $stepNumber = $index + 1;
                    $stepDescription = $step['description'] ?? '';
                    $stepActions = $step['actions'] ?? [];
                    $stepTips = $step['tips'] ?? [];

                    echo "\n步骤 {$stepNumber}: {$stepDescription}\n";

                    if (!empty($stepActions)) {
                        echo "  操作:\n";
                        foreach ($stepActions as $action) {
                            echo "    - {$action}\n";
                        }
                    }

                    if (!empty($stepTips)) {
                        echo "  提示:\n";
                        foreach ($stepTips as $tip) {
                            echo "    → {$tip}\n";
                        }
                    }

                    if ($interactive) {
                        echo "\n  按 Enter 继续...";
                        fgets(STDIN);
                    }
                }

                if (isset($workflow['expected_result'])) {
                    echo "\n【预期结果】\n";
                    echo $workflow['expected_result'] . "\n";
                }

                $this->completedWorkflows[] = $workflowName;
                echo "\n✓ 工作流程完成: {$workflowName}\n";

                if (isset($workflow['next_workflows'])) {
                    echo "\n推荐后续学习:\n";
                    foreach ($workflow['next_workflows'] as $next) {
                        echo "  • {$next}\n";
                    }
                }

                return true;
            }
        }

        echo "✗ 未找到工作流程: {$workflowName}\n";
        return false;
    }

    /**
     * 按难度筛选工作流程
     */
    public function listWorkflowsByDifficulty(string $difficulty): void
    {
        $this->logger->info("Listing workflows by difficulty: {$difficulty}");

        $workflows = $this->software->getWorkflows();
        $filtered = array_filter($workflows, function($wf) use ($difficulty) {
            return ($wf['difficulty'] ?? 'beginner') === $difficulty;
        });

        echo "\n=== {$difficulty} 级别工作流程 ===\n\n";

        if (empty($filtered)) {
            echo "没有找到 {$difficulty} 级别的工作流程。\n";
            return;
        }

        foreach ($filtered as $index => $workflow) {
            $name = $workflow['name'] ?? '未命名';
            $description = $workflow['description'] ?? '暂无描述';
            echo ($index + 1) . ". {$name}\n";
            echo "   {$description}\n\n";
        }
    }

    /**
     * 交互式工作流程学习模式
     */
    public function interactiveMode(): void
    {
        $this->logger->info("Starting interactive workflow learning mode");

        echo "\n=== 交互式工作流程学习模式 ===\n";
        echo "输入 'help' 查看可用命令\n";
        echo "输入 'exit' 退出学习模式\n\n";

        while (true) {
            echo "\n流程> ";
            $input = trim(fgets(STDIN));

            if ($input === 'exit') {
                echo "退出工作流程学习模式\n";
                break;
            }

            if ($input === 'help') {
                $this->showHelp();
                continue;
            }

            if ($input === 'list') {
                $this->showAvailableWorkflows();
                continue;
            }

            if ($input === 'progress') {
                $this->showProgress();
                continue;
            }

            if ($input === 'beginner' || $input === 'intermediate' || $input === 'advanced') {
                $this->listWorkflowsByDifficulty($input);
                continue;
            }

            if (!empty($input)) {
                $this->simulateWorkflow($input, true);
            }
        }
    }

    private function showHelp(): void
    {
        echo "\n可用命令:\n";
        echo "  list              - 显示所有工作流程\n";
        echo "  [流程名]          - 模拟执行指定的工作流程\n";
        echo "  beginner          - 显示初级工作流程\n";
        echo "  intermediate      - 显示中级工作流程\n";
        echo "  advanced          - 显示高级工作流程\n";
        echo "  progress          - 显示学习进度\n";
        echo "  help              - 显示帮助信息\n";
        echo "  exit              - 退出学习模式\n";
    }

    private function showProgress(): void
    {
        $total = count($this->software->getWorkflows());
        $completed = count($this->completedWorkflows);
        $percentage = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

        echo "\n=== 学习进度 ===\n";
        echo "已完成: {$completed}/{$total}\n";
        echo "完成度: {$percentage}%\n";

        if (!empty($this->completedWorkflows)) {
            echo "\n已完成的工作流程:\n";
            foreach ($this->completedWorkflows as $workflow) {
                echo "  ✓ {$workflow}\n";
            }
        }
    }

    /**
     * 获取已完成的工作流程
     */
    public function getCompletedWorkflows(): array
    {
        return $this->completedWorkflows;
    }
}