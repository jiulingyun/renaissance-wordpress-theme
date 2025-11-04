<?php
/**
 * 主题激活时创建默认内容
 * 包括文章、案例、公告、视频等
 */

if (!defined('ABSPATH')) {
    exit;
}

// 主题激活时创建默认文章内容
add_action('after_switch_theme', 'rena_insert_default_posts', 30);

function rena_insert_default_posts() {
    // 检查是否已有预设内容（通过检查是否有案例文章）
    // 注意：必须指定 'lang' => '' 来检查所有语言的文章
    $existing_cases = get_posts([
        'post_type' => 'case',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'lang' => '', // 检查所有语言
    ]);
    
    // 如果已经有案例，说明预设内容已创建，跳过
    if (!empty($existing_cases)) {
        return;
    }

    $theme_uri = get_template_directory_uri();

    // 1. 创建媒体报道分类
    $media_category = wp_insert_term('Media Reports', 'category', [
        'description' => 'Press releases and media coverage',
        'slug' => 'media-reports',
    ]);

    $category_id = is_wp_error($media_category) ? 0 : $media_category['term_id'];

    // 2. 创建媒体报道文章（2篇）
    $media_posts = [
        [
            'title' => 'Awarded "Most Innovative Quantitative Investment Company" by Financial Times',
            'content' => 'Recognition for our groundbreaking algorithmic trading strategies and exceptional performance in quantitative finance, setting new industry standards for innovation and excellence. Our team has developed proprietary mathematical models that consistently outperform traditional investment strategies.',
            'excerpt' => 'Recognition for our groundbreaking algorithmic trading strategies and exceptional performance in quantitative finance, setting new industry standards for innovation and excellence.',
            'image' => 'award-trophy-01.jpg',
        ],
        [
            'title' => 'Advanced neural networks process millions of market data points in real-time for optimal investment decisions',
            'content' => 'Our cutting-edge AI technology analyzes vast amounts of financial data with unprecedented speed and accuracy, enabling superior market predictions and risk assessment capabilities. The system processes over 100 million data points daily across global markets.',
            'excerpt' => 'Our cutting-edge AI technology analyzes vast amounts of financial data with unprecedented speed and accuracy, enabling superior market predictions and risk assessment capabilities.',
            'image' => 'award-trophy-02.jpg',
        ],
    ];

    foreach ($media_posts as $post_data) {
        $post_id = wp_insert_post([
            'post_type' => 'post',
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_excerpt' => $post_data['excerpt'],
            'post_status' => 'publish',
            'post_category' => $category_id ? [$category_id] : [],
        ]);

        // 设置特色图片和语言
        if ($post_id && !is_wp_error($post_id)) {
            rena_set_post_thumbnail($post_id, $post_data['image']);
            
            // 设置 Polylang 语言为英语
            if (function_exists('pll_set_post_language')) {
                pll_set_post_language($post_id, 'en');
            }
        }
    }

    // 3. 创建案例文章（3篇）
    $cases = [
        [
            'title' => 'Medallion Fund - The Most Successful Hedge Fund in History',
            'content' => 'The legendary quantitative fund that revolutionized Wall Street with mathematical models and data-driven strategies. Since its inception in 1988, the Medallion Fund has achieved an average annual return of 66% before fees, making it the most successful hedge fund in history.',
            'excerpt' => 'The legendary quantitative fund that revolutionized Wall Street with mathematical models and data-driven strategies',
            'image' => 'case-1.jpg',
            'tags' => ['Annual Return: 66%', 'Net Return: 39%'],
        ],
        [
            'title' => 'Quantitative Models Capture Macro Volatility Opportunities',
            'content' => 'Exceptional performance during the 2008 financial crisis through cross-market neutral strategies. Our quantitative models identified opportunities during market turmoil, generating positive returns while traditional funds suffered significant losses.',
            'excerpt' => 'Exceptional performance during the 2008 financial crisis through cross-market neutral strategies',
            'image' => 'case-2.jpg',
            'tags' => ['Crisis Return: +80%', 'Year: 2008'],
        ],
        [
            'title' => 'AI Models Optimize Stock Selection and Risk Control',
            'content' => 'Advanced deep learning algorithms and alternative data integration for superior equity performance. Our AI-powered stock selection system analyzes thousands of factors to identify high-potential investments while maintaining strict risk controls.',
            'excerpt' => 'Advanced deep learning algorithms and alternative data integration for superior equity performance',
            'image' => 'case-3.jpg',
            'tags' => ['Period: 2019-2021', 'Focus: Tech+Consumer'],
        ],
    ];

    foreach ($cases as $case_data) {
        $post_id = wp_insert_post([
            'post_type' => 'case',
            'post_title' => $case_data['title'],
            'post_content' => $case_data['content'],
            'post_excerpt' => $case_data['excerpt'],
            'post_status' => 'publish',
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // 设置特色图片
            rena_set_post_thumbnail($post_id, $case_data['image']);
            
            // 添加标签
            if (!empty($case_data['tags'])) {
                wp_set_post_tags($post_id, $case_data['tags']);
            }
            
            // 设置 Polylang 语言为英语
            if (function_exists('pll_set_post_language')) {
                pll_set_post_language($post_id, 'en');
            }
        }
    }

    // 4. 创建公告文章（1篇）
    $announcement_id = wp_insert_post([
        'post_type' => 'announcement',
        'post_title' => 'AI Model Enhancement Update - Next-Generation Quantitative Trading System',
        'post_content' => '<p>We are pleased to announce the official release of Renaissance Technologies\' latest AI model enhancement update. This update introduces next-generation machine learning algorithms, significantly improving market prediction accuracy and trading strategy effectiveness.</p><p>The new algorithm framework adopts deep neural networks and reinforcement learning techniques, enabling better identification of market patterns and trend changes. After extensive historical data testing, the new model has demonstrated exceptional performance across various market conditions.</p>',
        'post_excerpt' => 'We are pleased to announce the official release of Renaissance Technologies\' latest AI model enhancement update. This update introduces next-generation machine learning algorithms, significantly improving market prediction accuracy and trading strategy effectiveness.',
        'post_status' => 'publish',
    ]);

    if ($announcement_id && !is_wp_error($announcement_id)) {
        wp_set_post_tags($announcement_id, ['Important Update', 'System Update']);
        
        // 设置 Polylang 语言为英语
        if (function_exists('pll_set_post_language')) {
            pll_set_post_language($announcement_id, 'en');
        }
    }

    // 5. 上传演示视频到媒体库
    $demo_video_url = rena_upload_demo_video();

    // 6. 创建视频教程文章（4篇）
    $videos = [
        [
            'title' => 'Getting Started with Renaissance Platform',
            'content' => '<p>Learn the basics of our quantitative trading platform. This comprehensive guide covers platform setup, basic navigation, and essential features you need to get started with algorithmic trading.</p>',
            'excerpt' => 'Basic setup and configuration guide for new users',
        ],
        [
            'title' => 'Advanced Trading Strategies',
            'content' => '<p>Explore advanced quantitative trading strategies and techniques. Learn how to implement sophisticated algorithms and optimize your trading performance.</p>',
            'excerpt' => 'Professional trading techniques and optimization methods',
        ],
        [
            'title' => 'Risk Management Fundamentals',
            'content' => '<p>Master the essential principles of risk management in quantitative trading. Understand how to protect your capital while maximizing returns.</p>',
            'excerpt' => 'Essential risk control and portfolio management techniques',
        ],
        [
            'title' => 'Live Trading Demonstrations',
            'content' => '<p>Watch real-time trading demonstrations and learn practical tips from experienced traders. See how our strategies perform in live market conditions.</p>',
            'excerpt' => 'Real-time trading examples and practical insights',
        ],
    ];

    foreach ($videos as $video_data) {
        // 在内容中插入视频
        if ($demo_video_url) {
            $video_html = '<video controls width="100%"><source src="' . esc_url($demo_video_url) . '" type="video/webm">Your browser does not support the video tag.</video>';
            $video_data['content'] .= $video_html;
        }
        
        $post_id = wp_insert_post([
            'post_type' => 'video',
            'post_title' => $video_data['title'],
            'post_content' => $video_data['content'],
            'post_excerpt' => $video_data['excerpt'],
            'post_status' => 'publish',
        ]);
        
        // 设置 Polylang 语言为英语
        if ($post_id && !is_wp_error($post_id) && function_exists('pll_set_post_language')) {
            pll_set_post_language($post_id, 'en');
        }
    }

    // 不再使用标记选项，改为检查是否已有文章
}

// 辅助函数：上传演示视频到媒体库
function rena_upload_demo_video() {
    $video_path = get_template_directory() . '/assets/video/demo1.webm';
    
    if (!file_exists($video_path)) {
        return false;
    }

    $upload_dir = wp_upload_dir();
    $filename = 'demo-video.webm';
    $upload_file = $upload_dir['path'] . '/' . $filename;
    
    // 检查是否已上传
    $existing_url = $upload_dir['url'] . '/' . $filename;
    $existing_id = attachment_url_to_postid($existing_url);
    if ($existing_id) {
        return wp_get_attachment_url($existing_id);
    }
    
    // 复制视频到上传目录
    if (!copy($video_path, $upload_file)) {
        return false;
    }
    
    // 创建媒体附件
    $attachment = [
        'guid' => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => 'video/webm',
        'post_title' => 'Renaissance Demo Video',
        'post_content' => '',
        'post_status' => 'inherit'
    ];
    
    $attach_id = wp_insert_attachment($attachment, $upload_file);
    
    if (!is_wp_error($attach_id)) {
        // 加载必要的文件
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        // 生成视频元数据
        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        return wp_get_attachment_url($attach_id);
    }
    
    return false;
}

// 辅助函数：设置文章特色图片
function rena_set_post_thumbnail($post_id, $image_filename) {
    $image_path = get_template_directory() . '/assets/img/' . $image_filename;
    
    if (!file_exists($image_path)) {
        return;
    }

    $upload_dir = wp_upload_dir();
    $filename = basename($image_path);
    $upload_file = $upload_dir['path'] . '/' . $filename;
    
    // 如果文件已存在，直接使用
    if (file_exists($upload_file)) {
        $attachment_id = attachment_url_to_postid($upload_dir['url'] . '/' . $filename);
        if ($attachment_id) {
            set_post_thumbnail($post_id, $attachment_id);
            return;
        }
    }
    
    // 复制图片到上传目录
    copy($image_path, $upload_file);
    
    // 使用 WordPress 原生函数获取 MIME 类型（兼容性更好）
    $filetype = wp_check_filetype($filename, null);
    $mime_type = $filetype['type'] ? $filetype['type'] : 'image/jpeg'; // 默认为 jpeg
    
    $attachment = [
        'guid' => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => $mime_type,
        'post_title' => pathinfo($filename, PATHINFO_FILENAME),
        'post_content' => '',
        'post_status' => 'inherit'
    ];
    
    $attach_id = wp_insert_attachment($attachment, $upload_file, $post_id);
    
    if (!is_wp_error($attach_id)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_post_thumbnail($post_id, $attach_id);
    }
}

