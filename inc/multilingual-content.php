<?php
/**
 * 多语言内容自动创建
 * 为英语预设内容自动创建中文和法语翻译
 */

if (!defined('ABSPATH')) {
    exit;
}

// 主题激活时创建多语言翻译（优先级 50，确保在所有内容创建后）
add_action('after_switch_theme', 'rena_create_multilingual_content', 50);

function rena_create_multilingual_content() {
    // 检查 Polylang 是否激活
    if (!function_exists('pll_set_post_language') || !function_exists('pll_save_post_translations')) {
        return;
    }

    // 检查是否已配置语言
    if (!function_exists('pll_languages_list')) {
        return;
    }
    
    $languages = pll_languages_list();
    if (empty($languages)) {
        return; // 没有配置任何语言
    }
    
    // 检查是否有中文或法语（至少有一个）
    $has_zh = in_array('zh', $languages);
    $has_de = in_array('fr', $languages);
    
    if (!$has_zh && !$has_de) {
        return; // 既没有中文也没有法语，跳过
    }

    // 检查是否已创建过内容翻译（通过检查是否已有中文案例）
    $zh_cases = get_posts([
        'post_type' => 'case',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'lang' => 'zh',
    ]);
    
    $content_translated = !empty($zh_cases);
    
    // 检查是否已创建过菜单翻译
    $zh_menu = wp_get_nav_menu_object('Primary Navigation - 中文');
    $menu_translated = ($zh_menu !== false);

    // 1. 为所有英语文章创建中文和法语翻译
    if (!$content_translated) {
        rena_translate_posts();
        rena_translate_cases();
        rena_translate_announcements();
        rena_translate_videos();
        rena_translate_scientists();
        
        // 2. 为页面创建翻译
        rena_translate_pages();
        
        // 3. 为分类创建翻译
        rena_translate_categories();
    }
    
    // 4. 为菜单创建翻译（独立检查）
    if (!$menu_translated) {
        rena_translate_menus();
    }

    // 不再使用标记选项
}

// 翻译媒体报道文章
function rena_translate_posts() {
    $posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => -1,
        'lang' => 'en',
    ]);

    $translations_data = [
        [
            'zh' => [
                'title' => '荣获《金融时报》"最具创新性量化投资公司"奖',
                'content' => '我们凭借突破性的算法交易策略和卓越的量化金融表现获得认可，为行业创新和卓越树立了新标准。我们的团队开发了专有的数学模型，持续优于传统投资策略。',
                'excerpt' => '我们凭借突破性的算法交易策略和卓越的量化金融表现获得认可，为行业创新和卓越树立了新标准。',
            ],
            'fr' => [
                'title' => 'Ausgezeichnet als "Innovativstes quantitatives Investmentunternehmen" von Financial Times',
                'content' => 'Anerkennung für unsere bahnbrechenden algorithmischen Handelsstrategien und außergewöhnliche Leistung im quantitativen Finanzbereich, die neue Branchenstandards für Innovation und Exzellenz setzt.',
                'excerpt' => 'Anerkennung für unsere bahnbrechenden algorithmischen Handelsstrategien und außergewöhnliche Leistung im quantitativen Finanzbereich.',
            ],
        ],
        [
            'zh' => [
                'title' => '先进神经网络实时处理数百万市场数据点以实现最优投资决策',
                'content' => '我们的尖端AI技术以前所未有的速度和准确性分析大量金融数据，实现卓越的市场预测和风险评估能力。系统每天处理全球市场超过1亿个数据点。',
                'excerpt' => '我们的尖端AI技术以前所未有的速度和准确性分析大量金融数据，实现卓越的市场预测和风险评估能力。',
            ],
            'fr' => [
                'title' => 'Fortschrittliche neuronale Netze verarbeiten Millionen von Marktdatenpunkten in Echtzeit',
                'content' => 'Unsere hochmoderne KI-Technologie analysiert riesige Mengen an Finanzdaten mit beispielloser Geschwindigkeit und Genauigkeit und ermöglicht überlegene Marktprognosen.',
                'excerpt' => 'Unsere hochmoderne KI-Technologie analysiert riesige Mengen an Finanzdaten mit beispielloser Geschwindigkeit und Genauigkeit.',
            ],
        ],
    ];

    foreach ($posts as $index => $post) {
        if (!isset($translations_data[$index])) continue;
        
        $translations = ['en' => $post->ID];
        
        // 创建中文翻译
        if (isset($translations_data[$index]['zh'])) {
            $zh_id = rena_create_translation($post, 'zh', $translations_data[$index]['zh']);
            if ($zh_id) {
                $translations['zh'] = $zh_id;
            }
        }
        
        // 创建法语翻译
        if (isset($translations_data[$index]['fr'])) {
            $fr_id = rena_create_translation($post, 'fr', $translations_data[$index]['fr']);
            if ($fr_id) {
                $translations['fr'] = $fr_id;
            }
        }
        
        // 关联翻译
        pll_save_post_translations($translations);
    }
}

// 翻译案例
function rena_translate_cases() {
    $cases = get_posts([
        'post_type' => 'case',
        'posts_per_page' => -1,
        'lang' => 'en',
    ]);

    $translations_data = [
        [
            'zh' => [
                'title' => 'Medallion基金 - 史上最成功的对冲基金',
                'content' => '通过数学模型和数据驱动策略革命华尔街的传奇量化基金。自1988年成立以来，Medallion基金在扣除费用前实现了平均66%的年回报率，成为历史上最成功的对冲基金。',
                'excerpt' => '通过数学模型和数据驱动策略革命华尔街的传奇量化基金',
            ],
            'fr' => [
                'title' => 'Medallion Fund - Der erfolgreichste Hedgefonds der Geschichte',
                'content' => 'Der legendäre quantitative Fonds, der die Wall Street mit mathematischen Modellen und datengetriebenen Strategien revolutionierte.',
                'excerpt' => 'Der legendäre quantitative Fonds, der die Wall Street revolutionierte',
            ],
        ],
        [
            'zh' => [
                'title' => '量化模型捕捉宏观波动机会',
                'content' => '通过跨市场中性策略在2008年金融危机期间表现卓越。我们的量化模型在市场动荡中发现机会，在传统基金遭受重大损失时产生正收益。',
                'excerpt' => '通过跨市场中性策略在2008年金融危机期间表现卓越',
            ],
            'fr' => [
                'title' => 'Quantitative Modelle erfassen Makro-Volatilitätschancen',
                'content' => 'Außergewöhnliche Performance während der Finanzkrise 2008 durch markt-neutrale Strategien.',
                'excerpt' => 'Außergewöhnliche Performance während der Finanzkrise 2008',
            ],
        ],
        [
            'zh' => [
                'title' => 'AI模型优化选股和风险控制',
                'content' => '先进的深度学习算法和另类数据整合实现卓越的股票表现。我们的AI驱动选股系统分析数千个因素，识别高潜力投资，同时保持严格的风险控制。',
                'excerpt' => '先进的深度学习算法和另类数据整合实现卓越的股票表现',
            ],
            'fr' => [
                'title' => 'KI-Modelle optimieren Aktienauswahl und Risikokontrolle',
                'content' => 'Fortschrittliche Deep-Learning-Algorithmen und alternative Datenintegration für überlegene Aktienperformance.',
                'excerpt' => 'Fortschrittliche Deep-Learning-Algorithmen für überlegene Performance',
            ],
        ],
    ];

    foreach ($cases as $index => $case) {
        if (!isset($translations_data[$index])) continue;
        
        $translations = ['en' => $case->ID];
        
        if (isset($translations_data[$index]['zh'])) {
            $zh_id = rena_create_translation($case, 'zh', $translations_data[$index]['zh']);
            if ($zh_id) $translations['zh'] = $zh_id;
        }
        
        if (isset($translations_data[$index]['fr'])) {
            $fr_id = rena_create_translation($case, 'fr', $translations_data[$index]['fr']);
            if ($fr_id) $translations['fr'] = $fr_id;
        }
        
        pll_save_post_translations($translations);
    }
}

// 翻译公告
function rena_translate_announcements() {
    $announcements = get_posts([
        'post_type' => 'announcement',
        'posts_per_page' => -1,
        'lang' => 'en',
    ]);

    foreach ($announcements as $announcement) {
        $translations = ['en' => $announcement->ID];
        
        // 中文翻译
        $zh_id = rena_create_translation($announcement, 'zh', [
            'title' => 'AI模型增强更新 - 新一代量化交易系统',
            'content' => '<p>我们很高兴地宣布Renaissance Technologies最新的AI模型增强更新正式发布。本次更新引入了下一代机器学习算法，显著提升了市场预测准确性和交易策略的有效性。</p><p>新的算法框架采用了深度神经网络和强化学习技术，能够更好地识别市场模式和趋势变化。经过大量历史数据测试，新模型在各种市场条件下都表现出了卓越的性能。</p>',
            'excerpt' => '我们很高兴地宣布Renaissance Technologies最新的AI模型增强更新正式发布。本次更新引入了下一代机器学习算法，显著提升了市场预测准确性和交易策略的有效性。',
        ]);
        if ($zh_id) $translations['zh'] = $zh_id;
        
        // 法语翻译
        $fr_id = rena_create_translation($announcement, 'fr', [
            'title' => 'KI-Modell-Verbesserung - Quantitatives Handelssystem der nächsten Generation',
            'content' => '<p>Wir freuen uns, die Veröffentlichung des neuesten KI-Modell-Updates von Renaissance Technologies bekannt zu geben. Dieses Update führt Algorithmen der nächsten Generation ein.</p>',
            'excerpt' => 'Wir freuen uns, die Veröffentlichung des neuesten KI-Modell-Updates bekannt zu geben.',
        ]);
        if ($fr_id) $translations['fr'] = $fr_id;
        
        pll_save_post_translations($translations);
    }
}

// 翻译视频
function rena_translate_videos() {
    $videos = get_posts([
        'post_type' => 'video',
        'posts_per_page' => -1,
        'lang' => 'en',
    ]);

    $translations_data = [
        [
            'zh' => ['title' => 'Renaissance平台入门指南', 'excerpt' => '新用户的基本设置和配置指南'],
            'fr' => ['title' => 'Erste Schritte mit Renaissance Platform', 'excerpt' => 'Grundlegende Einrichtung und Konfiguration'],
        ],
        [
            'zh' => ['title' => '高级交易策略', 'excerpt' => '专业交易技术和优化方法'],
            'fr' => ['title' => 'Fortgeschrittene Handelsstrategien', 'excerpt' => 'Professionelle Handelstechniken'],
        ],
        [
            'zh' => ['title' => '风险管理基础', 'excerpt' => '基本的风险控制和投资组合管理技术'],
            'fr' => ['title' => 'Grundlagen des Risikomanagements', 'excerpt' => 'Wesentliche Risikokontrolle'],
        ],
        [
            'zh' => ['title' => '实时交易演示', 'excerpt' => '实时交易示例和实用见解'],
            'fr' => ['title' => 'Live-Trading-Demonstrationen', 'excerpt' => 'Echtzeit-Handelsbeispiele'],
        ],
    ];

    foreach ($videos as $index => $video) {
        if (!isset($translations_data[$index])) continue;
        
        $translations = ['en' => $video->ID];
        
        // 获取英语文章的内容和视频
        $en_content = get_post_field('post_content', $video->ID);
        
        if (isset($translations_data[$index]['zh'])) {
            $zh_data = $translations_data[$index]['zh'];
            // 提取视频 HTML 部分
            preg_match('/<video.*?<\/video>/s', $en_content, $video_matches);
            $video_html = $video_matches[0] ?? '';
            
            $zh_content = '<p>' . $zh_data['excerpt'] . '的详细说明。</p>' . $video_html;
            
            $zh_id = rena_create_translation($video, 'zh', [
                'title' => $zh_data['title'],
                'content' => $zh_content,
                'excerpt' => $zh_data['excerpt'],
            ]);
            if ($zh_id) $translations['zh'] = $zh_id;
        }
        
        if (isset($translations_data[$index]['fr'])) {
            $fr_data = $translations_data[$index]['fr'];
            preg_match('/<video.*?<\/video>/s', $en_content, $video_matches);
            $video_html = $video_matches[0] ?? '';
            
            $fr_content = '<p>' . $fr_data['excerpt'] . '.</p>' . $video_html;
            
            $fr_id = rena_create_translation($video, 'fr', [
                'title' => $fr_data['title'],
                'content' => $fr_content,
                'excerpt' => $fr_data['excerpt'],
            ]);
            if ($fr_id) $translations['fr'] = $fr_id;
        }
        
        pll_save_post_translations($translations);
    }
}

// 翻译科学家
function rena_translate_scientists() {
    $scientists = get_posts([
        'post_type' => 'scientist',
        'posts_per_page' => -1,
        'lang' => 'en',
    ]);

    $translations_data = [
        [
            'zh' => '美国计算机科学家和量化投资专家，现任Renaissance Technologies联席CEO。',
            'fr' => 'Informaticien américain et expert en investissement quantitatif, actuellement co-PDG de Renaissance Technologies.'
        ],
        [
            'zh' => '长期支持Renaissance Technologies的日常运营和战略事务管理。她拥有坚实的金融和管理背景，精通量化投资流程以及高强度研究环境中的协调和执行。',
            'fr' => 'Soutien à long terme pour les opérations quotidiennes et la gestion des affaires stratégiques chez Renaissance Technologies. Elle possède une solide expérience en finance et en gestion, maîtrisant les processus d\'investissement quantitatif et la coordination et l\'exécution dans des environnements de recherche à haute intensité.'
        ],
        [
            'zh' => '专注于机器学习和深度神经网络在量化投资中的应用，负责智能投资模型优化。',
            'fr' => 'Se spécialise dans l\'application de l\'apprentissage automatique et des réseaux de neurones profonds dans l\'investissement quantitatif, responsable de l\'optimisation des modèles d\'investissement intelligents.'
        ],
        [
            'zh' => '专注于多资产组合优化和风险管理，在衍生品和量化交易方面经验丰富。',
            'fr' => 'Se concentre sur l\'optimisation de portefeuille multi-actifs et la gestion des risques, avec une vaste expérience en produits dérivés et trading quantitatif.'
        ],
        [
            'zh' => '研究投资者行为和市场心理学，使用大数据提高量化策略的准确性。',
            'fr' => 'Recherche le comportement des investisseurs et la psychologie du marché, utilisant le big data pour améliorer la précision des stratégies quantitatives.'
        ],
        [
            'zh' => '专注于全球宏观经济、地缘政治和商品市场分析，为投资决策提供战略支持。',
            'fr' => 'Se spécialise dans l\'analyse macroéconomique mondiale, géopolitique et des marchés de matières premières, fournissant un soutien stratégique pour les décisions d\'investissement.'
        ],
    ];

    foreach ($scientists as $index => $scientist) {
        if (!isset($translations_data[$index])) continue;
        
        $translations = ['en' => $scientist->ID];
        
        // 保留英文标题，只翻译描述
        $zh_id = rena_create_translation($scientist, 'zh', [
            'title' => get_the_title($scientist->ID),
            'excerpt' => $translations_data[$index]['zh'],
        ]);
        if ($zh_id) {
            $translations['zh'] = $zh_id;
            // 复制头像
            $thumbnail_id = get_post_thumbnail_id($scientist->ID);
            if ($thumbnail_id) {
                set_post_thumbnail($zh_id, $thumbnail_id);
            }
        }
        
        $fr_id = rena_create_translation($scientist, 'fr', [
            'title' => get_the_title($scientist->ID),
            'excerpt' => $translations_data[$index]['fr'],
        ]);
        if ($fr_id) {
            $translations['fr'] = $fr_id;
            $thumbnail_id = get_post_thumbnail_id($scientist->ID);
            if ($thumbnail_id) {
                set_post_thumbnail($fr_id, $thumbnail_id);
            }
        }
        
        pll_save_post_translations($translations);
    }
}

// 翻译页面
function rena_translate_pages() {
    $pages_to_translate = [
        'Home' => [
            'zh' => '首页',
            'fr' => 'Accueil',
        ],
        'Research' => [
            'zh' => '研究',
            'fr' => 'Recherche',
        ],
        'Downloads' => [
            'zh' => '下载',
            'fr' => 'Téléchargements',
        ],
        'Contact Information' => [
            'zh' => '联系信息',
            'fr' => 'Informations de contact',
        ],
        'Risk Warning' => [
            'zh' => '风险警告',
            'fr' => 'Avertissement sur les risques',
        ],
        'Privacy Policy' => [
            'zh' => '隐私政策',
            'fr' => 'Politique de confidentialité',
        ],
        'Investor Relations' => [
            'zh' => '投资者关系',
            'fr' => 'Relations investisseurs',
        ],
        'Member' => [
            'zh' => '会员',
            'fr' => 'Membre',
        ],
        'Register' => [
            'zh' => '注册',
            'fr' => 'Inscription',
        ],
        'Forgot Password' => [
            'zh' => '忘记密码',
            'fr' => 'Mot de passe oublié',
        ],
        'My Profile' => [
            'zh' => '我的资料',
            'fr' => 'Mon profil',
        ],
    ];

    foreach ($pages_to_translate as $en_title => $trans) {
        $en_page = get_page_by_title($en_title);
        if (!$en_page) continue;
        
        $translations = ['en' => $en_page->ID];
        
        // 中文翻译
        $zh_id = rena_create_page_translation($en_page, 'zh', $trans['zh']);
        if ($zh_id) {
            $translations['zh'] = $zh_id;
            
            // 为特定页面设置翻译后的 Elementor 数据
            $slug = get_post_field('post_name', $en_page->ID);
            if ($slug === 'home' && function_exists('rena_set_home_elementor_data_zh')) {
                rena_set_home_elementor_data_zh($zh_id);
            } elseif ($slug === 'research' && function_exists('rena_set_research_elementor_data_zh')) {
                rena_set_research_elementor_data_zh($zh_id);
            } elseif ($slug === 'downloads' && function_exists('rena_set_downloads_elementor_data_zh')) {
                rena_set_downloads_elementor_data_zh($zh_id);
            }
        }
        
        // 法语翻译
        $fr_id = rena_create_page_translation($en_page, 'fr', $trans['fr']);
        if ($fr_id) {
            $translations['fr'] = $fr_id;
            
            // 为特定页面设置翻译后的 Elementor 数据
            $slug = get_post_field('post_name', $en_page->ID);
            if ($slug === 'home' && function_exists('rena_set_home_elementor_data_fr')) {
                rena_set_home_elementor_data_fr($fr_id);
            } elseif ($slug === 'research' && function_exists('rena_set_research_elementor_data_fr')) {
                rena_set_research_elementor_data_fr($fr_id);
            } elseif ($slug === 'downloads' && function_exists('rena_set_downloads_elementor_data_fr')) {
                rena_set_downloads_elementor_data_fr($fr_id);
            }
        }
        
        pll_save_post_translations($translations);
    }
}

// 翻译分类
function rena_translate_categories() {
    // Media Reports 分类
    $en_cat = get_term_by('slug', 'media-reports', 'category');
    if ($en_cat) {
        $translations = ['en' => $en_cat->term_id];
        
        $zh_cat = wp_insert_term('媒体报道', 'category', ['slug' => 'media-reports-zh']);
        if (!is_wp_error($zh_cat)) {
            $translations['zh'] = $zh_cat['term_id'];
            pll_set_term_language($zh_cat['term_id'], 'zh');
        }
        
        $fr_cat = wp_insert_term('Medienberichte', 'category', ['slug' => 'media-reports-de']);
        if (!is_wp_error($fr_cat)) {
            $translations['fr'] = $fr_cat['term_id'];
            pll_set_term_language($fr_cat['term_id'], 'fr');
        }
        
        if (function_exists('pll_save_term_translations')) {
            pll_save_term_translations($translations);
        }
    }
}

// 辅助函数：创建文章翻译
function rena_create_translation($original_post, $lang, $data) {
    $new_post = [
        'post_type' => $original_post->post_type,
        'post_title' => $data['title'],
        'post_content' => $data['content'] ?? get_post_field('post_content', $original_post->ID),
        'post_excerpt' => $data['excerpt'] ?? '',
        'post_status' => 'publish',
    ];
    
    $new_id = wp_insert_post($new_post);
    
    if ($new_id && !is_wp_error($new_id)) {
        pll_set_post_language($new_id, $lang);
        
        // 复制特色图片
        $thumbnail_id = get_post_thumbnail_id($original_post->ID);
        if ($thumbnail_id) {
            set_post_thumbnail($new_id, $thumbnail_id);
        }
        
        // 复制标签
        $tags = wp_get_post_tags($original_post->ID, ['fields' => 'names']);
        if ($tags) {
            wp_set_post_tags($new_id, $tags);
        }
        
        return $new_id;
    }
    
    return false;
}

// 辅助函数：创建页面翻译
function rena_create_page_translation($original_page, $lang, $title) {
    // 获取原始页面的 slug
    $original_slug = get_post_field('post_name', $original_page->ID);
    
    // 页面 slug 到 HTML 文件名的映射
    $slug_to_html = [
        'home' => 'home.html',
        'login' => 'login.html',
        'register' => 'register.html',
        'forgot-password' => 'forgot-password.html',
        'profile' => 'profile.html',
        'contact' => 'contact.html',
        'downloads' => 'downloads.html',
        'research' => 'research.html',
        'investor-relations' => 'investor-relations.html',
        'risk-warning' => 'risk-warning.html',
        'privacy-policy' => 'privacy-policy.html',
    ];
    
    // 获取对应的 HTML 文件名
    $html_file = isset($slug_to_html[$original_slug]) ? $slug_to_html[$original_slug] : null;
    
    // 尝试加载翻译后的内容
    $content = get_post_field('post_content', $original_page->ID); // 默认使用原始内容
    
    if ($html_file) {
        // 构建翻译文件路径（例如：login_zh.html, login_de.html）
        $base_name = str_replace('.html', '', $html_file);
        $translated_file = get_template_directory() . '/default-pages/' . $base_name . '_' . $lang . '.html';
        
        // 如果翻译文件存在，使用翻译内容
        if (file_exists($translated_file)) {
            $translated_content = file_get_contents($translated_file);
            if ($translated_content !== false) {
                $content = $translated_content;
            }
        }
    }
    
    $new_page = [
        'post_type' => 'page',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
    ];
    
    $new_id = wp_insert_post($new_page);
    
    if ($new_id && !is_wp_error($new_id)) {
        pll_set_post_language($new_id, $lang);
        
        // 复制页面模板
        $template = get_post_meta($original_page->ID, '_wp_page_template', true);
        if ($template) {
            update_post_meta($new_id, '_wp_page_template', $template);
        }
        
        // Login、Register、Forgot Password 使用传统翻译，不使用 Elementor
        $non_elementor_pages = ['login', 'register', 'forgot-password'];
        $is_non_elementor = in_array($original_slug, $non_elementor_pages);
        
        // 只有非 Elementor 页面才添加 Elementor 标记
        if (!$is_non_elementor) {
            // 标记为 Elementor 可编辑
            update_post_meta($new_id, '_elementor_edit_mode', 'builder');
            update_post_meta($new_id, '_elementor_template_type', 'wp-page');
            update_post_meta($new_id, '_elementor_version', '3.0.0');
        }
        
        // 对于使用 Elementor 的特殊页面（home, research, downloads），
        // 不复制英文的 Elementor 数据，而是由专门的翻译函数设置
        $elementor_pages = ['home', 'research', 'downloads'];
        $should_copy_elementor = !in_array($original_slug, $elementor_pages) && !$is_non_elementor;
        
        if ($should_copy_elementor) {
            // 复制 Elementor 数据（使用 wp_slash 保持格式）
            $elementor_data = get_post_meta($original_page->ID, '_elementor_data', true);
            if ($elementor_data) {
                update_post_meta($new_id, '_elementor_data', wp_slash($elementor_data));
            }
            
            // 复制 Elementor 页面设置
            $elementor_settings = get_post_meta($original_page->ID, '_elementor_page_settings', true);
            if ($elementor_settings) {
                update_post_meta($new_id, '_elementor_page_settings', $elementor_settings);
            }
            
            // 复制其他 Elementor 相关的 meta
            $elementor_css = get_post_meta($original_page->ID, '_elementor_css', true);
            if ($elementor_css) {
                update_post_meta($new_id, '_elementor_css', $elementor_css);
            }
        }
        // 对于 home, research, downloads 页面，Elementor 数据将由
        // rena_translate_pages() 中的专门函数设置
        
        return $new_id;
    }
    
    return false;
}


// 翻译菜单
function rena_translate_menus() {
    if (!function_exists('pll_languages_list')) {
        return;
    }

    // 获取英语的菜单
    $en_primary_menu = wp_get_nav_menu_object('Primary Navigation');
    $en_footer_menu = wp_get_nav_menu_object('Footer Navigation');

    if (!$en_primary_menu || !$en_footer_menu) {
        return;
    }

    // 创建或获取中文菜单
    $zh_primary_menu = wp_get_nav_menu_object('Primary Navigation - 中文');
    if (!$zh_primary_menu) {
        $zh_primary_menu_id = wp_create_nav_menu('Primary Navigation - 中文');
        if (is_wp_error($zh_primary_menu_id)) {
            return;
        }
    } else {
        $zh_primary_menu_id = $zh_primary_menu->term_id;
    }
    
    $zh_footer_menu = wp_get_nav_menu_object('Footer Navigation - 中文');
    if (!$zh_footer_menu) {
        $zh_footer_menu_id = wp_create_nav_menu('Footer Navigation - 中文');
        if (is_wp_error($zh_footer_menu_id)) {
            return;
        }
    } else {
        $zh_footer_menu_id = $zh_footer_menu->term_id;
    }

    // 创建或获取法语菜单
    $fr_primary_menu = wp_get_nav_menu_object('Primary Navigation - Français');
    if (!$fr_primary_menu) {
        $fr_primary_menu_id = wp_create_nav_menu('Primary Navigation - Français');
        if (is_wp_error($fr_primary_menu_id)) {
            return;
        }
    } else {
        $fr_primary_menu_id = $fr_primary_menu->term_id;
    }
    
    $fr_footer_menu = wp_get_nav_menu_object('Footer Navigation - Français');
    if (!$fr_footer_menu) {
        $fr_footer_menu_id = wp_create_nav_menu('Footer Navigation - Français');
        if (is_wp_error($fr_footer_menu_id)) {
            return;
        }
    } else {
        $fr_footer_menu_id = $fr_footer_menu->term_id;
    }

    // 清空中文菜单的现有项目
    $zh_primary_items = wp_get_nav_menu_items($zh_primary_menu_id);
    if ($zh_primary_items) {
        foreach ($zh_primary_items as $item) {
            wp_delete_post($item->ID, true);
        }
    }
    $zh_footer_items = wp_get_nav_menu_items($zh_footer_menu_id);
    if ($zh_footer_items) {
        foreach ($zh_footer_items as $item) {
            wp_delete_post($item->ID, true);
        }
    }
    
    // 清空法语菜单的现有项目
    $fr_primary_items = wp_get_nav_menu_items($fr_primary_menu_id);
    if ($fr_primary_items) {
        foreach ($fr_primary_items as $item) {
            wp_delete_post($item->ID, true);
        }
    }
    $fr_footer_items = wp_get_nav_menu_items($fr_footer_menu_id);
    if ($fr_footer_items) {
        foreach ($fr_footer_items as $item) {
            wp_delete_post($item->ID, true);
        }
    }

    // 为中文菜单添加项目
    rena_create_menu_items($zh_primary_menu_id, 'primary', 'zh');
    rena_create_menu_items($zh_footer_menu_id, 'footer', 'zh');

    // 为法语菜单添加项目
    rena_create_menu_items($fr_primary_menu_id, 'primary', 'fr');
    rena_create_menu_items($fr_footer_menu_id, 'footer', 'fr');

    // 设置菜单的语言并关联翻译
    if (function_exists('pll_set_term_language') && function_exists('pll_save_term_translations')) {
        pll_set_term_language($en_primary_menu->term_id, 'en');
        pll_set_term_language($zh_primary_menu_id, 'zh');
        pll_set_term_language($fr_primary_menu_id, 'fr');
        pll_save_term_translations([
            'en' => $en_primary_menu->term_id,
            'zh' => $zh_primary_menu_id,
            'fr' => $fr_primary_menu_id,
        ]);

        pll_set_term_language($en_footer_menu->term_id, 'en');
        pll_set_term_language($zh_footer_menu_id, 'zh');
        pll_set_term_language($fr_footer_menu_id, 'fr');
        pll_save_term_translations([
            'en' => $en_footer_menu->term_id,
            'zh' => $zh_footer_menu_id,
            'fr' => $fr_footer_menu_id,
        ]);
    }

    // 自动分配菜单到主题位置
    $locations = get_theme_mod('nav_menu_locations', []);
    
    // 为英语设置菜单位置
    $locations['primary'] = $en_primary_menu->term_id;
    $locations['footer'] = $en_footer_menu->term_id;
    
    set_theme_mod('nav_menu_locations', $locations);
    
    // 为 Polylang 设置多语言菜单位置
    if (function_exists('pll_languages_list')) {
        // 获取现有的 Polylang 菜单设置
        $polylang_nav_menus = get_option('polylang_nav_menus', []);
        
        // 确保数据结构正确
        if (!is_array($polylang_nav_menus)) {
            $polylang_nav_menus = [];
        }
        
        // 为每个语言设置菜单位置
        $polylang_nav_menus['en'] = [
            'primary' => $en_primary_menu->term_id,
            'footer' => $en_footer_menu->term_id,
        ];
        
        $polylang_nav_menus['zh'] = [
            'primary' => $zh_primary_menu_id,
            'footer' => $zh_footer_menu_id,
        ];
        
        $polylang_nav_menus['fr'] = [
            'primary' => $fr_primary_menu_id,
            'footer' => $fr_footer_menu_id,
        ];
        
        // 保存 Polylang 菜单位置设置
        update_option('polylang_nav_menus', $polylang_nav_menus);
        
        // 注意：Polylang 的菜单位置需要在后台手动确认一次才能完全生效
        // 这是 Polylang 的设计限制，无法通过代码完全自动化
        // 详见 MENU-SETUP-GUIDE.md 文档
    }
}

// 创建菜单项目
function rena_create_menu_items($menu_id, $menu_type, $lang) {
    if ($menu_type === 'primary') {
        $items_data = [
            'en' => [
                ['title' => 'Home', 'slug' => 'home'],
                ['title' => 'Research', 'slug' => 'research'],
                ['title' => 'Téléchargements', 'slug' => 'downloads'],
                ['title' => 'Member', 'slug' => 'login'],
            ],
            'zh' => [
                ['title' => '首页', 'slug' => 'home'],
                ['title' => '研究', 'slug' => 'research'],
                ['title' => '下载', 'slug' => 'downloads'],
                ['title' => '会员', 'slug' => 'login'],
            ],
            'fr' => [
                ['title' => 'Accueil', 'slug' => 'home'],
                ['title' => 'Recherche', 'slug' => 'research'],
                ['title' => 'Téléchargements', 'slug' => 'downloads'],
                ['title' => 'Membre', 'slug' => 'login'],
            ],
        ];
    } else {
        $items_data = [
            'en' => [
                ['title' => 'Privacy Policy', 'slug' => 'privacy-policy'],
                ['title' => 'Risk Warning', 'slug' => 'risk-warning'],
                ['title' => 'Contact Information', 'slug' => 'contact'],
                ['title' => 'Relations investisseurs', 'slug' => 'investor-relations'],
            ],
            'zh' => [
                ['title' => '隐私政策', 'slug' => 'privacy-policy'],
                ['title' => '风险警告', 'slug' => 'risk-warning'],
                ['title' => '联系信息', 'slug' => 'contact'],
                ['title' => '投资者关系', 'slug' => 'investor-relations'],
            ],
            'fr' => [
                ['title' => 'Politique de confidentialité', 'slug' => 'privacy-policy'],
                ['title' => 'Avertissement sur les risques', 'slug' => 'risk-warning'],
                ['title' => 'Informations de contact', 'slug' => 'contact'],
                ['title' => 'Relations investisseurs', 'slug' => 'investor-relations'],
            ],
        ];
    }

    $menu_items = $items_data[$lang] ?? $items_data['en'];

    foreach ($menu_items as $index => $item) {
        if (isset($item['slug'])) {
            $en_page = get_page_by_path($item['slug']);
            if ($en_page && function_exists('pll_get_post')) {
                $translated_page_id = pll_get_post($en_page->ID, $lang);
                
                if ($translated_page_id) {
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title' => $item['title'],
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $translated_page_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                        'menu-item-position' => $index + 1,
                    ]);
                }
            }
        } else {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title' => $item['title'],
                'menu-item-url' => home_url($item['url']),
                'menu-item-type' => 'custom',
                'menu-item-status' => 'publish',
                'menu-item-position' => $index + 1,
            ]);
        }
    }
}
