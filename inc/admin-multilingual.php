<?php
/**
 * 后台多语言管理优化
 * 
 * @package     Renaissance
 * @subpackage  Admin
 * @author      JiuLingYun
 * @copyright   Copyright (c) 2025 JiuLingYun (https://www.jiulingyun.cn)
 * @license     GPL v2 or later
 * 
 * 功能：
 * 1. 在文章/页面列表添加语言列
 * 2. 添加语言筛选器
 * 3. 优化列表显示（颜色标记、语言标志）
 * 4. 添加快捷编辑链接
 */

if (!defined('ABSPATH')) {
  exit;
}

// 只在后台启用
if (!is_admin()) {
  return;
}

// 检查 Polylang 是否激活
if (!function_exists('pll_current_language')) {
  return;
}

/**
 * 1. 为科学家文章类型添加头像列
 */
function rena_add_scientist_avatar_column($columns) {
  // 在标题列之前插入头像列
  $new_columns = [];
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['scientist_avatar'] = __('Avatar', 'renaissance');
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}

add_filter('manage_scientist_posts_columns', 'rena_add_scientist_avatar_column');

/**
 * 2. 显示科学家头像
 */
function rena_show_scientist_avatar_column($column, $post_id) {
  if ($column !== 'scientist_avatar') {
    return;
  }
  
  $thumbnail_id = get_post_thumbnail_id($post_id);
  if ($thumbnail_id) {
    $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'thumbnail');
    if ($thumbnail_url) {
      echo '<img src="' . esc_url($thumbnail_url[0]) . '" alt="' . esc_attr(get_the_title($post_id)) . '" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">';
    } else {
      echo '<span style="color: #999;">—</span>';
    }
  } else {
    echo '<span style="color: #999;">—</span>';
  }
}

add_action('manage_scientist_posts_custom_column', 'rena_show_scientist_avatar_column', 10, 2);

/**
 * 3. 设置头像列宽度
 */
function rena_scientist_avatar_column_width() {
  echo '<style>
    .column-scientist_avatar {
      width: 70px;
      text-align: center;
    }
  </style>';
}

add_action('admin_head', 'rena_scientist_avatar_column_width');

/**
 * 4. 添加语言筛选器（使用 Polylang 默认语言）
 */
function rena_add_language_filter($post_type) {
  // 只在文章列表页面显示
  if (!in_array($post_type, ['post', 'page', 'case', 'announcement', 'video', 'scientist'])) {
    return;
  }
  
  $languages = pll_languages_list(['fields' => '']);
  if (empty($languages)) {
    return;
  }
  
  // 获取 Polylang 的默认语言
  $default_lang = pll_default_language();
  
  // 默认选中 Polylang 默认语言，除非用户手动选择了其他语言
  $current_lang = isset($_GET['lang_filter']) ? sanitize_text_field($_GET['lang_filter']) : $default_lang;
  
  echo '<select name="lang_filter" id="lang_filter">';
  
  // "All Languages" 选项
  $all_selected = ($current_lang === '') ? ' selected="selected"' : '';
  echo '<option value=""' . $all_selected . '>' . esc_html__('All Languages', 'renaissance') . '</option>';
  
  // 各个语言选项
  foreach ($languages as $language) {
    $selected = ($current_lang === $language->slug) ? ' selected="selected"' : '';
    $flag_url = $language->flag_url ?? '';
    
    echo '<option value="' . esc_attr($language->slug) . '"' . $selected . '>';
    echo esc_html($language->name);
    echo '</option>';
  }
  
  echo '</select>';
}

add_action('restrict_manage_posts', 'rena_add_language_filter');

/**
 * 5. 处理语言筛选（默认显示 Polylang 默认语言）
 */
function rena_filter_posts_by_language($query) {
  global $pagenow, $typenow;
  
  if (!is_admin() || $pagenow !== 'edit.php') {
    return;
  }
  
  if (!in_array($typenow, ['post', 'page', 'case', 'announcement', 'video', 'scientist'])) {
    return;
  }
  
  // 如果用户选择了特定语言
  if (isset($_GET['lang_filter'])) {
    $lang = sanitize_text_field($_GET['lang_filter']);
    
    // 空值表示显示所有语言
    if ($lang === '') {
      // 不设置语言过滤，显示所有
      return;
    }
    
    // 设置为选定的语言
    $query->set('lang', $lang);
  } else {
    // 默认显示 Polylang 的默认语言
    $default_lang = pll_default_language();
    if ($default_lang) {
      $query->set('lang', $default_lang);
    }
  }
}

add_filter('parse_query', 'rena_filter_posts_by_language');

/**
 * 6. 在标题中添加语言标记
 */
function rena_add_language_badge_to_title($title, $post_id) {
  // 只在后台列表页面显示
  // 使用 function_exists 检查 get_current_screen 是否可用
  if (!is_admin() || !function_exists('get_current_screen')) {
    return $title;
  }
  
  $screen = get_current_screen();
  if (!$screen || !in_array($screen->base ?? '', ['edit'])) {
    return $title;
  }
  
  $lang = pll_get_post_language($post_id);
  if (!$lang) {
    return $title;
  }
  
  $lang_badges = [
    'en' => '<span class="rena-lang-badge rena-lang-en">EN</span>',
    'zh' => '<span class="rena-lang-badge rena-lang-zh">中文</span>',
    'fr' => '<span class="rena-lang-badge rena-lang-fr">FR</span>',
  ];
  
  $badge = $lang_badges[$lang] ?? '<span class="rena-lang-badge">' . strtoupper($lang) . '</span>';
  
  return $badge . ' ' . $title;
}

add_filter('the_title', 'rena_add_language_badge_to_title', 10, 2);

/**
 * 7. 添加"编辑所有翻译"快捷链接
 */
function rena_add_edit_translations_link($actions, $post) {
  $translations = pll_get_post_translations($post->ID);
  
  if (count($translations) <= 1) {
    return $actions;
  }
  
  $languages = pll_languages_list(['fields' => '']);
  $links = [];
  
  foreach ($translations as $lang => $trans_id) {
    if ($trans_id == $post->ID) continue;
    
    $lang_obj = null;
    foreach ($languages as $language) {
      if ($language->slug === $lang) {
        $lang_obj = $language;
        break;
      }
    }
    
    if ($lang_obj) {
      $edit_url = get_edit_post_link($trans_id);
      $links[] = '<a href="' . esc_url($edit_url) . '">' . esc_html($lang_obj->name) . '</a>';
    }
  }
  
  if (!empty($links)) {
    $actions['edit_translations'] = '<span class="rena-edit-translations">' . 
      __('Edit translations:', 'renaissance') . ' ' . 
      implode(' | ', $links) . 
      '</span>';
  }
  
  return $actions;
}

add_filter('post_row_actions', 'rena_add_edit_translations_link', 10, 2);
add_filter('page_row_actions', 'rena_add_edit_translations_link', 10, 2);

/**
 * 8. 加载后台样式
 */
function rena_admin_multilingual_assets($hook) {
  // 只在文章列表页面加载
  if ($hook !== 'edit.php') {
    return;
  }
  
  wp_enqueue_style(
    'rena-admin-multilingual',
    get_template_directory_uri() . '/assets/css/admin-multilingual.css',
    [],
    '1.4.8'
  );
}

add_action('admin_enqueue_scripts', 'rena_admin_multilingual_assets');

/**
 * 9. 允许标题列渲染 HTML（用于语言徽章）
 */
function rena_allow_html_in_title($safe_text, $text) {
  // 只在后台列表页面允许 HTML
  // 使用 function_exists 检查 get_current_screen 是否可用
  if (is_admin() && function_exists('get_current_screen')) {
    $screen = get_current_screen();
    if ($screen && $screen->base === 'edit') {
      return $text;
    }
  }
  return $safe_text;
}

add_filter('esc_html', 'rena_allow_html_in_title', 10, 2);

