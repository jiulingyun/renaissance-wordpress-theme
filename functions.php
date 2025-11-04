<?php
/**
 * Renaissance WordPress Theme
 *
 * @package     Renaissance
 * @author      JiuLingYun
 * @copyright   Copyright (c) 2025 JiuLingYun (https://www.jiulingyun.cn)
 * @license     GPL v2 or later
 * @link        https://www.jiulingyun.cn
 * @version     1.6.9
 *
 * This file is part of the Renaissance WordPress Theme.
 *
 * Renaissance is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * Renaissance is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// 主题基础支持与加载语言包
add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('elementor'); // 添加 Elementor 支持
  load_theme_textdomain('renaissance', get_template_directory() . '/languages');
});

// 隐藏非管理员用户的 WordPress 顶部工具条
add_action('after_setup_theme', function() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
});

// 注册导航菜单
add_action('after_setup_theme', function () {
  register_nav_menus([
    'primary' => __('Primary Menu', 'renaissance'),
    'footer'  => __('Footer Menu', 'renaissance'),
  ]);
});

// 资源加载：映射 extracted 静态资源至主题 assets
add_action('wp_enqueue_scripts', function () {
  $uri = get_template_directory_uri();
  // CSS
  wp_enqueue_style('bootstrap', $uri . '/assets/css/bootstrap.min.css', [], '5.3.0');
  wp_enqueue_style('bs-icons', $uri . '/assets/css/bootstrap-icons.min.css', [], '1.11.0');
  wp_enqueue_style('theme-style', $uri . '/assets/css/style.css', ['bootstrap'], '1.0.0');
  wp_enqueue_style('custom-fixes', $uri . '/assets/css/custom-fixes.css', ['theme-style'], '1.0.0');

  // JS（放页脚）
  wp_enqueue_script('bootstrap', $uri . '/assets/js/bootstrap.bundle.min.js', [], '5.3.0', true);
  wp_enqueue_script('earth-globe', $uri . '/assets/js/earth-globe.js', [], '1.0.0', true);
  wp_enqueue_script('floating-particles', $uri . '/assets/js/floating-particles.js', [], '1.0.0', true);
  wp_enqueue_script('particles', $uri . '/assets/js/particles.js', [], '1.0.0', true);
  wp_enqueue_script('newsletter', $uri . '/assets/js/newsletter.js', [], '1.0.0', true);
  // 静态语言切换 JS 已禁用，现在使用 Polylang 进行服务器端多语言管理
  // wp_enqueue_script('lang-en', $uri . '/assets/js/lang/en.js', [], '1.0.0', true);
  // wp_enqueue_script('lang-zh', $uri . '/assets/js/lang/zh.js', [], '1.0.0', true);
  // wp_enqueue_script('lang-fr', $uri . '/assets/js/lang/fr.js', [], '1.0.0', true);
  // wp_enqueue_script('language', $uri . '/assets/js/language.js', ['lang-en', 'lang-zh', 'lang-fr'], '1.0.0', true);
  wp_enqueue_script('back-to-top', $uri . '/assets/js/back-to-top.js', [], '1.0.0', true);
  
  // Contact page specific
  if (is_page_template('page-templates/page-contact.php')) {
    wp_enqueue_script('contact', $uri . '/assets/js/contact.js', [], '1.0.0', true);
  }
  
  // Login page specific
  if (is_page_template('page-templates/page-login.php')) {
    wp_enqueue_script('login', $uri . '/assets/js/login.js', [], '1.0.0', true);
  }
  
  // Register page specific
  if (is_page_template('page-templates/page-register.php')) {
    wp_enqueue_script('register', $uri . '/assets/js/register.js', [], '1.0.0', true);
  }
  
  // Forgot password page specific
  if (is_page_template('page-templates/page-forgot-password.php')) {
    wp_enqueue_script('forgot-password', $uri . '/assets/js/forgot-password.js', [], '1.0.0', true);
  }
});

// 注册 Polylang 字符串翻译（用于 Customizer 设置）
add_action('init', function () {
  if (function_exists('pll_register_string')) {
    // 注册页脚可编辑字符串
    $footer_description = get_theme_mod('footer_description', 'All rights reserved. The information on this website is for informational and discussion purposes only and does not constitute any issuance. Issuance can only be made by delivering a confidential issuance memorandum to appropriate investors. Past performance is not a guarantee of future performance. www.renfundx.com is the only official website of Renaissance Technologies of Canada Ltd.. Renaissance Technologies and any of its affiliated companies do not operate any other public websites. Any websites claiming to be associated with our company or our funds are not legitimate.');
    $whatsapp_text = get_theme_mod('footer_whatsapp_text', 'WhatsApp Consultation');
    $footer_copyright = get_theme_mod('footer_copyright', '© 2025 Renaissance Technologies of Canada Ltd.');
    
    pll_register_string('footer_description', $footer_description, 'Renaissance Theme - Footer');
    pll_register_string('footer_whatsapp_text', $whatsapp_text, 'Renaissance Theme - Footer');
    pll_register_string('footer_copyright', $footer_copyright, 'Renaissance Theme - Footer');
  }
}, 99); // 优先级设为 99，确保在 Polylang 加载后执行

// 自定义能力与角色（Downloads 权限）
add_action('init', function () {
  $cap = 'access_downloads';
  if ($admin = get_role('administrator')) {
    $admin->add_cap($cap);
  }
  if (!get_role('premium_member')) {
    add_role('premium_member', __('Premium Member', 'renaissance'), [
      'read' => true,
      $cap   => true,
    ]);
  } else {
    // 如果角色已存在，更新显示名称以支持翻译
    $role = get_role('premium_member');
    if ($role) {
      // 移除旧角色并重新创建以更新名称
      remove_role('premium_member');
      add_role('premium_member', __('Premium Member', 'renaissance'), [
        'read' => true,
        $cap   => true,
      ]);
    }
  }
});

// 访问判断助手：登录且有能力
function rena_user_has_download_access(): bool {
  return is_user_logged_in() && current_user_can('access_downloads');
}

// 获取多语言页面 URL 的辅助函数
function rena_get_translated_page_url($page_slug, $fallback_url = '') {
  $page = get_page_by_path($page_slug);
  if ($page && function_exists('pll_get_post') && function_exists('pll_current_language')) {
    $current_lang = pll_current_language();
    $translated_page_id = pll_get_post($page->ID, $current_lang);
    if ($translated_page_id) {
      return get_permalink($translated_page_id);
    }
  }
  // 回退到默认 URL
  return $fallback_url ? $fallback_url : home_url('/' . $page_slug . '/');
}

// 注册自定义文章类型到 Polylang
add_filter('pll_get_post_types', function($post_types, $is_settings) {
  // 添加自定义文章类型到 Polylang 支持列表
  $post_types['case'] = 'case';
  $post_types['announcement'] = 'announcement';
  $post_types['video'] = 'video';
  $post_types['scientist'] = 'scientist';
  return $post_types;
}, 10, 2);

// 注册分类和标签到 Polylang
add_filter('pll_get_taxonomies', function($taxonomies, $is_settings) {
  // 添加分类和标签到 Polylang 支持列表
  $taxonomies['category'] = 'category';
  $taxonomies['post_tag'] = 'post_tag';
  return $taxonomies;
}, 10, 2);

// 注册自定义文章类型
add_action('init', function () {
  // 1. Cases（成功案例）
  register_post_type('case', [
    'labels' => [
      'name'               => __('Cases', 'renaissance'),
      'singular_name'      => __('Case', 'renaissance'),
      'menu_name'          => __('Cases', 'renaissance'),
      'add_new'            => __('Add New', 'renaissance'),
      'add_new_item'       => __('Add New Case', 'renaissance'),
      'edit_item'          => __('Edit Case', 'renaissance'),
      'new_item'           => __('New Case', 'renaissance'),
      'view_item'          => __('View Case', 'renaissance'),
      'search_items'       => __('Search Cases', 'renaissance'),
      'not_found'          => __('No cases found', 'renaissance'),
      'not_found_in_trash' => __('No cases found in trash', 'renaissance'),
    ],
    'public'        => true,
    'has_archive'   => true,
    'rewrite'       => ['slug' => 'cases'],
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'], // 支持排序
    'menu_icon'     => 'dashicons-portfolio',
    'menu_position' => 5,
    'show_in_rest'  => true,
    'taxonomies'    => ['post_tag'], // 支持标签
  ]);

  // 2. Announcements（公告）
  register_post_type('announcement', [
    'labels' => [
      'name'               => __('Announcements', 'renaissance'),
      'singular_name'      => __('Announcement', 'renaissance'),
      'menu_name'          => __('Announcements', 'renaissance'),
      'add_new'            => __('Add New', 'renaissance'),
      'add_new_item'       => __('Add New Announcement', 'renaissance'),
      'edit_item'          => __('Edit Announcement', 'renaissance'),
      'new_item'           => __('New Announcement', 'renaissance'),
      'view_item'          => __('View Announcement', 'renaissance'),
      'search_items'       => __('Search Announcements', 'renaissance'),
      'not_found'          => __('No announcements found', 'renaissance'),
      'not_found_in_trash' => __('No announcements found in trash', 'renaissance'),
    ],
    'public'        => true,
    'has_archive'   => true,
    'rewrite'       => ['slug' => 'announcements'],
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'], // 支持排序
    'menu_icon'     => 'dashicons-megaphone',
    'menu_position' => 6,
    'show_in_rest'  => true,
    'taxonomies'    => ['post_tag'], // 支持标签（如"重要更新"）
  ]);

  // 3. Videos（视频教程）
  register_post_type('video', [
    'labels' => [
      'name'               => __('Videos', 'renaissance'),
      'singular_name'      => __('Video', 'renaissance'),
      'menu_name'          => __('Videos', 'renaissance'),
      'add_new'            => __('Add New', 'renaissance'),
      'add_new_item'       => __('Add New Video', 'renaissance'),
      'edit_item'          => __('Edit Video', 'renaissance'),
      'new_item'           => __('New Video', 'renaissance'),
      'view_item'          => __('View Video', 'renaissance'),
      'search_items'       => __('Search Videos', 'renaissance'),
      'not_found'          => __('No videos found', 'renaissance'),
      'not_found_in_trash' => __('No videos found in trash', 'renaissance'),
    ],
    'public'        => true,
    'has_archive'   => true,
    'rewrite'       => ['slug' => 'videos'],
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'], // 支持排序
    'menu_icon'     => 'dashicons-video-alt3',
    'menu_position' => 7,
    'show_in_rest'  => true,
    'taxonomies'    => ['post_tag'],
  ]);

  // 4. Scientists/Engineers（科学家/工程师）
  register_post_type('scientist', [
    'labels' => [
      'name'               => __('Scientists', 'renaissance'),
      'singular_name'      => __('Scientist', 'renaissance'),
      'menu_name'          => __('Scientists', 'renaissance'),
      'add_new'            => __('Add New', 'renaissance'),
      'add_new_item'       => __('Add New Scientist', 'renaissance'),
      'edit_item'          => __('Edit Scientist', 'renaissance'),
      'new_item'           => __('New Scientist', 'renaissance'),
      'view_item'          => __('View Scientist', 'renaissance'),
      'search_items'       => __('Search Scientists', 'renaissance'),
      'not_found'          => __('No scientists found', 'renaissance'),
      'not_found_in_trash' => __('No scientists found in trash', 'renaissance'),
    ],
    'public'        => true,
    'has_archive'   => false,
    'rewrite'       => ['slug' => 'scientist'],
    'supports'      => ['title', 'editor', 'thumbnail', 'page-attributes'], // 支持排序
    'menu_icon'     => 'dashicons-groups',
    'menu_position' => 8,
    'show_in_rest'  => true,
  ]);
}, 20); // 优先级设为 20，在角色能力注册之后

// 为默认的 post 类型添加 page-attributes 支持
add_filter('register_post_type_args', 'rena_add_page_attributes_to_post', 10, 2);
function rena_add_page_attributes_to_post($args, $post_type) {
  if ($post_type === 'post') {
    $args['supports'][] = 'page-attributes';
  }
  return $args;
}

// 设置后台列表默认按 menu_order 排序
add_action('pre_get_posts', 'rena_admin_posts_orderby');
function rena_admin_posts_orderby($query) {
  if (!is_admin() || !$query->is_main_query()) {
    return;
  }

  $screen = get_current_screen();
  if (!$screen) {
    return;
  }

  // 为所有需要排序的文章类型设置默认排序
  $post_types = ['post', 'page', 'case', 'announcement', 'video', 'scientist'];
  
  if (in_array($screen->post_type, $post_types)) {
    // 如果没有设置排序参数，默认按 menu_order 排序
    if (!isset($_GET['orderby']) || empty($_GET['orderby'])) {
      $query->set('orderby', 'menu_order');
      $query->set('order', 'ASC');
    }
  }
}

// 为自定义文章类型添加 Polylang 支持
add_filter('pll_get_post_types', 'rena_add_cpt_to_polylang', 10, 2);
function rena_add_cpt_to_polylang($post_types, $is_settings) {
  if ($is_settings) {
    // 在 Polylang 设置页面显示
    $post_types['case'] = 'case';
    $post_types['announcement'] = 'announcement';
    $post_types['video'] = 'video';
    $post_types['scientist'] = 'scientist';
  } else {
    // 实际启用翻译
    $post_types['case'] = 'case';
    $post_types['announcement'] = 'announcement';
    $post_types['video'] = 'video';
    $post_types['scientist'] = 'scientist';
  }
  return $post_types;
}

// 主题激活时设置固定链接为"文章名"模式
add_action('after_switch_theme', 'rena_set_permalink_structure', 5);
function rena_set_permalink_structure() {
  // 检查当前固定链接设置
  $current_structure = get_option('permalink_structure');
  
  // 强制设置为"文章名"格式（/%postname%/）
  // 这与WordPress后台"设置 > 固定链接"中的"文章名"选项对应
  if ($current_structure !== '/%postname%/') {
    update_option('permalink_structure', '/%postname%/');
    flush_rewrite_rules();
  }
}

// 主题激活时设置 Home 页面为首页
add_action('after_switch_theme', 'rena_set_homepage', 25);
function rena_set_homepage() {
  // 查找 Home 页面
  $home_page = get_page_by_path('home');
  
  if (!$home_page) {
    // 如果不存在，尝试按标题查找
    $home_page = get_page_by_title('Home');
  }
  
  if ($home_page) {
    // 设置为首页
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_page->ID);
    
    // 如果还没有博客页面，可以设置 Downloads 或创建一个
    $posts_page = get_option('page_for_posts');
    if (!$posts_page) {
      // 不设置博客页面，因为我们主要使用自定义文章类型
      update_option('page_for_posts', 0);
    }
  }
}

// 主题激活时创建默认菜单（优先级 20，在页面创建之后）
add_action('after_switch_theme', 'rena_create_default_menus', 20);
function rena_create_default_menus() {
  
  // 创建 Primary Menu
  $primary_menu_name = 'Primary Navigation';
  $primary_menu = wp_get_nav_menu_object($primary_menu_name);
  
  if (!$primary_menu) {
    $primary_menu_id = wp_create_nav_menu($primary_menu_name);
  } else {
    $primary_menu_id = $primary_menu->term_id;
    // 清空现有菜单项
    $menu_items = wp_get_nav_menu_items($primary_menu_id);
    if ($menu_items) {
      foreach ($menu_items as $item) {
        wp_delete_post($item->ID, true);
      }
    }
  }

  // 添加 Home 链接（使用自定义链接）
  wp_update_nav_menu_item($primary_menu_id, 0, [
    'menu-item-title' => 'Home',
    'menu-item-url' => home_url('/'),
    'menu-item-type' => 'custom',
    'menu-item-status' => 'publish',
    'menu-item-position' => 1,
  ]);

  // 添加 Research 页面（使用页面类型确保正确链接）
  $research_page = get_page_by_title('Research');
  if ($research_page) {
    wp_update_nav_menu_item($primary_menu_id, 0, [
      'menu-item-title' => 'Research',
      'menu-item-object' => 'page',
      'menu-item-object-id' => $research_page->ID,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
      'menu-item-position' => 2,
    ]);
  }

  // 添加 Downloads 页面
  $downloads_page = get_page_by_title('Downloads');
  if ($downloads_page) {
    wp_update_nav_menu_item($primary_menu_id, 0, [
      'menu-item-title' => 'Downloads',
      'menu-item-object' => 'page',
      'menu-item-object-id' => $downloads_page->ID,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
      'menu-item-position' => 3,
    ]);
  }

  // 添加 Member Login 链接（链接到自定义登录页面）
  $login_page = get_page_by_path('login');
  if (!$login_page) {
    // 如果按 slug 找不到，尝试按标题查找
    $login_page = get_page_by_title('Member');
  }
  if ($login_page) {
    wp_update_nav_menu_item($primary_menu_id, 0, [
      'menu-item-title' => 'Member',
      'menu-item-object' => 'page',
      'menu-item-object-id' => $login_page->ID,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
      'menu-item-position' => 4,
    ]);
  }

  // 创建 Footer Menu
  $footer_menu_name = 'Footer Navigation';
  $footer_menu = wp_get_nav_menu_object($footer_menu_name);
  
  if (!$footer_menu) {
    $footer_menu_id = wp_create_nav_menu($footer_menu_name);
  } else {
    $footer_menu_id = $footer_menu->term_id;
    // 清空现有菜单项
    $menu_items = wp_get_nav_menu_items($footer_menu_id);
    if ($menu_items) {
      foreach ($menu_items as $item) {
        wp_delete_post($item->ID, true);
      }
    }
  }

  // 获取页面 ID（使用标题查找，更可靠）
  $privacy_page = get_page_by_title('Privacy Policy');
  $risk_page = get_page_by_title('Risk Warning');
  $contact_page = get_page_by_title('Contact Information');
  $investor_page = get_page_by_title('Investor Relations');

  // 添加菜单项到 Footer Menu
  $footer_items = [
    [
      'title' => 'Privacy Policy',
      'page' => $privacy_page,
    ],
    [
      'title' => 'Risk Warning',
      'page' => $risk_page,
    ],
    [
      'title' => 'Contact Information',
      'page' => $contact_page,
    ],
    [
      'title' => 'Investor Relations',
      'page' => $investor_page,
    ],
  ];

  foreach ($footer_items as $index => $item) {
    if ($item['page']) {
      wp_update_nav_menu_item($footer_menu_id, 0, [
        'menu-item-title' => $item['title'],
        'menu-item-object' => 'page',
        'menu-item-object-id' => $item['page']->ID,
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish',
        'menu-item-position' => $index + 1,
      ]);
    }
  }

  // 绑定菜单到主题位置（使用正确的方法）
  $locations = get_nav_menu_locations();
  $locations['primary'] = $primary_menu_id;
  $locations['footer'] = $footer_menu_id;
  set_theme_mod('nav_menu_locations', $locations);
}

// 主题激活时创建默认页面
add_action('after_switch_theme', 'rena_create_default_pages');
function rena_create_default_pages() {
  $pages_to_create = [
    [
      'title' => 'Home',
      'slug' => 'home',
      'template' => '', // 使用默认模板，支持 Elementor
      'html_file' => 'home.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Member',
      'slug' => 'login',
      'template' => 'page-templates/page-login.php',
      'html_file' => 'login.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Register',
      'slug' => 'register',
      'template' => 'page-templates/page-register.php',
      'html_file' => 'register.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Forgot Password',
      'slug' => 'forgot-password',
      'template' => 'page-templates/page-forgot-password.php',
      'html_file' => 'forgot-password.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'My Profile',
      'slug' => 'profile',
      'template' => 'page-templates/page-profile.php',
      'html_file' => 'profile.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Contact Information',
      'slug' => 'contact',
      'template' => 'page-templates/page-contact.php',
      'html_file' => 'contact.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Downloads',
      'slug' => 'downloads',
      'template' => 'page-templates/page-downloads.php',
      'html_file' => 'downloads.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Research',
      'slug' => 'research',
      'template' => 'page-templates/page-research.php',
      'html_file' => 'research.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Investor Relations',
      'slug' => 'investor-relations',
      'template' => 'page-templates/page-investor-relations.php',
      'html_file' => 'investor-relations.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Risk Warning',
      'slug' => 'risk-warning',
      'template' => 'page-templates/page-risk-warning.php',
      'html_file' => 'risk-warning.html',
      'enable_comments' => true,
    ],
    [
      'title' => 'Privacy Policy',
      'slug' => 'privacy-policy',
      'template' => 'page-templates/page-privacy-policy.php',
      'html_file' => 'privacy-policy.html',
      'enable_comments' => true,
    ],
  ];

  foreach ($pages_to_create as $page_config) {
    // 检查页面是否已存在
    $existing_page = get_page_by_path($page_config['slug']);
    
    // 读取默认 HTML 内容
    $html_file = get_template_directory() . '/default-pages/' . $page_config['html_file'];
    $default_content = file_exists($html_file) ? file_get_contents($html_file) : '';

    // 检查是否需要开启评论
    $enable_comments = isset($page_config['enable_comments']) && $page_config['enable_comments'];
    
    if ($existing_page) {
      // 页面已存在，不覆盖内容（保留用户编辑）
      // 只更新模板设置
      update_post_meta($existing_page->ID, '_wp_page_template', $page_config['template']);
      
      // 只有特定页面才启用 Elementor（Login、Register、Forgot Password 使用传统翻译）
      $non_elementor_pages = ['login', 'register', 'forgot-password'];
      if (!in_array($page_config['slug'], $non_elementor_pages)) {
        update_post_meta($existing_page->ID, '_elementor_edit_mode', 'builder');
        update_post_meta($existing_page->ID, '_elementor_template_type', 'wp-page');
      }
      
      // 如果需要开启评论
      if ($enable_comments) {
        wp_update_post(['ID' => $existing_page->ID, 'comment_status' => 'open']);
      }
    } else {
      // 创建新页面
      $page_id = wp_insert_post([
        'post_type' => 'page',
        'post_title' => $page_config['title'],
        'post_name' => $page_config['slug'],
        'post_content' => $default_content,
        'post_status' => 'publish',
        'comment_status' => $enable_comments ? 'open' : 'closed',
        'ping_status' => 'closed',
      ]);

      // 设置页面模板和 Elementor 标记
      if ($page_id && !is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', $page_config['template']);
        
        // 只有特定页面才启用 Elementor（Login、Register、Forgot Password 使用传统翻译）
        $non_elementor_pages = ['login', 'register', 'forgot-password'];
        if (!in_array($page_config['slug'], $non_elementor_pages)) {
          // 启用 Elementor 编辑模式
          update_post_meta($page_id, '_elementor_edit_mode', 'builder');
          update_post_meta($page_id, '_elementor_template_type', 'wp-page');
          update_post_meta($page_id, '_elementor_version', '3.0.0');
        }
        
        // 为特定页面添加完整的 Elementor 预设数据
        if ($page_config['slug'] === 'home') {
          rena_set_home_elementor_data($page_id);
        } elseif ($page_config['slug'] === 'research') {
          rena_set_research_elementor_data($page_id);
        } elseif ($page_config['slug'] === 'downloads') {
          rena_set_downloads_elementor_data($page_id);
        }
      }
    }
  }
}

// 主题激活时写入默认科学家数据
add_action('after_switch_theme', 'rena_insert_default_scientists');
function rena_insert_default_scientists() {
  // 检查是否已有科学家文章
  // 注意：必须指定 'lang' => '' 来检查所有语言的文章
  $existing_scientists = get_posts([
    'post_type' => 'scientist',
    'posts_per_page' => 1,
    'fields' => 'ids',
    'lang' => '', // 检查所有语言
  ]);
  
  // 如果已经有科学家，跳过
  if (!empty($existing_scientists)) {
    return;
  }

  $theme_uri = get_template_directory_uri();
  
  $default_scientists = [
    [
      'title' => 'Peter Fitzhugh Brown',
      'content' => 'American computer scientist and quantitative investment expert, currently serving as Co-CEO of Renaissance Technologies.',
      'excerpt' => 'American computer scientist and quantitative investment expert, currently serving as Co-CEO of Renaissance Technologies.',
      'avatar' => 'scientist-1.jpg',
    ],
    [
      'title' => 'Julia Nicole Macleod',
      'content' => 'Long-term support for daily operations and strategic affairs management at Renaissance Technologies. She has a solid background in finance and management, proficient in quantitative investment processes and coordination and execution in high-intensity research environments.',
      'excerpt' => 'Long-term support for daily operations and strategic affairs management at Renaissance Technologies. She has a solid background in finance and management, proficient in quantitative investment processes and coordination and execution in high-intensity research environments.',
      'avatar' => 'scientist-2.jpg',
    ],
    [
      'title' => 'Dr. Ethan MacLeod',
      'content' => 'Specializes in the application of machine learning and deep neural networks in quantitative investment, responsible for intelligent investment model optimization.',
      'excerpt' => 'Specializes in the application of machine learning and deep neural networks in quantitative investment, responsible for intelligent investment model optimization.',
      'avatar' => 'scientist-3.jpg',
    ],
    [
      'title' => 'Dr. Sophia Dubois',
      'content' => 'Focuses on multi-asset portfolio optimization and risk management, with extensive experience in derivatives and quantitative trading.',
      'excerpt' => 'Focuses on multi-asset portfolio optimization and risk management, with extensive experience in derivatives and quantitative trading.',
      'avatar' => 'scientist-4.jpg',
    ],
    [
      'title' => 'Prof.Liam Tremblay',
      'content' => 'Researches investor behavior and market psychology, using big data to improve the accuracy of quantitative strategies.',
      'excerpt' => 'Researches investor behavior and market psychology, using big data to improve the accuracy of quantitative strategies.',
      'avatar' => 'scientist-5.jpg',
    ],
    [
      'title' => 'Dr. Owen Chen',
      'content' => 'Specializes in global macroeconomic, geopolitical, and commodity market analysis, providing strategic support for investment decisions.',
      'excerpt' => 'Specializes in global macroeconomic, geopolitical, and commodity market analysis, providing strategic support for investment decisions.',
      'avatar' => 'scientist-6.jpg',
    ],
  ];

  foreach ($default_scientists as $index => $scientist) {
    $post_id = wp_insert_post([
      'post_type' => 'scientist',
      'post_title' => $scientist['title'],
      'post_content' => $scientist['content'],
      'post_excerpt' => $scientist['excerpt'],
      'post_status' => 'publish',
      'menu_order' => $index + 1,
    ]);

    // 设置特色图片（如果主题目录中有图片）
    if ($post_id && !is_wp_error($post_id)) {
      $image_path = get_template_directory() . '/assets/img/' . $scientist['avatar'];
      if (file_exists($image_path)) {
        $upload_dir = wp_upload_dir();
        $filename = basename($image_path);
        
        // 为每个科学家创建唯一的文件名，避免覆盖
        $unique_filename = $post_id . '-' . $filename;
        $upload_file = $upload_dir['path'] . '/' . $unique_filename;
        
        // 复制图片到上传目录
        copy($image_path, $upload_file);
        
        $attachment = [
          'guid' => $upload_dir['url'] . '/' . $unique_filename,
          'post_mime_type' => 'image/jpeg',
          'post_title' => $scientist['title'],
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
    }
  }

  // 不再使用标记选项，改为检查是否已有文章
}

// 从文章内容中提取第一个视频 URL
function rena_get_first_video_url($post_id) {
  $content = get_post_field('post_content', $post_id);
  
  // 匹配各种视频链接格式
  // 1. HTML5 video 标签
  if (preg_match('/<video[^>]*>.*?<source[^>]+src=["\']([^"\']+)["\'][^>]*>.*?<\/video>/is', $content, $matches)) {
    return $matches[1];
  }
  
  // 2. 直接的 video 标签 src
  if (preg_match('/<video[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches)) {
    return $matches[1];
  }
  
  // 3. WordPress 视频短代码 [video src="..."]
  if (preg_match('/\[video[^\]]+src=["\']([^"\']+)["\']/i', $content, $matches)) {
    return $matches[1];
  }
  
  // 4. 简单的视频短代码 [video]URL[/video]
  if (preg_match('/\[video\]([^\[]+)\[\/video\]/i', $content, $matches)) {
    return trim($matches[1]);
  }
  
  // 5. 直接的 MP4 链接
  if (preg_match('/(https?:\/\/[^\s]+\.(?:mp4|webm|ogg))/i', $content, $matches)) {
    return $matches[1];
  }
  
  return '';
}

// 获取视频时长
function rena_get_video_duration($post_id, $video_url = '') {
  // 优先使用自定义字段
  $duration = get_post_meta($post_id, 'video_duration', true);
  if (!empty($duration)) {
    return $duration;
  }
  
  // 如果没有视频 URL，尝试获取
  if (empty($video_url)) {
    $video_url = rena_get_first_video_url($post_id);
  }
  
  // 尝试从媒体库获取时长
  if (!empty($video_url)) {
    // 检查是否是媒体库中的文件
    $attachment_id = attachment_url_to_postid($video_url);
    if ($attachment_id) {
      $metadata = wp_get_attachment_metadata($attachment_id);
      if (isset($metadata['length_formatted'])) {
        return $metadata['length_formatted'];
      } elseif (isset($metadata['length'])) {
        // 转换秒为 MM:SS 格式
        $seconds = (int)$metadata['length'];
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
      }
    }
  }
  
  // 默认返回
  return '00:00';
}

// 主菜单回退输出（未分配菜单时）
function rena_fallback_menu_primary() {
  echo '<ul class="navbar-nav mx-auto">'
     . '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'renaissance') . '</a></li>'
     . '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/research/')) . '">' . esc_html__('Research', 'renaissance') . '</a></li>'
     . '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/downloads/')) . '">' . esc_html__('Downloads', 'renaissance') . '</a></li>'
     . '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/member/')) . '">' . esc_html__('Member', 'renaissance') . '</a></li>'
     . '</ul>';
}

// 页脚菜单：标记最后一项以便跳过分隔符
add_filter('wp_nav_menu_objects', function($items, $args) {
  if (isset($args->theme_location) && $args->theme_location === 'footer') {
    $count = count($items);
    $i = 0;
    foreach ($items as $item) {
      $i++;
      $item->_rena_is_last = ($i === $count);
    }
  }
  return $items;
}, 10, 2);

// WordPress Customizer 配置（主题自定义器）
add_action('customize_register', function($wp_customize) {
  
  // ========== 站点身份 - 添加 Logo 设置 ==========
  $wp_customize->add_setting('site_logo', [
    'default' => get_template_directory_uri() . '/assets/img/logo.svg',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'site_logo', [
    'label' => '网站 Logo',
    'description' => '上传网站Logo图片（推荐SVG或PNG格式）',
    'section' => 'title_tagline',
    'priority' => 8,
  ]));

  // ========== 页脚设置面板 ==========
  $wp_customize->add_section('footer_settings', [
    'title' => '页脚设置',
    'priority' => 35,
  ]);

  // 页脚描述文字
  $wp_customize->add_setting('footer_description', [
    'default' => 'All rights reserved. The information on this website is for informational and discussion purposes only and does not constitute any issuance. Issuance can only be made by delivering a confidential issuance memorandum to appropriate investors. Past performance is not a guarantee of future performance. www.renfundx.com is the only official website of Renaissance Technologies of Canada Ltd.. Renaissance Technologies and any of its affiliated companies do not operate any other public websites. Any websites claiming to be associated with our company or our funds are not legitimate.',
    'sanitize_callback' => 'wp_kses_post',
  ]);
  $wp_customize->add_control('footer_description', [
    'label' => 'Logo 下方描述文字',
    'description' => '显示在页脚 Logo 下方的描述文字',
    'section' => 'footer_settings',
    'type' => 'textarea',
    'input_attrs' => [
      'rows' => 5,
    ],
  ]);

  // WhatsApp 链接
  $wp_customize->add_setting('footer_whatsapp_link', [
    'default' => 'https://wa.me/message/7O5Y2WOR6HEPF1',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('footer_whatsapp_link', [
    'label' => 'WhatsApp 链接',
    'description' => '完整的 WhatsApp 联系链接',
    'section' => 'footer_settings',
    'type' => 'url',
  ]);

  // WhatsApp 按钮文字
  $wp_customize->add_setting('footer_whatsapp_text', [
    'default' => 'WhatsApp Consultation',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('footer_whatsapp_text', [
    'label' => 'WhatsApp 按钮文字',
    'section' => 'footer_settings',
    'type' => 'text',
  ]);

  // 版权文字
  $wp_customize->add_setting('footer_copyright', [
    'default' => '© 2025 Renaissance Technologies of Canada Ltd.',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('footer_copyright', [
    'label' => '版权文字',
    'description' => '显示在页脚右下角的版权信息',
    'section' => 'footer_settings',
    'type' => 'text',
  ]);
  
  // ========== 创建"Research 页面"面板 ==========
  $wp_customize->add_panel('renaissance_research_panel', [
    'title' => 'Research 页面',
    'description' => '配置 Research 页面的文字内容',
    'priority' => 31,
  ]);

  // === Research Hero 区域 ===
  $wp_customize->add_section('research_hero_section', [
    'title' => 'Hero 区域',
    'panel' => 'renaissance_research_panel',
    'priority' => 10,
  ]);

  $wp_customize->add_setting('research_hero_title', [
    'default' => 'Research Excellence',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_hero_title', [
    'label' => '主标题',
    'section' => 'research_hero_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('research_hero_subtitle', [
    'default' => 'We don\'t pursue short-term "excess returns"—we pursue repeatable, verifiable, and shareable financial scientific structures.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('research_hero_subtitle', [
    'label' => '副标题',
    'section' => 'research_hero_section',
    'type' => 'textarea',
  ]);

  // === Research 特性卡片区域 ===
  $wp_customize->add_section('research_features_section', [
    'title' => '特性卡片区域',
    'panel' => 'renaissance_research_panel',
    'priority' => 15,
  ]);

  // 特性卡片 1
  $wp_customize->add_setting('research_feature1_title', [
    'default' => 'Complexity to Clarity',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_feature1_title', [
    'label' => '卡片 1 标题',
    'section' => 'research_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('research_feature1_description', [
    'default' => 'Multi-dimensional modeling to simplify complex financial systems',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_feature1_description', [
    'label' => '卡片 1 描述',
    'section' => 'research_features_section',
    'type' => 'text',
  ]);

  // 特性卡片 2
  $wp_customize->add_setting('research_feature2_title', [
    'default' => 'Alpha to Insight',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_feature2_title', [
    'label' => '卡片 2 标题',
    'section' => 'research_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('research_feature2_description', [
    'default' => 'Every alpha model translates to actionable market intelligence',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_feature2_description', [
    'label' => '卡片 2 描述',
    'section' => 'research_features_section',
    'type' => 'text',
  ]);

  // 特性卡片 3
  $wp_customize->add_setting('research_feature3_title', [
    'default' => 'Ethics in AI Finance',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_feature3_title', [
    'label' => '卡片 3 标题',
    'section' => 'research_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('research_feature3_description', [
    'default' => 'Responsible implementation and ethical AI applications',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_feature3_description', [
    'label' => '卡片 3 描述',
    'section' => 'research_features_section',
    'type' => 'text',
  ]);

  // === Scientist 区域 ===
  $wp_customize->add_section('research_scientist_section', [
    'title' => 'Scientist 区域',
    'panel' => 'renaissance_research_panel',
    'priority' => 20,
  ]);

  $wp_customize->add_setting('research_scientist_title', [
    'default' => 'Scientist',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_scientist_title', [
    'label' => '区块标题',
    'section' => 'research_scientist_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('research_scientist_subtitle', [
    'default' => 'Based on science as the cornerstone, exploring the unknown in finance',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_scientist_subtitle', [
    'label' => '区块副标题',
    'section' => 'research_scientist_section',
    'type' => 'text',
  ]);

  // === Cases 区域 ===
  $wp_customize->add_section('research_cases_section', [
    'title' => '成功案例区域',
    'panel' => 'renaissance_research_panel',
    'priority' => 30,
  ]);

  $wp_customize->add_setting('research_cases_title', [
    'default' => 'Successful Cases',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_cases_title', [
    'label' => '区块标题',
    'section' => 'research_cases_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('research_cases_subtitle', [
    'default' => 'With excellent performance, verify the value of the model',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('research_cases_subtitle', [
    'label' => '区块副标题',
    'section' => 'research_cases_section',
    'type' => 'text',
  ]);

  // 案例文章显示数量
  $wp_customize->add_setting('research_cases_count', [
    'default' => 3,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control('research_cases_count', [
    'label' => '显示文章数量',
    'description' => '设置显示的文章数量',
    'section' => 'research_cases_section',
    'type' => 'number',
    'input_attrs' => [
      'min' => 1,
      'max' => 12,
      'step' => 1,
    ],
  ]);
  
  // ========== 创建"主页内容"面板 ==========
  $wp_customize->add_panel('renaissance_homepage_panel', [
    'title' => '主页内容',
    'description' => '配置主页各个区域的文字内容',
    'priority' => 30,
  ]);

  // === Hero 区域（首页顶部） ===
  $wp_customize->add_section('homepage_hero_section', [
    'title' => '顶部区域',
    'panel' => 'renaissance_homepage_panel',
    'priority' => 10,
  ]);

  $wp_customize->add_setting('hero_title_1', [
    'default' => 'At the intersection of algorithms and humanity',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('hero_title_1', [
    'label' => '主标题第一行',
    'section' => 'homepage_hero_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('hero_title_2', [
    'default' => 'We see the future of finance',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('hero_title_2', [
    'label' => '主标题第二行',
    'section' => 'homepage_hero_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('hero_description', [
    'default' => 'The end of technology is aesthetics, the end of finance is wisdom',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('hero_description', [
    'label' => '副标题描述',
    'section' => 'homepage_hero_section',
    'type' => 'text',
  ]);

  // === 使命与愿景区域 ===
  $wp_customize->add_section('homepage_mission_section', [
    'title' => '使命与愿景区域',
    'panel' => 'renaissance_homepage_panel',
    'priority' => 20,
  ]);

  $wp_customize->add_setting('mission_title', [
    'default' => 'Mission and Vision',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('mission_title', [
    'label' => '区块标题',
    'section' => 'homepage_mission_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('mission_subtitle', [
    'default' => 'Devoted to reshaping the financial engineering system through artificial intelligence and mathematical models, Based on scientific research and driven by industrial applications',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('mission_subtitle', [
    'label' => '区块副标题',
    'section' => 'homepage_mission_section',
    'type' => 'textarea',
  ]);

  // === 媒体报道区域 ===
  $wp_customize->add_section('homepage_media_section', [
    'title' => '媒体报道区域',
    'panel' => 'renaissance_homepage_panel',
    'priority' => 30,
  ]);

  $wp_customize->add_setting('media_title', [
    'default' => 'Media reports',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('media_title', [
    'label' => '区块标题',
    'section' => 'homepage_media_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('media_subtitle', [
    'default' => 'Focus on cutting-edge technology and capital dynamics',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('media_subtitle', [
    'label' => '区块副标题',
    'section' => 'homepage_media_section',
    'type' => 'text',
  ]);

  // 媒体报道分类选择
  $wp_customize->add_setting('media_category', [
    'default' => '',
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control('media_category', [
    'label' => '文章分类',
    'description' => '选择要显示的文章分类（留空则显示所有文章）',
    'section' => 'homepage_media_section',
    'type' => 'select',
    'choices' => rena_get_category_choices(),
  ]);

  // 媒体报道显示数量
  $wp_customize->add_setting('media_posts_count', [
    'default' => 3,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control('media_posts_count', [
    'label' => '显示文章数量',
    'description' => '设置轮播中显示的文章数量',
    'section' => 'homepage_media_section',
    'type' => 'number',
    'input_attrs' => [
      'min' => 1,
      'max' => 10,
      'step' => 1,
    ],
  ]);

  // === 公司介绍标签页区域 ===
  $wp_customize->add_section('homepage_company_tabs_section', [
    'title' => '公司介绍标签页',
    'panel' => 'renaissance_homepage_panel',
    'priority' => 15,
  ]);

  // 标签页 1 - 标题
  $wp_customize->add_setting('company_tab1_title', [
    'default' => 'COMPANY OVERVIEW',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('company_tab1_title', [
    'label' => '标签页 1 标题',
    'section' => 'homepage_company_tabs_section',
    'type' => 'text',
  ]);

  // 标签页 1 - 内容
  $wp_customize->add_setting('company_tab1_content', [
    'default' => 'Renaissance Technologies was founded by mathematician James Simons in the 1980s and is hailed as the "pinnacle symbol of quantitative investment." The company reshaped the essence of investment through mathematical models, statistics, and algorithmic logic...',
    'sanitize_callback' => 'wp_kses_post',
  ]);
  $wp_customize->add_control('company_tab1_content', [
    'label' => '标签页 1 内容',
    'section' => 'homepage_company_tabs_section',
    'type' => 'textarea',
    'input_attrs' => ['rows' => 8],
  ]);

  // 标签页 2 - 标题
  $wp_customize->add_setting('company_tab2_title', [
    'default' => 'COMPANY POSITIONING',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('company_tab2_title', [
    'label' => '标签页 2 标题',
    'section' => 'homepage_company_tabs_section',
    'type' => 'text',
  ]);

  // 标签页 2 - 内容
  $wp_customize->add_setting('company_tab2_content', [
    'default' => 'Renaissance Technologies of Canada Ltd. positions itself as a pioneer in quantitative finance...',
    'sanitize_callback' => 'wp_kses_post',
  ]);
  $wp_customize->add_control('company_tab2_content', [
    'label' => '标签页 2 内容',
    'section' => 'homepage_company_tabs_section',
    'type' => 'textarea',
    'input_attrs' => ['rows' => 8],
  ]);

  // 标签页 3 - 标题
  $wp_customize->add_setting('company_tab3_title', [
    'default' => 'QUANTITATIVE INSTITUTION',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('company_tab3_title', [
    'label' => '标签页 3 标题',
    'section' => 'homepage_company_tabs_section',
    'type' => 'text',
  ]);

  // 标签页 3 - 内容
  $wp_customize->add_setting('company_tab3_content', [
    'default' => 'As a quantitative institution, Renaissance Technologies of Canada Ltd. stands at the forefront of financial innovation...',
    'sanitize_callback' => 'wp_kses_post',
  ]);
  $wp_customize->add_control('company_tab3_content', [
    'label' => '标签页 3 内容',
    'section' => 'homepage_company_tabs_section',
    'type' => 'textarea',
    'input_attrs' => ['rows' => 8],
  ]);

  // === 特性卡片区域 ===
  $wp_customize->add_section('homepage_features_section', [
    'title' => '特性卡片区域',
    'panel' => 'renaissance_homepage_panel',
    'priority' => 25,
  ]);

  // 特性卡片 1 - Safety
  $wp_customize->add_setting('feature1_title', [
    'default' => 'Safety',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature1_title', [
    'label' => '卡片 1 标题',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('feature1_description', [
    'default' => 'Build a reliable foundation of system',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature1_description', [
    'label' => '卡片 1 描述',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  // 特性卡片 2 - Innovation
  $wp_customize->add_setting('feature2_title', [
    'default' => 'Innovation',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature2_title', [
    'label' => '卡片 2 标题',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('feature2_description', [
    'default' => 'Whoever harnesses markets opportunities',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature2_description', [
    'label' => '卡片 2 描述',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  // 特性卡片 3 - Precise
  $wp_customize->add_setting('feature3_title', [
    'default' => 'Precise',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature3_title', [
    'label' => '卡片 3 标题',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('feature3_description', [
    'default' => 'Seizes the margin of a hair within millimetric returns',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature3_description', [
    'label' => '卡片 3 描述',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  // 特性卡片 4 - Excellent
  $wp_customize->add_setting('feature4_title', [
    'default' => 'Excellent',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature4_title', [
    'label' => '卡片 4 标题',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('feature4_description', [
    'default' => 'Elaborate global perspectives and capital power',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('feature4_description', [
    'label' => '卡片 4 描述',
    'section' => 'homepage_features_section',
    'type' => 'text',
  ]);

});

// 获取分类列表用于 Customizer 选择
function rena_get_category_choices() {
  $categories = get_categories([
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
  ]);
  
  $choices = ['' => '-- 所有分类 --'];
  foreach ($categories as $category) {
    $choices[$category->term_id] = $category->name;
  }
  
  return $choices;
}

// 辅助函数：获取主题设置值（带默认值）
function rena_get_option($field_name, $default = '') {
  return get_theme_mod($field_name, $default);
}

// Elementor 额外支持
add_action('elementor/theme/register_locations', function($elementor_theme_manager) {
  $elementor_theme_manager->register_all_core_location();
});

// 为 Elementor 添加自定义宽度支持
add_action('after_setup_theme', function() {
  add_theme_support('elementor', [
    'default_generic_fonts' => true,
    'disable_color_schemes' => false,
    'disable_typography_schemes' => false,
  ]);
});

// Downloads 页面 Customizer 设置
add_action('customize_register', function($wp_customize) {
  // Downloads Page Panel
  $wp_customize->add_panel('downloads_panel', [
    'title' => 'Downloads 页面',
    'priority' => 125,
  ]);

  // Downloads Hero Section
  $wp_customize->add_section('downloads_hero_section', [
    'title' => '顶部区域',
    'panel' => 'downloads_panel',
  ]);

  $wp_customize->add_setting('downloads_hero_category', [
    'default' => 'Encryption',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_hero_category', [
    'label' => '分类标签',
    'section' => 'downloads_hero_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_hero_title', [
    'default' => 'Software & Tools',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_hero_title', [
    'label' => '主标题',
    'section' => 'downloads_hero_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_hero_subtitle', [
    'default' => '"Tools are not the end—they are vessels of thought."',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_hero_subtitle', [
    'label' => '副标题',
    'section' => 'downloads_hero_section',
    'type' => 'text',
  ]);

  // Downloads Main Download Section
  $wp_customize->add_section('downloads_main_section', [
    'title' => '主下载区域',
    'panel' => 'downloads_panel',
  ]);

  $wp_customize->add_setting('downloads_main_badge', [
    'default' => 'Premium Members',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_main_badge', [
    'label' => '徽章文字',
    'section' => 'downloads_main_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_main_title', [
    'default' => 'Financial engineering software package',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_main_title', [
    'label' => '标题',
    'section' => 'downloads_main_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_main_desc', [
    'default' => 'All-around quantitative tool, driving investment innovation',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_main_desc', [
    'label' => '描述',
    'section' => 'downloads_main_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_main_file', [
    'default' => '',
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'downloads_main_file', [
    'label' => '下载文件',
    'section' => 'downloads_main_section',
    'mime_type' => 'application',
  ]));

  $wp_customize->add_setting('downloads_main_button_text', [
    'default' => 'Download',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_main_button_text', [
    'label' => '按钮文字',
    'section' => 'downloads_main_section',
    'type' => 'text',
  ]);

  // Downloads Announcement Section
  $wp_customize->add_section('downloads_announcement_section', [
    'title' => '更新公告区域',
    'panel' => 'downloads_panel',
  ]);

  $wp_customize->add_setting('downloads_announcement_title', [
    'default' => 'Update Announcement and Patch Download',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_announcement_title', [
    'label' => '标题',
    'section' => 'downloads_announcement_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_announcement_desc', [
    'default' => 'Real-time upgrade, ensuring system stability and security',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_announcement_desc', [
    'label' => '描述',
    'section' => 'downloads_announcement_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_announcement_count', [
    'default' => 1,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control('downloads_announcement_count', [
    'label' => '显示文章数量',
    'section' => 'downloads_announcement_section',
    'type' => 'number',
    'input_attrs' => [
      'min' => 1,
      'max' => 5,
    ],
  ]);

  // Downloads Video Tutorials Section
  $wp_customize->add_section('downloads_video_section', [
    'title' => '视频教程区域',
    'panel' => 'downloads_panel',
  ]);

  $wp_customize->add_setting('downloads_video_title', [
    'default' => 'Video Tutorials',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_video_title', [
    'label' => '标题',
    'section' => 'downloads_video_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_video_desc', [
    'default' => 'Comprehensive video guides for all skill levels',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_video_desc', [
    'label' => '描述',
    'section' => 'downloads_video_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_video_count', [
    'default' => 4,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control('downloads_video_count', [
    'label' => '显示视频数量',
    'section' => 'downloads_video_section',
    'type' => 'number',
    'input_attrs' => [
      'min' => 1,
      'max' => 10,
    ],
  ]);

  // Downloads Get Started Section
  $wp_customize->add_section('downloads_getstarted_section', [
    'title' => '底部行动区域',
    'panel' => 'downloads_panel',
  ]);

  $wp_customize->add_setting('downloads_getstarted_category', [
    'default' => 'Encryption',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_getstarted_category', [
    'label' => '分类标签',
    'section' => 'downloads_getstarted_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_getstarted_title', [
    'default' => 'Ready to Get Started?',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_getstarted_title', [
    'label' => '标题',
    'section' => 'downloads_getstarted_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_getstarted_desc', [
    'default' => 'Join our member platform to access all software tools, documentation, and ongoing updates. Premium members receive priority support and early access to new features.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('downloads_getstarted_desc', [
    'label' => '描述',
    'section' => 'downloads_getstarted_section',
    'type' => 'textarea',
  ]);

  $wp_customize->add_setting('downloads_getstarted_login_text', [
    'default' => 'Login to Download',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_getstarted_login_text', [
    'label' => '登录按钮文字',
    'section' => 'downloads_getstarted_section',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('downloads_getstarted_register_text', [
    'default' => 'Create Account',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('downloads_getstarted_register_text', [
    'label' => '注册按钮文字',
    'section' => 'downloads_getstarted_section',
    'type' => 'text',
  ]);
});

// ============================================
// 插件依赖检查和提示
// ============================================
require_once get_template_directory() . '/inc/plugin-dependencies.php';

// ============================================
// ACF 字段注册
// ============================================
require_once get_template_directory() . '/inc/acf-fields.php';

// ============================================
// 自定义登录/注册处理
// ============================================
require_once get_template_directory() . '/inc/auth/login-handler.php';

// ============================================
// 用户自定义字段管理
// ============================================
require_once get_template_directory() . '/inc/user-fields.php';

// ============================================
// 自定义 Gutenberg 区块
// ============================================
require_once get_template_directory() . '/inc/blocks/custom-blocks.php';

// ============================================
// Elementor 自定义小部件
// ============================================
require_once get_template_directory() . '/inc/elementor/widgets-loader.php';

// ============================================
// Elementor 页面预设模板
// ============================================
require_once get_template_directory() . '/inc/elementor-templates.php';

// ============================================
// 默认内容填充（文章、案例、公告、视频）
// ============================================
require_once get_template_directory() . '/inc/default-content.php';

// ============================================
// 多语言内容自动翻译
// ============================================
require_once get_template_directory() . '/inc/multilingual-content.php';

// ============================================
// 后台多语言管理优化
// ============================================
require_once get_template_directory() . '/inc/admin-multilingual.php';
require_once get_template_directory() . '/inc/admin-sortable-posts.php';

// ============================================
// 联系表单 AJAX 处理器
// ============================================
function rena_handle_contact_form() {
  // 验证 nonce（安全令牌）
  check_ajax_referer('rena_contact_form', 'nonce');
  
  // 获取表单数据
  $name = sanitize_text_field($_POST['name']);
  $email = sanitize_email($_POST['email']);
  $message = sanitize_textarea_field($_POST['message']);
  $post_id = intval($_POST['post_id']);
  
  // 验证数据
  if (empty($name) || empty($email) || empty($message)) {
    wp_send_json_error([
      'message' => __('Please fill in all required fields.', 'renaissance')
    ]);
  }
  
  if (!is_email($email)) {
    wp_send_json_error([
      'message' => __('Please enter a valid email address.', 'renaissance')
    ]);
  }
  
  if (strlen($message) < 10) {
    wp_send_json_error([
      'message' => __('Message must be at least 10 characters long.', 'renaissance')
    ]);
  }
  
  // 插入评论作为联系表单提交
  $comment_data = [
    'comment_post_ID' => $post_id,
    'comment_author' => $name,
    'comment_author_email' => $email,
    'comment_content' => $message,
    'comment_type' => 'contact_form', // 标记为联系表单
    'comment_approved' => 0, // 待审核
    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
    'comment_date' => current_time('mysql'),
    'comment_date_gmt' => current_time('mysql', 1),
  ];
  
  $comment_id = wp_insert_comment($comment_data);
  
  if ($comment_id) {
    wp_send_json_success([
      'message' => __('Thank you for your message! We will get back to you within 24-48 hours.', 'renaissance')
    ]);
  } else {
    wp_send_json_error([
      'message' => __('Sorry, there was an error sending your message. Please try again or contact us via email.', 'renaissance')
    ]);
  }
}

add_action('wp_ajax_rena_contact_form', 'rena_handle_contact_form');
add_action('wp_ajax_nopriv_rena_contact_form', 'rena_handle_contact_form');

// ============================================
// 订阅表单 AJAX 处理器
// ============================================
function rena_handle_newsletter_subscription() {
  // 验证 nonce（安全令牌）
  check_ajax_referer('rena_newsletter_subscription', 'nonce');
  
  // 获取表单数据
  $email = sanitize_email($_POST['email']);
  $page_id = intval($_POST['page_id']);
  
  // 验证数据
  if (empty($email)) {
    wp_send_json_error([
      'message' => __('Please enter your email address.', 'renaissance')
    ]);
  }
  
  if (!is_email($email)) {
    wp_send_json_error([
      'message' => __('Please enter a valid email address.', 'renaissance')
    ]);
  }
  
  // 检查是否已经订阅过（同一邮箱在同一页面）
  $existing = get_comments([
    'post_id' => $page_id,
    'author_email' => $email,
    'type' => 'newsletter', // 缩短类型名称（最多20字符）
    'count' => true,
  ]);
  
  if ($existing > 0) {
    wp_send_json_error([
      'message' => __('This email is already subscribed.', 'renaissance')
    ]);
  }
  
  // 验证页面是否存在
  $post = get_post($page_id);
  if (!$post) {
    wp_send_json_error([
      'message' => __('Invalid page ID.', 'renaissance'),
      'debug' => 'Page ID: ' . $page_id
    ]);
  }
  
  // 插入评论作为订阅记录
  $comment_data = [
    'comment_post_ID' => $page_id,
    'comment_author' => 'Newsletter Subscriber',
    'comment_author_email' => $email,
    'comment_author_url' => '',
    'comment_content' => '用户请求订阅 ' . $email,
    'comment_type' => 'newsletter', // 缩短为 newsletter（不超过20字符）
    'comment_approved' => 1, // 自动批准
    'comment_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
    'comment_date' => current_time('mysql'),
    'comment_date_gmt' => current_time('mysql', 1),
    'comment_author_IP' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
    'user_id' => 0,
  ];
  
  $comment_id = wp_insert_comment($comment_data);
  
  if ($comment_id && !is_wp_error($comment_id)) {
    wp_send_json_success([
      'message' => __('Thank you for subscribing! You will receive our weekly newsletter.', 'renaissance')
    ]);
  } else {
    $error_message = is_wp_error($comment_id) ? $comment_id->get_error_message() : 'Unknown error';
    wp_send_json_error([
      'message' => __('Sorry, there was an error processing your subscription. Please try again.', 'renaissance'),
      'debug' => $error_message
    ]);
  }
}

add_action('wp_ajax_rena_newsletter_subscription', 'rena_handle_newsletter_subscription');
add_action('wp_ajax_nopriv_rena_newsletter_subscription', 'rena_handle_newsletter_subscription');

