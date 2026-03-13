<?php

/**
 * 复杂软件配置模板
 * 演示具有复杂界面的软件（类似 Photoshop/Figma 等专业工具）
 */

return [
    // ========== 基本信息 ==========
    'name' => '专业设计软件',
    'version' => '3.0.0',
    'description' => '具有复杂界面的专业设计软件示例',
    'type' => 'professional_tool',

    // ========== 界面元素配置 ==========
    'interface_elements' => [
        // 顶部菜单栏
        [
            'name' => '顶部菜单栏',
            'type' => 'menu_bar',
            'description' => '软件顶部的主菜单区域',
            'components' => ['文件', '编辑', '视图', '插入', '格式', '工具', '窗口', '帮助'],
            'simulated_elements' => [
                'file_menu' => [
                    'label' => '文件',
                    'submenu' => ['新建', '打开', '保存', '另存为', '导出', '打印', '退出'],
                    'hotkeys' => ['Ctrl+N', 'Ctrl+O', 'Ctrl+S', 'Ctrl+Shift+S', 'Ctrl+E', 'Ctrl+P', 'Ctrl+Q']
                ],
                'edit_menu' => [
                    'label' => '编辑',
                    'submenu' => ['撤销', '重做', '剪切', '复制', '粘贴', '删除', '全选'],
                    'hotkeys' => ['Ctrl+Z', 'Ctrl+Y', 'Ctrl+X', 'Ctrl+C', 'Ctrl+V', 'Delete', 'Ctrl+A']
                ],
                'view_menu' => [
                    'label' => '视图',
                    'submenu' => ['放大', '缩小', '适应窗口', '实际大小', '标尺', '网格', '参考线'],
                    'hotkeys' => ['Ctrl++', 'Ctrl+-', 'Ctrl+0', 'Ctrl+1', 'Ctrl+R', 'Ctrl+G\'', 'Ctrl+;']
                ],
                'insert_menu' => [
                    'label' => '插入',
                    'submenu' => ['图片', '形状', '文本', '表格', '图表', '链接']
                ],
            ]
        ],

        // 顶部工具栏
        [
            'name' => '顶部工具栏',
            'type' => 'main_toolbar',
            'description' => '常用操作快捷工具栏',
            'components' => ['新建', '打开', '保存', '撤销', '重做', '剪切', '复制', '粘贴'],
            'simulated_elements' => [
                'new_btn' => [
                    'label' => '新建',
                    'icon' => '📄',
                    'tooltip' => '创建新项目 (Ctrl+N)',
                    'action' => 'create_new',
                    'position' => ['x' => 10, 'y' => 10]
                ],
                'open_btn' => [
                    'label' => '打开',
                    'icon' => '📂',
                    'tooltip' => '打开文件 (Ctrl+O)',
                    'action' => 'open_file',
                    'position' => ['x' => 60, 'y' => 10]
                ],
                'save_btn' => [
                    'label' => '保存',
                    'icon' => '💾',
                    'tooltip' => '保存 (Ctrl+S)',
                    'action' => 'save_file',
                    'position' => ['x' => 110, 'y' => 10]
                ],
                'separator_1' => [
                    'type' => 'separator',
                    'position' => ['x' => 150, 'y' => 10]
                ],
                'undo_btn' => [
                    'label' => '撤销',
                    'icon' => '↩️',
                    'tooltip' => '撤销 (Ctrl+Z)',
                    'action' => 'undo',
                    'position' => ['x' => 160, 'y' => 10]
                ],
                'redo_btn' => [
                    'label' => '重做',
                    'icon' => '↪️',
                    'tooltip' => '重做 (Ctrl+Y)',
                    'action' => 'redo',
                    'position' => ['x' => 210, 'y' => 10]
                ],
            ]
        ],

        // 左侧工具面板
        [
            'name' => '左侧工具面板',
            'type' => 'left_panel',
            'description' => '绘图和编辑工具面板',
            'components' => ['选择工具', '移动工具', '画笔工具', '橡皮擦', '文字工具', '形状工具', '填充工具', '渐变工具'],
            'simulated_elements' => [
                'select_tool' => [
                    'label' => '选择',
                    'icon' => '🖱️',
                    'tooltip' => '选择工具 (V)',
                    'action' => 'select',
                    'position' => ['x' => 10, 'y' => 10]
                ],
                'move_tool' => [
                    'label' => '移动',
                    'icon' => '✋',
                    'tooltip' => '移动工具 (M)',
                    'action' => 'move',
                    'position' => ['x' => 10, 'y' => 60]
                ],
                'brush_tool' => [
                    'label' => '画笔',
                    'icon' => '🖌️',
                    'tooltip' => '画笔工具 (B)',
                    'action' => 'brush',
                    'position' => ['x' => 10, 'y' => 110]
                ],
                'eraser_tool' => [
                    'label' => '橡皮擦',
                    'icon' => '🧽',
                    'tooltip' => '橡皮擦 (E)',
                    'action' => 'eraser',
                    'position' => ['x' => 10, 'y' => 160]
                ],
                'text_tool' => [
                    'label' => '文字',
                    'icon' => '📝',
                    'tooltip' => '文字工具 (T)',
                    'action' => 'text',
                    'position' => ['x' => 10, 'y' => 210]
                ],
                'shape_tool' => [
                    'label' => '形状',
                    'icon' => '⬛',
                    'tooltip' => '形状工具 (U)',
                    'action' => 'shape',
                    'position' => ['x' => 10, 'y' => 260]
                ],
                'fill_tool' => [
                    'label' => '填充',
                    'icon' => '🪣',
                    'tooltip' => '填充工具 (G)',
                    'action' => 'fill',
                    'position' => ['x' => 10, 'y' => 310]
                ],
                'gradient_tool' => [
                    'label' => '渐变',
                    'icon' => '🌈',
                    'tooltip' => '渐变工具',
                    'action' => 'gradient',
                    'position' => ['x' => 10, 'y' => 360]
                ],
            ]
        ],

        // 右侧属性面板
        [
            'name' => '右侧属性面板',
            'type' => 'right_panel',
            'description' => '对象属性和图层管理面板',
            'components' => ['属性设置', '图层管理', '样式设置', '历史记录'],
            'simulated_elements' => [
                'properties_section' => [
                    'label' => '属性',
                    'fields' => [
                        ['label' => '宽度', 'type' => 'number', 'unit' => 'px', 'default' => 100],
                        ['label' => '高度', 'type' => 'number', 'unit' => 'px', 'default' => 100],
                        ['label' => 'X坐标', 'type' => 'number', 'unit' => 'px', 'default' => 0],
                        ['label' => 'Y坐标', 'type' => 'number', 'unit' => 'px', 'default' => 0],
                        ['label' => '旋转', 'type' => 'number', 'unit' => '°', 'default' => 0],
                        ['label' => '不透明度', 'type' => 'slider', 'min' => 0, 'max' => 100, 'default' => 100]
                    ]
                ],
                'layers_section' => [
                    'label' => '图层',
                    'actions' => ['新建图层', '删除图层', '复制图层', '合并图层'],
                    'buttons' => [
                        ['label' => '＋', 'tooltip' => '新建图层', 'action' => 'new_layer'],
                        ['label' => '－', 'tooltip' => '删除图层', 'action' => 'delete_layer'],
                        ['label' => '复制', 'tooltip' => '复制图层', 'action' => 'duplicate_layer']
                    ]
                ],
                'styles_section' => [
                    'label' => '样式',
                    'fields' => [
                        ['label' => '填充颜色', 'type' => 'color', 'default' => '#000000'],
                        ['label' => '边框颜色', 'type' => 'color', 'default' => '#000000'],
                        ['label' => '边框宽度', 'type' => 'number', 'unit' => 'px', 'default' => 0],
                        ['label' => '阴影', 'type' => 'toggle', 'default' => false]
                    ]
                ],
                'history_section' => [
                    'label' => '历史',
                    'actions' => ['清空历史', '导出历史']
                ]
            ]
        ],

        // 底部状态栏
        [
            'name' => '底部状态栏',
            'type' => 'status_bar',
            'description' => '显示软件状态和信息的底部栏',
            'components' => ['缩放级别', '文档信息', '工具状态', '提示信息'],
            'simulated_elements' => [
                'zoom_level' => [
                    'label' => '缩放',
                    'default_value' => '100%',
                    'actions' => ['放大', '缩小', '适应']
                ],
                'document_info' => [
                    'label' => '文档',
                    'fields' => ['尺寸', '颜色模式', '分辨率']
                ],
                'tool_status' => [
                    'label' => '工具',
                    'default_value' => '选择工具'
                ],
                'hint_text' => [
                    'label' => '提示',
                    'default_value' => '准备就绪'
                ]
            ]
        ],

        // 中央画布区域
        [
            'name' => '中央画布区域',
            'type' => 'canvas',
            'description' => '主要的创作和编辑区域',
            'components' => ['画布', '标尺', '参考线', '对象'],
            'simulated_elements' => [
                'main_canvas' => [
                    'label' => '画布',
                    'default_width' => 1920,
                    'default_height' => 1080,
                    'background' => '#ffffff',
                    'supports' => ['layers', 'objects', 'guides', 'grids']
                ],
                'horizontal_ruler' => [
                    'label' => '水平标尺',
                    'position' => 'top',
                    'unit' => 'pixels'
                ],
                'vertical_ruler' => [
                    'label' => '垂直标尺',
                    'position' => 'left',
                    'unit' => 'pixels'
                ]
            ]
        ],
    ],

    // ========== 基础操作配置 ==========
    'operations' => [
        [
            'name' => '新建项目',
            'category' => 'file',
            'description' => '创建一个新的设计项目',
            'shortcut' => 'Ctrl + N',
            'menu_path' => '文件 > 新建',
            'steps' => [
                '点击文件菜单',
                '选择新建命令',
                '在弹出的对话框中设置画布尺寸',
                '选择颜色模式和分辨率',
                '点击确定创建项目'
            ],
            'tips' => [
                '常用尺寸: 1920x1080 (桌面), 1080x1920 (移动端)',
                '颜色模式: RGB 用于屏幕显示, CMYK 用于印刷',
                '分辨率: 72dpi 适合屏幕, 300dpi 适合印刷'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'new_btn',
                'dialog' => 'new_project_dialog',
                'feedback' => '新项目已创建'
            ]
        ],
        [
            'name' => '打开文件',
            'category' => 'file',
            'description' => '打开已有的设计文件',
            'shortcut' => 'Ctrl + O',
            'menu_path' => '文件 > 打开',
            'steps' => [
                '点击文件菜单',
                '选择打开命令',
                '在文件浏览器中选择文件',
                '点击打开按钮'
            ],
            'tips' => [
                '支持的格式: PSD, AI, SKETCH, PDF, JPG, PNG 等',
                '可以同时打开多个文件'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'open_btn',
                'dialog' => 'open_file_dialog',
                'feedback' => '文件已打开'
            ]
        ],
        [
            'name' => '保存项目',
            'category' => 'file',
            'description' => '保存当前项目',
            'shortcut' => 'Ctrl + S',
            'menu_path' => '文件 > 保存',
            'steps' => [
                '点击文件菜单',
                '选择保存命令',
                '首次保存时选择保存位置和格式',
                '输入文件名',
                '点击保存'
            ],
            'tips' => [
                '定期保存以防数据丢失',
                '使用「另存为」可以创建备份'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'save_btn',
                'feedback' => '项目已保存'
            ]
        ],
        [
            'name' => '使用选择工具',
            'category' => 'tools',
            'description' => '选择和移动画布上的对象',
            'shortcut' => 'V',
            'menu_path' => '工具 > 选择',
            'steps' => [
                '点击左侧工具面板的选择工具图标',
                '或按 V 键',
                '点击画布上的对象选中它',
                '拖动对象可以移动位置',
                '按住 Shift 键可以多选'
            ],
            'tips' => [
                '选中对象后可以在右侧面板修改属性',
                '双击对象可以进入编辑模式'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'select_tool',
                'feedback' => '已选择选择工具',
                'cursor' => 'move'
            ]
        ],
        [
            'name' => '使用画笔工具',
            'category' => 'tools',
            'description' => '在画布上绘制自由路径',
            'shortcut' => 'B',
            'menu_path' => '工具 > 画笔',
            'steps' => [
                '点击左侧工具面板的画笔工具图标',
                '或按 B 键',
                '在右侧面板设置画笔大小和颜色',
                '在画布上拖动鼠标绘制',
                '松开鼠标完成绘制'
            ],
            'tips' => [
                '按住 Shift 键可以绘制直线',
                '双击画笔工具可以快速设置属性'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'brush_tool',
                'feedback' => '已选择画笔工具',
                'cursor' => 'crosshair'
            ]
        ],
        [
            'name' => '添加文本',
            'category' => 'tools',
            'description' => '在画布上添加文字',
            'shortcut' => 'T',
            'menu_path' => '工具 > 文字',
            'steps' => [
                '点击左侧工具面板的文字工具图标',
                '或按 T 键',
                '在画布上点击创建文本框',
                '输入文字内容',
                '在右侧面板设置字体和大小'
            ],
            'tips' => [
                '双击文本可以编辑内容',
                '可以使用系统字体或导入自定义字体'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'text_tool',
                'feedback' => '已选择文字工具',
                'cursor' => 'text'
            ]
        ],
        [
            'name' => '创建形状',
            'category' => 'tools',
            'description' => '创建基本几何形状',
            'shortcut' => 'U',
            'menu_path' => '工具 > 形状',
            'steps' => [
                '点击左侧工具面板的形状工具图标',
                '或按 U 键',
                '在工具选项栏选择形状类型',
                '在画布上拖动创建形状',
                '在右侧面板调整形状属性'
            ],
            'tips' => [
                '按住 Shift 键可以创建正方形或圆形',
                '可以组合多个形状创建复杂图形'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'shape_tool',
                'feedback' => '已选择形状工具',
                'cursor' => 'crosshair'
            ]
        ],
        [
            'name' => '调整对象属性',
            'category' => 'properties',
            'description' => '修改选中对象的位置、大小和样式',
            'shortcut' => 'Ctrl + P',
            'menu_path' => '窗口 > 属性',
            'steps' => [
                '使用选择工具选中对象',
                '在右侧属性面板中修改数值',
                '可以直接输入数值或拖动滑块',
                '修改后画布实时更新'
            ],
            'tips' => [
                '按 Enter 键确认修改',
                '按 Esc 键取消修改'
            ],
            'simulated_action' => [
                'type' => 'modify_property',
                'target' => 'properties_section',
                'feedback' => '属性已更新'
            ]
        ],
        [
            'name' => '新建图层',
            'category' => 'layers',
            'description' => '创建一个新的图层',
            'shortcut' => 'Ctrl + Shift + N',
            'menu_path' => '图层 > 新建图层',
            'steps' => [
                '在右侧图层面板点击新建图层按钮',
                '或使用快捷键',
                '新图层出现在图层列表顶部',
                '双击图层名称可以重命名'
            ],
            'tips' => [
                '使用图层可以组织和管理对象',
                '可以调整图层的顺序和可见性'
            ],
            'simulated_action' => [
                'type' => 'click_button',
                'target' => 'new_layer',
                'feedback' => '新图层已创建'
            ]
        ],
        [
            'name' => '缩放画布',
            'category' => 'view',
            'description' => '调整画布的显示比例',
            'shortcut' => 'Ctrl + +/-',
            'menu_path' => '视图 > 缩放',
            'steps' => [
                '使用快捷键 Ctrl + (+) 放大',
                '使用快捷键 Ctrl + (-) 缩小',
                '或使用鼠标滚轮缩放',
                '或点击状态栏的缩放级别'
            ],
            'tips' => [
                'Ctrl + 0 可以适应窗口',
                'Ctrl + 1 可以恢复 100%'
            ],
            'simulated_action' => [
                'type' => 'zoom',
                'target' => 'main_canvas',
                'feedback' => '画布已缩放'
            ]
        ],
    ],

    // ========== 工作流程配置 ==========
    'workflows' => [
        [
            'name' => '从零创建设计',
            'description' => '学习如何从零开始创建一个完整的设计项目',
            'difficulty' => 'beginner',
            'estimated_time' => '15-20 分钟',
            'prerequisites' => [
                '了解基本界面布局',
                '熟悉鼠标操作'
            ],
            'steps' => [
                [
                    'description' => '创建新项目',
                    'actions' => [
                        '点击顶部工具栏的"新建"按钮',
                        '在弹出的对话框中设置画布尺寸为 1920x1080',
                        '选择 RGB 颜色模式',
                        '设置分辨率为 72dpi',
                        '点击确定创建项目'
                    ],
                    'tips' => [
                        '1920x1080 是桌面显示器的标准分辨率',
                        'RGB 颜色模式适合屏幕显示'
                    ],
                    'simulated_element' => 'new_btn'
                ],
                [
                    'description' => '添加背景色',
                    'actions' => [
                        '选择填充工具',
                        '在右侧面板选择背景颜色',
                        '点击画布填充整个区域'
                    ],
                    'tips' => [
                        '浅色背景通常更适合阅读',
                        '可以稍后根据需要修改'
                    ],
                    'simulated_element' => 'fill_tool'
                ],
                [
                    'description' => '添加标题文字',
                    'actions' => [
                        '选择文字工具',
                        '在画布顶部中央点击',
                        '输入标题文字',
                        '在右侧属性面板设置字体大小为 48px',
                        '调整文字颜色和位置'
                    ],
                    'tips' => [
                        '标题应该醒目且易读',
                        '使用无衬线字体更现代'
                    ],
                    'simulated_element' => 'text_tool'
                ],
                [
                    'description' => '添加装饰元素',
                    'actions' => [
                        '新建图层',
                        '选择形状工具',
                        '在画布上绘制几何形状',
                        '在右侧面板调整形状颜色和透明度'
                    ],
                    'tips' => [
                        '使用不同图层管理不同元素',
                        '可以调整图层顺序来改变显示效果'
                    ],
                    'simulated_element' => 'shape_tool'
                ],
                [
                    'description' => '保存项目',
                    'actions' => [
                        '点击顶部工具栏的"保存"按钮',
                        '选择保存位置',
                        '输入文件名',
                        '点击保存'
                    ],
                    'tips' => [
                        '建议使用有意义的文件名',
                        '定期保存以防数据丢失'
                    ],
                    'simulated_element' => 'save_btn'
                ],
            ],
            'expected_result' => '成功创建并保存了一个包含标题和装饰元素的设计项目',
            'next_workflows' => [
                '编辑现有设计',
                '使用图层管理',
                '导出设计'
            ]
        ],
        [
            'name' => '编辑现有设计',
            'description' => '学习如何打开和编辑已有的设计文件',
            'difficulty' => 'beginner',
            'estimated_time' => '10-15 分钟',
            'prerequisites' => [
                '完成从零创建设计流程',
                '了解基本编辑操作'
            ],
            'steps' => [
                [
                    'description' => '打开设计文件',
                    'actions' => [
                        '点击顶部工具栏的"打开"按钮',
                        '在文件浏览器中选择要编辑的文件',
                        '点击打开'
                    ],
                    'tips' => [
                        '可以同时打开多个文件',
                        '最近打开的文件会显示在文件菜单中'
                    ],
                    'simulated_element' => 'open_btn'
                ],
                [
                    'description' => '选择要编辑的对象',
                    'actions' => [
                        '选择选择工具',
                        '点击画布上的对象',
                        '选中的对象周围会出现选择框'
                    ],
                    'tips' => [
                        '按住 Shift 键可以多选',
                        '按 Ctrl + A 可以全选'
                    ],
                    'simulated_element' => 'select_tool'
                ],
                [
                    'description' => '修改对象属性',
                    'actions' => [
                        '在右侧属性面板中修改对象的位置',
                        '调整对象的大小',
                        '修改对象的颜色',
                        '调整对象的透明度'
                    ],
                    'tips' => [
                        '可以直接输入数值精确控制',
                        '也可以拖动滑块快速调整'
                    ],
                    'simulated_element' => 'properties_section'
                ],
                [
                    'description' => '添加新元素',
                    'actions' => [
                        '新建图层',
                        '选择合适的工具',
                        '在画布上添加新元素',
                        '调整新元素的位置和样式'
                    ],
                    'tips' => [
                        '使用图层可以更好地组织内容',
                        '记得保存修改'
                    ],
                    'simulated_element' => 'new_layer'
                ],
                [
                    'description' => '保存修改',
                    'actions' => [
                        '点击保存按钮',
                        '或使用快捷键 Ctrl + S',
                        '确认保存成功'
                    ],
                    'tips' => [
                        '如果需要保留原文件，使用"另存为"',
                        '可以使用版本控制管理不同版本'
                    ],
                    'simulated_element' => 'save_btn'
                ],
            ],
            'expected_result' => '成功打开并编辑了设计文件，所有修改已保存',
            'next_workflows' => [
                '使用图层管理',
                '高级编辑技巧'
            ]
        ],
        [
            'name' => '使用图层管理',
            'description' => '学习如何使用图层功能组织和管理设计元素',
            'difficulty' => 'intermediate',
            'estimated_time' => '15-20 分钟',
            'prerequisites' => [
                '完成从零创建设计流程',
                '熟悉基本编辑操作'
            ],
            'steps' => [
                [
                    'description' => '创建多个图层',
                    'actions' => [
                        '新建第一个图层',
                        '添加背景元素',
                        '新建第二个图层',
                        '添加主要内容',
                        '新建第三个图层',
                        '添加装饰元素'
                    ],
                    'tips' => [
                        '每个图层应该包含相关的内容',
                        '图层名称应该清晰描述内容'
                    ],
                    'simulated_element' => 'new_layer'
                ],
                [
                    'description' => '调整图层顺序',
                    'actions' => [
                        '在图层面板中选择图层',
                        '拖动图层到新的位置',
                        '观察画布上的变化',
                        '找到合适的顺序'
                    ],
                    'tips' => [
                        '上层的图层会覆盖下层的内容',
                        '可以使用透明度创建叠加效果'
                    ],
                    'simulated_element' => 'layers_section'
                ],
                [
                    'description' => '控制图层可见性',
                    'actions' => [
                        '点击图层旁边的眼睛图标',
                        '隐藏不需要的图层',
                        '再次点击恢复显示',
                        '查看不同图层的组合效果'
                    ],
                    'tips' => [
                        '隐藏图层可以查看底层的细节',
                        '不会删除图层内容'
                    ],
                    'simulated_element' => 'layers_section'
                ],
                [
                    'description' => '重命名图层',
                    'actions' => [
                        '双击图层名称',
                        '输入新的名称',
                        '按 Enter 确认',
                        '为所有图层设置清晰的名称'
                    ],
                    'tips' => [
                        '使用有意义的名称便于识别',
                        '可以使用前缀分组，如 BG-背景, FG-前景'
                    ],
                    'simulated_element' => 'layers_section'
                ],
                [
                    'description' => '删除不需要的图层',
                    'actions' => [
                        '选择要删除的图层',
                        '点击图层面板的删除按钮',
                        '或按 Delete 键',
                        '确认删除'
                    ],
                    'tips' => [
                        '删除后无法恢复（除非使用撤销）',
                        '建议先隐藏确认不需要再删除'
                    ],
                    'simulated_element' => 'delete_layer'
                ],
                [
                    'description' => '合并图层',
                    'actions' => [
                        '选择要合并的多个图层',
                        '右键选择合并',
                        '或使用快捷键 Ctrl + E',
                        '确认合并结果'
                    ],
                    'tips' => [
                        '合并后的图层无法单独编辑',
                        '建议先备份再合并'
                    ],
                    'simulated_element' => 'layers_section'
                ],
            ],
            'expected_result' => '熟练使用图层功能组织和管理设计元素',
            'next_workflows' => [
                '高级编辑技巧',
                '导出设计'
            ]
        ],
        [
            'name' => '导出设计',
            'description' => '学习如何将设计导出为不同的格式',
            'difficulty' => 'intermediate',
            'estimated_time' => '10-15 分钟',
            'prerequisites' => [
                '完成至少一个设计项目',
                '了解不同的文件格式'
            ],
            'steps' => [
                [
                    'description' => '准备导出',
                    'actions' => [
                        '保存当前项目',
                        '确认所有图层可见性设置正确',
                        '检查画布尺寸和分辨率'
                    ],
                    'tips' => [
                        '导出前保存项目以便后续修改',
                        '可以隐藏不需要导出的图层'
                    ],
                    'simulated_element' => 'save_btn'
                ],
                [
                    'description' => '导出为 JPG',
                    'actions' => [
                        '点击文件菜单',
                        '选择导出',
                        '选择 JPG 格式',
                        '设置导出质量',
                        '点击导出'
                    ],
                    'tips' => [
                        'JPG 适合照片和复杂图像',
                        '不支持透明背景'
                    ],
                    'simulated_element' => 'file_menu'
                ],
                [
                    'description' => '导出为 PNG',
                    'actions' => [
                        '点击文件菜单',
                        '选择导出',
                        '选择 PNG 格式',
                        '保持透明背景（如果需要）',
                        '点击导出'
                    ],
                    'tips' => [
                        'PNG 支持透明背景',
                        '适合图标和界面元素'
                    ],
                    'simulated_element' => 'file_menu'
                ],
                [
                    'description' => '导出为 PDF',
                    'actions' => [
                        '点击文件菜单',
                        '选择导出',
                        '选择 PDF 格式',
                        '设置导出选项',
                        '点击导出'
                    ],
                    'tips' => [
                        'PDF 适合打印和文档',
                        '保持矢量质量'
                    ],
                    'simulated_element' => 'file_menu'
                ],
            ],
            'expected_result' => '成功将设计导出为多种格式',
            'next_workflows' => [
                '批量处理',
                '自动化工作流'
            ]
        ],
    ],

    // ========== 界面模拟配置 ==========
    'interface_simulation' => [
        'layout' => [
            'header' => [
                'height' => 80,
                'background' => '#2d2d2d',
                'elements' => ['menu_bar', 'main_toolbar']
            ],
            'main' => [
                'display' => 'flex',
                'flex_direction' => 'row',
                'background' => '#1e1e1e'
            ],
            'left_panel' => [
                'width' => 60,
                'background' => '#252526',
                'elements' => ['tools_panel']
            ],
            'center' => [
                'flex' => 1,
                'display' => 'flex',
                'flex_direction' => 'column',
                'background' => '#1e1e1e'
            ],
            'canvas_container' => [
                'flex' => 1,
                'background' => '#333333',
                'elements' => ['canvas', 'rulers']
            ],
            'right_panel' => [
                'width' => 280,
                'background' => '#252526',
                'elements' => ['properties', 'layers', 'styles', 'history']
            ],
            'footer' => [
                'height' => 30,
                'background' => '#007acc',
                'elements' => ['status_bar']
            ]
        ],
        'colors' => [
            'primary' => '#007acc',
            'secondary' => '#252526',
            'text' => '#cccccc',
            'text_dark' => '#666666',
            'border' => '#3e3e42',
            'highlight' => '#264f78',
            'hover' => '#2a2d2e'
        ],
        'fonts' => [
            'default' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            'monospace' => 'Consolas, "Courier New", monospace'
        ],
        'canvas_settings' => [
            'default_width' => 1920,
            'default_height' => 1080,
            'zoom_levels' => [10, 25, 50, 75, 100, 150, 200, 300, 400],
            'min_zoom' => 10,
            'max_zoom' => 400
        ]
    ],
];