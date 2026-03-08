<?php

/**
 * 硬件软件配置示例
 * 用于模拟特定硬件的软件操作流程学习
 */

return [
    // ========== 基本信息 ==========
    'name' => '硬件控制软件',
    'version' => '2.0.0',
    'description' => '特定硬件设备的控制软件模拟',
    'type' => 'hardware_control',

    // ========== 界面元素配置 ==========
    'interface_elements' => [
        [
            'name' => '主菜单栏',
            'type' => 'menu_bar',
            'description' => '软件顶部的主菜单',
            'components' => ['文件', '编辑', '视图', '工具', '帮助'],
            'usage' => '点击菜单项执行相应操作',
            'shortcuts' => [
                'Alt + F' => '打开文件菜单',
            ],
            'simulated_elements' => [
                'file_menu' => [
                    'label' => '文件',
                    'actions' => ['新建', '打开', '保存', '退出'],
                    'hotkeys' => ['Ctrl+N', 'Ctrl+O', 'Ctrl+S', 'Alt+F4']
                ],
                'edit_menu' => [
                    'label' => '编辑',
                    'actions' => ['撤销', '重做', '复制', '粘贴'],
                    'hotkeys' => ['Ctrl+Z', 'Ctrl+Y', 'Ctrl+C', 'Ctrl+V']
                ],
            ]
        ],
        [
            'name' => '工具栏',
            'type' => 'toolbar',
            'description' => '常用操作快捷工具栏',
            'components' => ['新建', '打开', '保存', '设置', '帮助'],
            'usage' => '点击图标快速执行常用操作',
            'simulated_elements' => [
                'new_button' => [
                    'label' => '新建',
                    'icon' => '➕',
                    'action' => 'create_new_project',
                    'position' => ['x' => 10, 'y' => 10]
                ],
                'open_button' => [
                    'label' => '打开',
                    'icon' => '📂',
                    'action' => 'open_file',
                    'position' => ['x' => 60, 'y' => 10]
                ],
                'save_button' => [
                    'label' => '保存',
                    'icon' => '💾',
                    'action' => 'save_file',
                    'position' => ['x' => 110, 'y' => 10]
                ],
                'settings_button' => [
                    'label' => '设置',
                    'icon' => '⚙️',
                    'action' => 'open_settings',
                    'position' => ['x' => 160, 'y' => 10]
                ],
            ]
        ],
        [
            'name' => '状态栏',
            'type' => 'status_bar',
            'description' => '底部状态显示栏',
            'components' => ['连接状态', '设备状态', '时间显示'],
            'simulated_elements' => [
                'connection_status' => [
                    'label' => '连接状态',
                    'default_value' => '未连接',
                    'states' => ['已连接', '未连接', '连接中', '断开']
                ],
                'device_status' => [
                    'label' => '设备状态',
                    'default_value' => '就绪',
                    'states' => ['就绪', '运行中', '错误', '维护中']
                ]
            ]
        ],
        [
            'name' => '主工作区',
            'type' => 'workspace',
            'description' => '主要的操作区域',
            'components' => ['控制面板', '数据显示区', '参数设置区'],
            'simulated_elements' => [
                'control_panel' => [
                    'label' => '控制面板',
                    'buttons' => [
                        ['label' => '启动', 'action' => 'start_device', 'hotkey' => 'F5'],
                        ['label' => '停止', 'action' => 'stop_device', 'hotkey' => 'F6'],
                        ['label' => '重置', 'action' => 'reset_device', 'hotkey' => 'F7']
                    ]
                ],
                'data_display' => [
                    'label' => '数据显示',
                    'fields' => [
                        ['label' => '温度', 'unit' => '°C', 'id' => 'temp_display'],
                        ['label' => '压力', 'unit' => 'Pa', 'id' => 'pressure_display'],
                        ['label' => '转速', 'unit' => 'RPM', 'id' => 'speed_display']
                    ]
                ]
            ]
        ],
    ],

    // ========== 基础操作配置 ==========
    'operations' => [
        [
            'name' => '新建项目',
            'category' => 'file',
            'description' => '创建一个新的控制项目',
            'shortcut' => 'Ctrl + N',
            'menu_path' => '文件 > 新建',
            'steps' => [
                '点击文件菜单',
                '选择新建命令',
                '在弹出的对话框中输入项目名称',
                '点击确定按钮'
            ],
            'tips' => [
                '项目名称不能包含特殊字符',
                '建议使用有意义的名称便于管理'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'new_button',
                'feedback' => '新项目创建成功'
            ]
        ],
        [
            'name' => '打开文件',
            'category' => 'file',
            'description' => '打开已保存的项目文件',
            'shortcut' => 'Ctrl + O',
            'menu_path' => '文件 > 打开',
            'steps' => [
                '点击文件菜单',
                '选择打开命令',
                '在文件浏览器中选择文件',
                '点击打开按钮'
            ],
            'tips' => [
                '支持的项目文件格式: .hwproj',
                '可以同时打开多个项目'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'open_button',
                'feedback' => '文件打开成功'
            ]
        ],
        [
            'name' => '启动设备',
            'category' => 'device',
            'description' => '启动硬件设备运行',
            'shortcut' => 'F5',
            'menu_path' => '控制 > 启动',
            'steps' => [
                '确认设备连接正常',
                '点击启动按钮或按F5键',
                '观察状态栏确认设备状态',
                '检查数据显示区确认运行参数'
            ],
            'tips' => [
                '启动前请确保所有参数设置正确',
                '如果连接失败，请检查硬件连接'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'start_device',
                'feedback' => '设备已启动',
                'state_change' => [
                    'device_status' => '运行中',
                    'connection_status' => '已连接'
                ]
            ]
        ],
        [
            'name' => '停止设备',
            'category' => 'device',
            'description' => '停止正在运行的设备',
            'shortcut' => 'F6',
            'menu_path' => '控制 > 停止',
            'steps' => [
                '确认当前没有正在执行的任务',
                '点击停止按钮或按F6键',
                '等待设备完全停止',
                '观察状态栏确认停止状态'
            ],
            'tips' => [
                '强制停止可能导致数据丢失',
                '建议先完成当前任务再停止'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'stop_device',
                'feedback' => '设备已停止',
                'state_change' => [
                    'device_status' => '就绪'
                ]
            ]
        ],
        [
            'name' => '调整参数',
            'category' => 'settings',
            'description' => '调整设备运行参数',
            'shortcut' => 'Ctrl + P',
            'menu_path' => '工具 > 参数设置',
            'steps' => [
                '打开参数设置对话框',
                '选择要调整的参数类型',
                '输入新的参数值',
                '点击应用或确定保存设置'
            ],
            'tips' => [
                '参数修改会在下次启动时生效',
                '超出范围的参数将被自动纠正'
            ],
            'simulated_action' => [
                'type' => 'open_dialog',
                'target' => 'parameter_dialog',
                'feedback' => '参数设置已保存'
            ]
        ],
    ],

    // ========== 工作流程配置 ==========
    'workflows' => [
        [
            'name' => '设备启动流程',
            'description' => '学习如何正确启动硬件设备',
            'difficulty' => 'beginner',
            'estimated_time' => '5-10 分钟',
            'prerequisites' => [
                '了解硬件连接方式',
                '熟悉软件基本操作'
            ],
            'steps' => [
                [
                    'description' => '打开软件并新建项目',
                    'actions' => [
                        '双击软件图标启动程序',
                        '点击工具栏的"新建"按钮',
                        '在弹出的对话框中输入项目名称',
                        '点击确定创建项目'
                    ],
                    'tips' => [
                        '首次启动可能需要加载驱动程序',
                        '确保硬件设备已正确连接'
                    ],
                    'simulated_element' => 'new_button'
                ],
                [
                    'description' => '检查设备连接状态',
                    'actions' => [
                        '查看状态栏的连接状态',
                        '确认显示"已连接"',
                        '如果显示"未连接"，检查硬件连接'
                    ],
                    'tips' => [
                        'USB连接线要插紧',
                        '设备电源要打开'
                    ],
                    'simulated_element' => 'connection_status'
                ],
                [
                    'description' => '设置运行参数',
                    'actions' => [
                        '点击"设置"按钮打开参数设置',
                        '调整温度、压力等参数',
                        '点击应用保存设置'
                    ],
                    'tips' => [
                        '参考设备手册设置合适的参数',
                        '首次使用建议使用默认参数'
                    ],
                    'simulated_element' => 'settings_button'
                ],
                [
                    'description' => '启动设备',
                    'actions' => [
                        '点击控制面板的"启动"按钮',
                        '或按F5快捷键',
                        '观察状态栏确认设备状态',
                        '查看数据显示区的实时数据'
                    ],
                    'tips' => [
                        '启动后设备会进入预热阶段',
                        '预热完成后状态会变为"运行中"'
                    ],
                    'simulated_element' => 'start_device'
                ],
            ],
            'expected_result' => '设备成功启动，状态栏显示"运行中"，数据显示区显示实时数据',
            'next_workflows' => [
                '设备停止流程',
                '参数调整流程',
                '数据导出流程'
            ]
        ],
        [
            'name' => '设备停止流程',
            'description' => '学习如何安全停止正在运行的设备',
            'difficulty' => 'beginner',
            'estimated_time' => '3-5 分钟',
            'prerequisites' => [
                '完成设备启动流程',
                '了解设备停止注意事项'
            ],
            'steps' => [
                [
                    'description' => '完成当前任务',
                    'actions' => [
                        '确认所有操作已完成',
                        '检查是否有未保存的数据',
                        '如有需要，先保存数据'
                    ],
                    'tips' => [
                        '突然停止可能导致任务中断',
                        '建议等待任务完成后再停止'
                    ],
                    'simulated_element' => 'save_button'
                ],
                [
                    'description' => '停止设备',
                    'actions' => [
                        '点击控制面板的"停止"按钮',
                        '或按F6快捷键',
                        '等待设备完全停止',
                        '观察状态栏确认停止状态'
                    ],
                    'tips' => [
                        '停止过程可能需要几秒钟',
                        '不要强制关闭程序'
                    ],
                    'simulated_element' => 'stop_device'
                ],
                [
                    'description' => '保存项目',
                    'actions' => [
                        '点击"保存"按钮',
                        '或按Ctrl+S快捷键',
                        '确认保存位置和文件名',
                        '点击确定保存'
                    ],
                    'tips' => [
                        '定期保存可以防止数据丢失',
                        '建议使用有意义的文件名'
                    ],
                    'simulated_element' => 'save_button'
                ],
            ],
            'expected_result' => '设备安全停止，项目已保存，可以关闭软件',
            'next_workflows' => [
                '数据导出流程',
                '日志查看流程'
            ]
        ],
        [
            'name' => '参数调整流程',
            'description' => '学习如何调整设备运行参数',
            'difficulty' => 'intermediate',
            'estimated_time' => '10-15 分钟',
            'prerequisites' => [
                '完成设备启动流程',
                '了解参数的作用和范围'
            ],
            'steps' => [
                [
                    'description' => '打开参数设置',
                    'actions' => [
                        '点击工具栏的"设置"按钮',
                        '或从菜单选择"工具 > 参数设置"',
                        '打开参数设置对话框'
                    ],
                    'tips' => [
                        '参数设置可以在设备运行时进行',
                        '部分参数需要设备停止后才能修改'
                    ],
                    'simulated_element' => 'settings_button'
                ],
                [
                    'description' => '调整温度参数',
                    'actions' => [
                        '找到温度设置选项',
                        '输入目标温度值',
                        '点击应用或确定'
                    ],
                    'tips' => [
                        '温度范围: 0-200°C',
                        '超出范围的值会被自动纠正'
                    ],
                    'simulated_element' => 'temperature_input'
                ],
                [
                    'description' => '调整压力参数',
                    'actions' => [
                        '找到压力设置选项',
                        '输入目标压力值',
                        '点击应用或确定'
                    ],
                    'tips' => [
                        '压力范围: 0-1000 Pa',
                        '注意压力单位是Pa（帕斯卡）'
                    ],
                    'simulated_element' => 'pressure_input'
                ],
                [
                    'description' => '调整转速参数',
                    'actions' => [
                        '找到转速设置选项',
                        '输入目标转速值',
                        '点击应用或确定'
                    ],
                    'tips' => [
                        '转速范围: 0-5000 RPM',
                        '高转速可能增加噪音和磨损'
                    ],
                    'simulated_element' => 'speed_input'
                ],
                [
                    'description' => '保存并应用设置',
                    'actions' => [
                        '检查所有参数是否正确',
                        '点击应用按钮',
                        '确认参数已生效'
                    ],
                    'tips' => [
                        '部分参数需要重启设备才能生效',
                        '建议先保存项目再修改参数'
                    ],
                    'simulated_element' => 'apply_button'
                ],
            ],
            'expected_result' => '所有参数已成功调整并应用，设备按照新参数运行',
            'next_workflows' => [
                '数据监控流程',
                '故障排查流程'
            ]
        ],
    ],

    // ========== 界面模拟配置 ==========
    'interface_simulation' => [
        'layout' => [
            'header' => [
                'height' => 60,
                'background' => '#1a1a2e',
                'elements' => ['menu_bar', 'toolbar']
            ],
            'main' => [
                'flex' => 1,
                'display' => 'flex',
                'background' => '#2d2d44'
            ],
            'sidebar' => [
                'width' => 250,
                'background' => '#16213e',
                'elements' => ['control_panel']
            ],
            'workspace' => [
                'flex' => 1,
                'background' => '#0f0f1a',
                'elements' => ['data_display', 'status_display']
            ],
            'footer' => [
                'height' => 40,
                'background' => '#1a1a2e',
                'elements' => ['status_bar']
            ]
        ],
        'buttons' => [
            'start_device' => [
                'label' => '启动',
                'color' => '#28a745',
                'hover_color' => '#218838',
                'disabled_color' => '#6c757d'
            ],
            'stop_device' => [
                'label' => '停止',
                'color' => '#dc3545',
                'hover_color' => '#c82333',
                'disabled_color' => '#6c757d'
            ],
            'reset_device' => [
                'label' => '重置',
                'color' => '#ffc107',
                'hover_color' => '#e0a800',
                'disabled_color' => '#6c757d'
            ]
        ],
        'displays' => [
            'temp_display' => [
                'label' => '温度',
                'unit' => '°C',
                'min_value' => 0,
                'max_value' => 200,
                'default_value' => 25
            ],
            'pressure_display' => [
                'label' => '压力',
                'unit' => 'Pa',
                'min_value' => 0,
                'max_value' => 1000,
                'default_value' => 0
            ],
            'speed_display' => [
                'label' => '转速',
                'unit' => 'RPM',
                'min_value' => 0,
                'max_value' => 5000,
                'default_value' => 0
            ]
        ]
    ],
];