<?php
/**
 * 后台文章列表拖拽排序功能
 * 
 * @package     Renaissance
 * @subpackage  Admin
 * @author      JiuLingYun
 * @copyright   Copyright (c) 2025 JiuLingYun (https://www.jiulingyun.cn)
 * @license     GPL v2 or later
 * 
 * 功能：
 * 1. 为所有文章类型添加拖拽排序功能
 * 2. 使用 menu_order 字段保存排序
 * 3. 通过 AJAX 实时保存排序
 */

if (!defined('ABSPATH')) {
  exit;
}

// 只在后台启用
if (!is_admin()) {
  return;
}

/**
 * 1. 为所有文章类型添加排序列（如果不存在）
 */
function rena_add_sortable_column($columns) {
  // 检查当前屏幕
  if (!function_exists('get_current_screen')) {
    return $columns;
  }
  
  $screen = get_current_screen();
  if (!$screen || $screen->base !== 'edit') {
    return $columns;
  }
  
  // 添加排序列在标题之前
  $new_columns = [];
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['menu_order'] = '<span class="dashicons dashicons-menu-alt" title="' . esc_attr__('Drag to reorder', 'renaissance') . '"></span>';
    }
    $new_columns[$key] = $value;
  }
  
  return $new_columns;
}

// 为所有支持的文章类型添加排序列
add_filter('manage_posts_columns', 'rena_add_sortable_column');
add_filter('manage_pages_columns', 'rena_add_sortable_column');
add_filter('manage_case_posts_columns', 'rena_add_sortable_column');
add_filter('manage_announcement_posts_columns', 'rena_add_sortable_column');
add_filter('manage_video_posts_columns', 'rena_add_sortable_column');
add_filter('manage_scientist_posts_columns', 'rena_add_sortable_column');

/**
 * 2. 显示排序列内容（拖拽手柄）
 */
function rena_show_sortable_column($column, $post_id) {
  if ($column !== 'menu_order') {
    return;
  }
  
  $menu_order = get_post_field('menu_order', $post_id);
  echo '<span class="rena-sort-handle" data-post-id="' . esc_attr($post_id) . '" data-menu-order="' . esc_attr($menu_order) . '">';
  echo '<span class="dashicons dashicons-menu-alt2" style="color: #666; cursor: move;"></span>';
  echo '</span>';
}

add_action('manage_posts_custom_column', 'rena_show_sortable_column', 10, 2);
add_action('manage_pages_custom_column', 'rena_show_sortable_column', 10, 2);
add_action('manage_case_posts_custom_column', 'rena_show_sortable_column', 10, 2);
add_action('manage_announcement_posts_custom_column', 'rena_show_sortable_column', 10, 2);
add_action('manage_video_posts_custom_column', 'rena_show_sortable_column', 10, 2);
add_action('manage_scientist_posts_custom_column', 'rena_show_sortable_column', 10, 2);

/**
 * 3. 设置排序列样式
 */
function rena_sortable_column_style() {
  if (!function_exists('get_current_screen')) {
    return;
  }
  
  $screen = get_current_screen();
  if (!$screen || $screen->base !== 'edit') {
    return;
  }
  
  $post_types = ['post', 'page', 'case', 'announcement', 'video', 'scientist'];
  if (!in_array($screen->post_type, $post_types)) {
    return;
  }
  
  echo '<style>
    .column-menu_order {
      width: 50px;
      text-align: center;
    }
    .rena-sort-handle {
      display: inline-block;
      cursor: move;
      padding: 5px;
    }
    .rena-sort-handle:hover .dashicons {
      color: #2271b1 !important;
    }
    .wp-list-table tbody tr.ui-sortable-helper {
      background-color: #f0f0f1;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .wp-list-table tbody tr.ui-sortable-placeholder {
      height: 38px;
      background-color: #f0f6fc;
      border: 2px dashed #2271b1;
      visibility: visible !important;
    }
    .rena-sort-saving {
      opacity: 0.5;
      pointer-events: none;
    }
    .rena-sort-message {
      display: none;
      padding: 10px;
      margin: 10px 0;
      background: #fff;
      border-left: 4px solid #00a32a;
      box-shadow: 0 1px 1px rgba(0,0,0,0.04);
    }
    .rena-sort-message.show {
      display: block;
    }
  </style>';
}

add_action('admin_head', 'rena_sortable_column_style');

/**
 * 4. 加载排序脚本和样式
 */
function rena_enqueue_sortable_scripts($hook) {
  // 只在文章列表页面加载
  if ($hook !== 'edit.php') {
    return;
  }
  
  if (!function_exists('get_current_screen')) {
    return;
  }
  
  $screen = get_current_screen();
  if (!$screen) {
    return;
  }
  
  $post_types = ['post', 'page', 'case', 'announcement', 'video', 'scientist'];
  if (!in_array($screen->post_type, $post_types)) {
    return;
  }
  
  // 加载 jQuery UI Sortable（WordPress 已包含）
  wp_enqueue_script('jquery-ui-sortable');
  
  // 加载自定义排序脚本
  wp_enqueue_script(
    'rena-sortable-posts',
    get_template_directory_uri() . '/assets/js/admin-sortable-posts.js',
    ['jquery', 'jquery-ui-sortable'],
    '1.0.0',
    true
  );
  
  // 传递数据到 JavaScript
  wp_localize_script('rena-sortable-posts', 'renaSortable', [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('rena_sort_posts_nonce'),
    'postType' => $screen->post_type,
    'strings' => [
      'saving' => __('Saving order...', 'renaissance'),
      'saved' => __('Order saved successfully!', 'renaissance'),
      'error' => __('Failed to save order. Please try again.', 'renaissance'),
    ],
  ]);
}

add_action('admin_enqueue_scripts', 'rena_enqueue_sortable_scripts');

/**
 * 5. AJAX 处理排序保存
 */
function rena_save_post_order() {
  // 验证 nonce
  check_ajax_referer('rena_sort_posts_nonce', 'nonce');
  
  // 检查权限
  if (!current_user_can('edit_posts')) {
    wp_send_json_error(['message' => __('Insufficient permissions.', 'renaissance')]);
  }
  
  $post_ids = isset($_POST['post_ids']) ? array_map('intval', $_POST['post_ids']) : [];
  $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
  
  if (empty($post_ids) || empty($post_type)) {
    wp_send_json_error(['message' => __('Invalid data.', 'renaissance')]);
  }
  
  // 验证文章类型
  $allowed_types = ['post', 'page', 'case', 'announcement', 'video', 'scientist'];
  if (!in_array($post_type, $allowed_types)) {
    wp_send_json_error(['message' => __('Invalid post type.', 'renaissance')]);
  }
  
  // 更新每个文章的 menu_order
  foreach ($post_ids as $index => $post_id) {
    $menu_order = $index + 1;
    wp_update_post([
      'ID' => $post_id,
      'menu_order' => $menu_order,
    ]);
  }
  
  wp_send_json_success([
    'message' => __('Order saved successfully!', 'renaissance'),
    'count' => count($post_ids),
  ]);
}

add_action('wp_ajax_rena_save_post_order', 'rena_save_post_order');

/**
 * 6. 确保列表按 menu_order 排序显示（用于初始加载）
 * 注意：此功能已在 functions.php 中的 rena_admin_posts_orderby 函数中实现
 * 这里保留用于向后兼容，但如果 functions.php 中已有实现，这里不会重复执行
 */

