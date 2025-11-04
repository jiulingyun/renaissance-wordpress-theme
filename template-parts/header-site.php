<?php
$theme_uri = get_template_directory_uri();
$site_logo = get_theme_mod('site_logo', $theme_uri . '/assets/img/logo.svg');
?>
<!-- 导航栏 -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo esc_url($site_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav mx-auto',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'walker'         => new class extends Walker_Nav_Menu {
                        function start_lvl(&$output, $depth = 0, $args = null) {
                            $output .= '<ul class="dropdown-menu">';
                        }
                        function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                            $classes = empty($item->classes) ? [] : (array) $item->classes;
                            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
                            $class_names = $class_names ? ' class="nav-item ' . esc_attr($class_names) . '"' : ' class="nav-item"';
                            
                            $output .= '<li' . $class_names . '>';
                            
                            $atts = [];
                            $atts['href'] = !empty($item->url) ? $item->url : '';
                            $atts['class'] = 'nav-link';
                            if ($item->current) $atts['class'] .= ' active';
                            
                            $attributes = '';
                            foreach ($atts as $attr => $value) {
                                if (!empty($value)) {
                                    $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
                                }
                            }
                            
                            $title = apply_filters('the_title', $item->title, $item->ID);
                            $item_output = '<a' . $attributes . '>' . esc_html($title) . '</a>';
                            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                        }
                    }
                ]);
            } else {
                // Fallback menu
                echo '<ul class="navbar-nav mx-auto">';
                echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '" data-translate="nav-home">' . esc_html__('Home', 'renaissance') . '</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/research')) . '" data-translate="nav-research">' . esc_html__('Research', 'renaissance') . '</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/downloads')) . '" data-translate="nav-download">' . esc_html__('Downloads', 'renaissance') . '</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(wp_login_url()) . '" data-translate="nav-member">' . esc_html__('Member', 'renaissance') . '</a></li>';
                echo '</ul>';
            }
            ?>

            <!-- 语言选择下拉菜单 -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <?php
                    // 获取当前语言的国旗和显示文本
                    $current_lang_text = 'EN'; // 默认值
                    $current_lang_flag = ''; // 国旗 URL
                    
                    if (function_exists('pll_current_language')) {
                        $current_lang = pll_current_language('slug');
                        $current_lang_name = pll_current_language('name');
                        // 显示语言代码的大写形式
                        $current_lang_text = strtoupper($current_lang);
                        
                        // 获取当前语言的国旗
                        if (function_exists('pll_languages_list')) {
                            $languages = pll_languages_list(['fields' => '']);
                            foreach ($languages as $language) {
                                if ($language->slug === $current_lang) {
                                    $current_lang_flag = $language->flag_url ?? '';
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if ($current_lang_flag) : ?>
                            <img src="<?php echo esc_url($current_lang_flag); ?>" alt="<?php echo esc_attr($current_lang_text); ?>" style="width: 20px; height: 14px; margin-right: 5px; vertical-align: middle;">
                        <?php else : ?>
                            <i class="bi bi-globe"></i>
                        <?php endif; ?>
                        <?php echo esc_html($current_lang_text); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <?php if (function_exists('pll_the_languages')) : ?>
                            <?php
                            // 获取所有语言
                            $languages = pll_the_languages([
                                'raw' => 1,
                                'hide_if_empty' => 0,  // 即使没有翻译也显示
                                'show_flags' => 0,
                                'show_names' => 1,
                                'hide_current' => 0,   // 显示当前语言
                            ]);
                            
                            if ($languages && is_array($languages)) {
                                foreach ($languages as $lang) :
                                    // 如果当前页面有翻译，使用翻译链接；否则使用该语言的首页
                                    $lang_url = !empty($lang['url']) ? $lang['url'] : (function_exists('pll_home_url') ? pll_home_url($lang['slug']) : home_url('/'));
                                    $flag_url = $lang['flag'] ?? '';
                            ?>
                                    <li>
                                        <a class="dropdown-item<?php echo !empty($lang['current_lang']) ? ' active' : ''; ?>" href="<?php echo esc_url($lang_url); ?>" data-lang="<?php echo esc_attr($lang['slug']); ?>">
                                            <?php if ($flag_url) : ?>
                                                <img src="<?php echo esc_url($flag_url); ?>" alt="<?php echo esc_attr($lang['name']); ?>" style="width: 20px; height: 14px; margin-right: 8px; vertical-align: middle;">
                                            <?php endif; ?>
                                            <?php echo esc_html($lang['name']); ?>
                                        </a>
                                    </li>
                            <?php 
                                endforeach;
                            } else {
                                // 如果 pll_the_languages 返回空，尝试直接获取语言列表
                                if (function_exists('pll_languages_list')) {
                                    $lang_slugs = pll_languages_list(['fields' => 'slug']);
                                    $lang_names = pll_languages_list(['fields' => 'name']);
                                    $all_languages = pll_languages_list(['fields' => '']);
                                    
                                    foreach ($lang_slugs as $index => $slug) :
                                        $lang_url = function_exists('pll_home_url') ? pll_home_url($slug) : home_url('/');
                                        $is_current = function_exists('pll_current_language') && pll_current_language('slug') === $slug;
                                        
                                        // 获取国旗 URL
                                        $flag_url = '';
                                        foreach ($all_languages as $language) {
                                            if ($language->slug === $slug) {
                                                $flag_url = $language->flag_url ?? '';
                                                break;
                                            }
                                        }
                            ?>
                                        <li>
                                            <a class="dropdown-item<?php echo $is_current ? ' active' : ''; ?>" href="<?php echo esc_url($lang_url); ?>" data-lang="<?php echo esc_attr($slug); ?>">
                                                <?php if ($flag_url) : ?>
                                                    <img src="<?php echo esc_url($flag_url); ?>" alt="<?php echo esc_attr($lang_names[$index]); ?>" style="width: 20px; height: 14px; margin-right: 8px; vertical-align: middle;">
                                                <?php endif; ?>
                                                <?php echo esc_html($lang_names[$index]); ?>
                                            </a>
                                        </li>
                            <?php 
                                    endforeach;
                                }
                            }
                            ?>
                        <?php else : ?>
                            <li><a class="dropdown-item" href="#" data-lang="en">EN</a></li>
                            <li><a class="dropdown-item" href="#" data-lang="zh">中文</a></li>
                            <li><a class="dropdown-item" href="#" data-lang="fr">Français</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
