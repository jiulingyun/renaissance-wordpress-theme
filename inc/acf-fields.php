<?php
/**
 * ACF 字段注册
 * 
 * 通过代码注册 ACF 字段，实现高度自定义的内容管理
 * 注意：虽然 ACF 免费版不提供 Repeater 字段的可视化界面，
 * 但通过代码注册的 Repeater 字段可以正常使用！
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 检查 ACF 是否已激活
if (!function_exists('acf_add_local_field_group')) {
    return;
}

/**
 * 注册 Cases 自定义文章类型字段
 * 使用固定数量字段代替 Repeater（ACF 免费版兼容）
 */
add_action('acf/init', 'rena_register_case_fields');
function rena_register_case_fields() {
    
    $fields = [
        [
            'key' => 'field_case_category',
            'label' => '案例分类',
            'name' => 'case_category',
            'type' => 'text',
            'placeholder' => 'Algorithmic Trading',
        ],
        [
            'key' => 'field_case_subtitle',
            'label' => '案例副标题',
            'name' => 'case_subtitle',
            'type' => 'textarea',
            'rows' => 2,
        ],
    ];
    
    // 创建 5 个性能指标字段（固定数量）
    for ($i = 1; $i <= 5; $i++) {
        $fields[] = [
            'key' => 'field_case_metric_' . $i,
            'label' => '性能指标 ' . $i,
            'name' => 'metric_' . $i,
            'type' => 'group',
            'instructions' => '留空则不显示此指标',
            'layout' => 'block',
            'sub_fields' => [
                [
                    'key' => 'field_metric_value_' . $i,
                    'label' => '指标数值',
                    'name' => 'value',
                    'type' => 'text',
                    'placeholder' => '847%',
                ],
                [
                    'key' => 'field_metric_label_' . $i,
                    'label' => '指标标签',
                    'name' => 'label',
                    'type' => 'text',
                    'placeholder' => 'Return on Investment',
                ],
            ],
        ];
    }
    
    $fields[] = [
        'key' => 'field_project_duration',
        'label' => '项目周期',
        'name' => 'project_duration',
        'type' => 'text',
        'placeholder' => '36 months',
    ];
    $fields[] = [
        'key' => 'field_project_team_size',
        'label' => '团队规模',
        'name' => 'project_team_size',
        'type' => 'text',
        'placeholder' => '12 specialists',
    ];
    $fields[] = [
        'key' => 'field_project_markets',
        'label' => '市场范围',
        'name' => 'project_markets',
        'type' => 'text',
        'placeholder' => 'Global Equities, FX, Futures',
    ];
    $fields[] = [
        'key' => 'field_project_technology',
        'label' => '使用技术',
        'name' => 'project_technology',
        'type' => 'text',
        'placeholder' => 'C++, FPGA, Machine Learning',
    ];
    
    // 创建 10 个关键特性字段（固定数量）
    for ($i = 1; $i <= 10; $i++) {
        $fields[] = [
            'key' => 'field_key_feature_' . $i,
            'label' => '关键特性 ' . $i,
            'name' => 'feature_' . $i,
            'type' => 'text',
            'instructions' => '留空则不显示此特性',
            'placeholder' => 'Sub-microsecond execution latency',
        ];
    }
    
    acf_add_local_field_group([
        'key' => 'group_case_details',
        'title' => '案例详情',
        'fields' => $fields,
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'case',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
    ]);
}

/**
 * 注册 Announcements 自定义文章类型字段
 * 使用固定数量字段代替 Repeater（ACF 免费版兼容）
 */
add_action('acf/init', 'rena_register_announcement_fields');
function rena_register_announcement_fields() {
    
    $fields = [
        [
            'key' => 'field_announcement_category',
            'label' => '公告分类',
            'name' => 'announcement_category',
            'type' => 'text',
            'placeholder' => 'System Update',
        ],
        [
            'key' => 'field_announcement_subtitle',
            'label' => '公告副标题',
            'name' => 'announcement_subtitle',
            'type' => 'textarea',
            'rows' => 2,
        ],
        [
            'key' => 'field_update_version',
            'label' => '更新版本号',
            'name' => 'update_version',
            'type' => 'text',
            'placeholder' => 'v4.1.0',
        ],
        [
            'key' => 'field_update_size',
            'label' => '更新大小',
            'name' => 'update_size',
            'type' => 'text',
            'placeholder' => '245 MB',
        ],
        [
            'key' => 'field_update_compatibility',
            'label' => '兼容性',
            'name' => 'update_compatibility',
            'type' => 'text',
            'placeholder' => 'All Systems',
        ],
        [
            'key' => 'field_update_deployment',
            'label' => '部署方式',
            'name' => 'update_deployment',
            'type' => 'text',
            'placeholder' => 'Automatic',
        ],
    ];
    
    // 创建 5 个性能指标字段（固定数量，可选）
    for ($i = 1; $i <= 5; $i++) {
        $fields[] = [
            'key' => 'field_announcement_metric_' . $i,
            'label' => '性能指标 ' . $i . ' （可选）',
            'name' => 'metric_' . $i,
            'type' => 'group',
            'instructions' => '留空则不显示此指标',
            'layout' => 'block',
            'sub_fields' => [
                [
                    'key' => 'field_ann_metric_value_' . $i,
                    'label' => '指标数值',
                    'name' => 'value',
                    'type' => 'text',
                    'placeholder' => '+23%',
                ],
                [
                    'key' => 'field_ann_metric_label_' . $i,
                    'label' => '指标标签',
                    'name' => 'label',
                    'type' => 'text',
                    'placeholder' => 'Prediction Accuracy',
                ],
            ],
        ];
    }
    
    acf_add_local_field_group([
        'key' => 'group_announcement_details',
        'title' => '公告详情',
        'fields' => $fields,
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'announcement',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
    ]);
}

/**
 * 注册 Videos 自定义文章类型字段
 */
add_action('acf/init', 'rena_register_video_fields');
function rena_register_video_fields() {
    
    acf_add_local_field_group([
        'key' => 'group_video_details',
        'title' => '视频详情',
        'fields' => [
            [
                'key' => 'field_video_category',
                'label' => '视频分类',
                'name' => 'video_category',
                'type' => 'text',
                'placeholder' => 'Tutorial',
            ],
            [
                'key' => 'field_video_subtitle',
                'label' => '视频副标题',
                'name' => 'video_subtitle',
                'type' => 'textarea',
                'rows' => 2,
            ],
            [
                'key' => 'field_video_duration',
                'label' => '视频时长',
                'name' => 'video_duration',
                'type' => 'text',
                'instructions' => '可选，系统会自动从视频文件获取时长',
                'placeholder' => '12:34',
            ],
            [
                'key' => 'field_video_level',
                'label' => '难度等级',
                'name' => 'video_level',
                'type' => 'select',
                'choices' => [
                    'Beginner' => 'Beginner',
                    'Intermediate' => 'Intermediate',
                    'Advanced' => 'Advanced',
                ],
                'default_value' => 'Beginner',
            ],
            [
                'key' => 'field_video_views',
                'label' => '观看次数',
                'name' => 'video_views',
                'type' => 'number',
                'default_value' => 0,
            ],
            [
                'key' => 'field_video_language',
                'label' => '视频语言',
                'name' => 'video_language',
                'type' => 'text',
                'placeholder' => 'English',
            ],
            [
                'key' => 'field_video_subtitles',
                'label' => '字幕可用性',
                'name' => 'video_subtitles',
                'type' => 'text',
                'placeholder' => 'Available',
            ],
            [
                'key' => 'field_instructor_name',
                'label' => '讲师姓名',
                'name' => 'instructor_name',
                'type' => 'text',
                'placeholder' => 'Dr. Michael Chen',
            ],
            [
                'key' => 'field_instructor_title',
                'label' => '讲师头衔',
                'name' => 'instructor_title',
                'type' => 'text',
                'placeholder' => 'Senior Quantitative Analyst',
            ],
            [
                'key' => 'field_instructor_bio',
                'label' => '讲师简介',
                'name' => 'instructor_bio',
                'type' => 'textarea',
                'rows' => 3,
                'placeholder' => '15+ years experience in algorithmic trading and financial modeling.',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'video',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
    ]);
}

