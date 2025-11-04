<?php
/**
 * Elementor 自定义小部件加载器
 */

if (!defined('ABSPATH')) {
    exit;
}

// 检查 Elementor 是否已激活
if (!did_action('elementor/loaded')) {
    return;
}

// 注册自定义小部件
add_action('elementor/widgets/register', 'rena_register_elementor_widgets');

function rena_register_elementor_widgets($widgets_manager) {
    // 检查是否有必要的类
    if (!class_exists('\Elementor\Widget_Base')) {
        return;
    }

    // 加载小部件文件
    $widget_files = [
        // 动态内容小部件
        'media-reports.php',
        'scientists-list.php',
        'cases-list.php',
        'announcements-list.php',
        'video-tutorials.php',
        // 静态内容小部件
        'hero-section.php',
        'company-tabs.php',
        'mission-vision.php',
        'feature-cards.php',
        'section-title.php',
        'research-hero.php',
        'downloads-hero.php',
        'main-download-card.php',
        'commercialization-path.php',
        'get-started-cta.php',
    ];

    foreach ($widget_files as $file) {
        $path = get_template_directory() . '/inc/elementor/widgets/' . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }

    // 注册小部件（检查类是否存在）
    $widget_classes = [
        'Renaissance_Media_Reports_Widget',
        'Renaissance_Scientists_List_Widget',
        'Renaissance_Cases_List_Widget',
        'Renaissance_Announcements_List_Widget',
        'Renaissance_Video_Tutorials_Widget',
        'Renaissance_Hero_Section_Widget',
        'Renaissance_Company_Tabs_Widget',
        'Renaissance_Mission_Vision_Widget',
        'Renaissance_Feature_Cards_Widget',
        'Renaissance_Section_Title_Widget',
        'Renaissance_Research_Hero_Widget',
        'Renaissance_Downloads_Hero_Widget',
        'Renaissance_Main_Download_Card_Widget',
        'Renaissance_Commercialization_Path_Widget',
        'Renaissance_Get_Started_CTA_Widget',
    ];

    foreach ($widget_classes as $widget_class) {
        if (class_exists($widget_class)) {
            $widgets_manager->register(new $widget_class());
        }
    }
}

// 添加自定义小部件分类
add_action('elementor/elements/categories_registered', 'rena_add_elementor_widget_categories');

function rena_add_elementor_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'renaissance-dynamic',
        [
            'title' => __('Renaissance Dynamic', 'renaissance'),
            'icon' => 'fa fa-plug',
        ]
    );
}

