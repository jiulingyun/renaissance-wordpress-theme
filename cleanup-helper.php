<?php
/**
 * 内容清理助手页面
 * 访问此文件来清理所有内容
 * 使用方式: http://localhost:8080/wp-content/themes/renaissance/cleanup-helper.php?confirm=yes
 */

// 加载 WordPress
require_once('../../../wp-load.php');

// 检查是否是管理员
if (!current_user_can('administrator')) {
    die('只有管理员可以执行此操作');
}

// 检查确认参数
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>内容清理工具</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
            .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .btn { display: inline-block; padding: 12px 24px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; }
            .btn:hover { background: #c82333; }
            .cancel { background: #6c757d; margin-left: 10px; }
            .cancel:hover { background: #5a6268; }
        </style>
    </head>
    <body>
        <h1>⚠️ 内容清理工具</h1>
        <div class="warning">
            <h2>警告</h2>
            <p>此操作将删除以下所有内容：</p>
            <ul>
                <li>所有页面（Page）</li>
                <li>所有文章（Post）</li>
                <li>所有案例（Case）</li>
                <li>所有公告（Announcement）</li>
                <li>所有视频（Video）</li>
                <li>所有科学家（Scientist）</li>
                <li>所有菜单</li>
                <li>所有分类（保留默认分类）</li>
                <li>所有标签</li>
            </ul>
            <p><strong>此操作不可逆！</strong></p>
        </div>
        <a href="?confirm=yes" class="btn" onclick="return confirm('确定要删除所有内容吗？此操作不可逆！')">确认删除所有内容</a>
        <a href="<?php echo admin_url(); ?>" class="btn cancel">取消</a>
    </body>
    </html>
    <?php
    exit;
}

// 开始清理
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>清理进度</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .progress { background: white; padding: 20px; border-radius: 8px; margin: 10px 0; }
        .done { color: #28a745; }
    </style>
</head>
<body>
    <h1>清理进度</h1>
    <div class="progress">
<?php

echo "<p>🗑️ 删除所有页面（所有语言）...</p>";
flush();
$pages = get_posts(['post_type' => 'page', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '']);
foreach ($pages as $page_id) {
    wp_delete_post($page_id, true);
}
echo "<p class='done'>✓ 已删除 " . count($pages) . " 个页面</p>";
flush();

echo "<p>🗑️ 删除所有文章（所有语言）...</p>";
flush();
$posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '']);
foreach ($posts as $post_id) {
    wp_delete_post($post_id, true);
}
echo "<p class='done'>✓ 已删除 " . count($posts) . " 篇文章</p>";
flush();

echo "<p>🗑️ 删除所有案例（所有语言）...</p>";
flush();
$cases = get_posts(['post_type' => 'case', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '']);
foreach ($cases as $case_id) {
    wp_delete_post($case_id, true);
}
echo "<p class='done'>✓ 已删除 " . count($cases) . " 篇案例</p>";
flush();

echo "<p>🗑️ 删除所有公告（所有语言）...</p>";
flush();
$announcements = get_posts(['post_type' => 'announcement', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '']);
foreach ($announcements as $announcement_id) {
    wp_delete_post($announcement_id, true);
}
echo "<p class='done'>✓ 已删除 " . count($announcements) . " 篇公告</p>";
flush();

echo "<p>🗑️ 删除所有视频（所有语言）...</p>";
flush();
$videos = get_posts(['post_type' => 'video', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '']);
foreach ($videos as $video_id) {
    wp_delete_post($video_id, true);
}
echo "<p class='done'>✓ 已删除 " . count($videos) . " 篇视频</p>";
flush();

echo "<p>🗑️ 删除所有科学家（所有语言）...</p>";
flush();
$scientists = get_posts(['post_type' => 'scientist', 'posts_per_page' => -1, 'fields' => 'ids', 'lang' => '']);
foreach ($scientists as $scientist_id) {
    wp_delete_post($scientist_id, true);
}
echo "<p class='done'>✓ 已删除 " . count($scientists) . " 个科学家</p>";
flush();

echo "<p>🗑️ 删除所有分类...</p>";
flush();
$categories = get_categories(['hide_empty' => false]);
$deleted_cats = 0;
foreach ($categories as $category) {
    if ($category->term_id != 1) {
        wp_delete_category($category->term_id);
        $deleted_cats++;
    }
}
echo "<p class='done'>✓ 已删除 $deleted_cats 个分类</p>";
flush();

echo "<p>🗑️ 删除所有菜单...</p>";
flush();
$menus = wp_get_nav_menus();
foreach ($menus as $menu) {
    wp_delete_nav_menu($menu->term_id);
}
echo "<p class='done'>✓ 已删除 " . count($menus) . " 个菜单</p>";
flush();

echo "<p>🗑️ 删除所有标签...</p>";
flush();
$tags = get_tags(['hide_empty' => false]);
foreach ($tags as $tag) {
    wp_delete_term($tag->term_id, 'post_tag');
}
echo "<p class='done'>✓ 已删除 " . count($tags) . " 个标签</p>";
flush();

?>
    </div>
    <h2 style="color: #28a745;">✅ 所有内容已清理完成！</h2>
    <p><a href="<?php echo admin_url('themes.php'); ?>" style="display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">返回主题页面</a></p>
    <p style="color: #666;">提示: 切换主题再切回 Renaissance 来测试自动创建功能</p>
</body>
</html>

