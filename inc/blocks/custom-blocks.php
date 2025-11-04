<?php
/**
 * 自定义 Gutenberg 区块
 * 为主题添加自定义区块，方便编辑文章时使用
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 注册自定义区块
add_action('init', 'rena_register_custom_blocks');

function rena_register_custom_blocks() {
    // 检查 Gutenberg 是否可用
    if (!function_exists('register_block_type')) {
        return;
    }

    // 1. Methodology Card（方法论卡片）
    register_block_type('renaissance/methodology-card', [
        'render_callback' => 'rena_render_methodology_card',
        'title' => __('Methodology Card', 'renaissance'),
        'description' => __('A card to display methodology or approach', 'renaissance'),
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => 'Methodology Title',
            ],
            'description' => [
                'type' => 'string',
                'default' => 'Description of the methodology...',
            ],
        ],
    ]);

    // 2. Result Card（结果卡片）
    register_block_type('renaissance/result-card', [
        'render_callback' => 'rena_render_result_card',
        'title' => __('Result Card', 'renaissance'),
        'description' => __('A card to display metrics and results', 'renaissance'),
        'attributes' => [
            'number' => [
                'type' => 'string',
                'default' => '100%',
            ],
            'label' => [
                'type' => 'string',
                'default' => 'Metric Label',
            ],
            'description' => [
                'type' => 'string',
                'default' => 'Metric description',
            ],
        ],
    ]);

    // 3. Results Grid Container（结果网格容器）
    register_block_type('renaissance/results-grid', [
        'render_callback' => 'rena_render_results_grid',
        'title' => __('Results Grid', 'renaissance'),
        'description' => __('A grid container for result cards', 'renaissance'),
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => 'Results & Performance',
            ],
        ],
    ]);

    // 4. Methodology Grid Container（方法论网格容器）
    register_block_type('renaissance/methodology-grid', [
        'render_callback' => 'rena_render_methodology_grid',
        'title' => __('Methodology Grid', 'renaissance'),
        'description' => __('A grid container for methodology cards', 'renaissance'),
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => 'Methodology & Approach',
            ],
        ],
    ]);
}

// Methodology Card 渲染函数
function rena_render_methodology_card($attributes, $content) {
    $title = esc_html($attributes['title']);
    $description = esc_html($attributes['description']);
    
    ob_start();
    ?>
    <div class="methodology-item">
        <div class="methodology-icon">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <rect width="40" height="40" rx="8" fill="rgba(124, 58, 237, 0.2)"/>
                <path d="M12 20L18 26L28 14" stroke="#a855f7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h4 class="methodology-title"><?php echo $title; ?></h4>
        <p class="methodology-desc"><?php echo $description; ?></p>
    </div>
    <?php
    return ob_get_clean();
}

// Result Card 渲染函数
function rena_render_result_card($attributes, $content) {
    $number = esc_html($attributes['number']);
    $label = esc_html($attributes['label']);
    $description = esc_html($attributes['description']);
    
    ob_start();
    ?>
    <div class="result-card">
        <div class="result-number"><?php echo $number; ?></div>
        <div class="result-label"><?php echo $label; ?></div>
        <div class="result-desc"><?php echo $description; ?></div>
    </div>
    <?php
    return ob_get_clean();
}

// Results Grid 渲染函数
function rena_render_results_grid($attributes, $content) {
    $title = esc_html($attributes['title']);
    
    ob_start();
    ?>
    <div class="case-content-section">
        <h2 class="section-title"><?php echo $title; ?></h2>
        <div class="results-grid">
            <?php echo $content; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Methodology Grid 渲染函数
function rena_render_methodology_grid($attributes, $content) {
    $title = esc_html($attributes['title']);
    
    ob_start();
    ?>
    <div class="case-content-section">
        <h2 class="section-title"><?php echo $title; ?></h2>
        <div class="methodology-grid">
            <?php echo $content; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// 添加自定义区块分类
add_filter('block_categories_all', 'rena_custom_block_category', 10, 2);

function rena_custom_block_category($categories, $post) {
    return array_merge(
        $categories,
        [
            [
                'slug' => 'renaissance',
                'title' => __('Renaissance Blocks', 'renaissance'),
                'icon' => 'star-filled',
            ],
        ]
    );
}

// 注册区块编辑器脚本
add_action('enqueue_block_editor_assets', 'rena_enqueue_block_editor_assets');

function rena_enqueue_block_editor_assets() {
    $uri = get_template_directory_uri();
    
    wp_enqueue_script(
        'renaissance-blocks',
        $uri . '/assets/js/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-components', 'wp-editor'],
        '1.0.0',
        true
    );
    
    wp_enqueue_style(
        'renaissance-blocks-editor',
        $uri . '/assets/css/blocks-editor.css',
        ['wp-edit-blocks'],
        '1.0.0'
    );
}

