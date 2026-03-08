<?php

namespace App;

/**
 * 软件信息模型类
 * 用于存储和管理目标软件的基本信息
 */
class SoftwareInfo
{
    private string $name;
    private string $version;
    private string $description;
    private string $type;
    private array $interfaceElements;
    private array $operations;
    private array $workflows;

    public function __construct(array $data = [])
    {
        $this->name = $data['name'] ?? '';
        $this->version = $data['version'] ?? '1.0.0';
        $this->description = $data['description'] ?? '';
        $this->type = $data['type'] ?? 'general';
        $this->interfaceElements = $data['interface_elements'] ?? [];
        $this->operations = $data['operations'] ?? [];
        $this->workflows = $data['workflows'] ?? [];
    }

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getInterfaceElements(): array
    {
        return $this->interfaceElements;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    public function getWorkflows(): array
    {
        return $this->workflows;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function addInterfaceElement(array $element): void
    {
        $this->interfaceElements[] = $element;
    }

    public function addOperation(array $operation): void
    {
        $this->operations[] = $operation;
    }

    public function addWorkflow(array $workflow): void
    {
        $this->workflows[] = $workflow;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'description' => $this->description,
            'type' => $this->type,
            'interface_elements' => $this->interfaceElements,
            'operations' => $this->operations,
            'workflows' => $this->workflows,
        ];
    }
}