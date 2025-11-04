<?php
/**
 * Elementor 页面预设数据
 * 为 Home/Research/Downloads 页面创建完整的 Elementor 布局
 */

if (!defined('ABSPATH')) {
    exit;
}

// Home 页面 Elementor 数据
function rena_set_home_elementor_data($page_id) {
    $elementor_data = [
        // 1. Hero Section
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-hero-section',
                            'settings' => [
                                'title_line1' => 'At the intersection of algorithms and humanity',
                                'title_line2' => 'We see the future of finance',
                                'description' => 'The end of technology is aesthetics, the end of finance is wisdom',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 2. Company Tabs
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-company-tabs',
                            'settings' => [
                                'tab1_title' => 'COMPANY OVERVIEW',
                                'tab1_content' => 'Renaissance Technologies was founded by mathematician James Simons in the 1980s and is hailed as the "pinnacle symbol of quantitative investment." The company reshaped the essence of investment through mathematical models, statistics, and algorithmic logic. Its flagship fund, the Medallion Fund, is renowned in the global capital world for its near-perfect return curve and extremely low drawdown rates. The development history of Renaissance is an epic of the fusion of scientific thought and financial wisdom.',
                                'tab2_title' => 'COMPANY POSITIONING',
                                'tab2_content' => 'Renaissance Technologies of Canada Ltd. positions itself as a pioneer in quantitative finance, dedicated to providing secure, transparent, and sustainable investment solutions for institutional investors and high-net-worth clients. The company adheres to the investment philosophy of "data over intuition, algorithms over emotion," with core strategies managed by a closed quantitative team.',
                                'tab3_title' => 'QUANTITATIVE INSTITUTION',
                                'tab3_content' => 'As a quantitative institution, Renaissance Technologies of Canada Ltd. stands at the forefront of financial innovation. Founded in Toronto with global headquarters in New York, the company serves as a leading quantitative financial research institution worldwide. The company specializes in equity, fund, and gold strategies.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 3. Mission & Vision
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-mission-vision',
                            'settings' => [
                                'title' => 'Mission and Vision',
                                'subtitle' => 'Devoted to reshaping the financial engineering system through artificial intelligence and mathematical models, Based on scientific research and driven by industrial applications',
                            ],
                        ],
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-feature-cards',
                            'settings' => [
                                'card1_title' => 'Safety',
                                'card1_description' => 'Build a reliable foundation of system',
                                'card2_title' => 'Innovation',
                                'card2_description' => 'Whoever harnesses markets opportunities',
                                'card3_title' => 'Precise',
                                'card3_description' => 'Seizes the margin of a hair within millimetric returns',
                                'card4_title' => 'Excellent',
                                'card4_description' => 'Elaborate global perspectives and capital power',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 4. Media Reports Section
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-section-title',
                            'settings' => [
                                'title' => 'Media reports',
                                'subtitle' => 'Focus on cutting-edge technology and capital dynamics',
                                'alignment' => 'center',
                            ],
                        ],
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-media-reports',
                            'settings' => [
                                'posts_per_page' => 3,
                                'category' => '',
                                'orderby' => 'date',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
    update_post_meta($page_id, '_elementor_page_settings', []);
}

// Research 页面 Elementor 数据
function rena_set_research_elementor_data($page_id) {
    $elementor_data = [
        // 1. Research Hero
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-research-hero',
                            'settings' => [
                                'title' => 'Research Excellence',
                                'subtitle' => 'We don\'t pursue short-term "excess returns"—we pursue repeatable, verifiable, and shareable financial scientific structures.',
                                'feature1_title' => 'Complexity to Clarity',
                                'feature1_description' => 'Multi-dimensional modeling to simplify complex financial systems',
                                'feature2_title' => 'Alpha to Insight',
                                'feature2_description' => 'Every alpha model translates to actionable market intelligence',
                                'feature3_title' => 'Ethics in AI Finance',
                                'feature3_description' => 'Responsible implementation and ethical AI applications',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 2. Scientists Section
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-section-title',
                            'settings' => [
                                'title' => 'Scientist',
                                'subtitle' => 'Based on science as the cornerstone, exploring the unknown in finance',
                                'alignment' => 'center',
                            ],
                        ],
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-scientists-list',
                            'settings' => [
                                'posts_per_page' => -1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 3. Cases Section
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-section-title',
                            'settings' => [
                                'title' => 'Successful Cases',
                                'subtitle' => 'With excellent performance, verify the value of the model',
                                'alignment' => 'center',
                            ],
                        ],
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-cases-list',
                            'settings' => [
                                'posts_per_page' => 3,
                                'columns' => '3',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 4. Commercialization Path
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-commercialization-path',
                            'settings' => [
                                'title' => 'Commercialization Path',
                                'subtitle' => 'Our research-to-market pipeline ensures that scientific breakthroughs translate into practical applications. Clients of our models can support publicly traded companies and institutional investors worldwide.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
    update_post_meta($page_id, '_elementor_page_settings', []);
}

// Downloads 页面 Elementor 数据
function rena_set_downloads_elementor_data($page_id) {
    $elementor_data = [
        // 1. Downloads Hero
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-downloads-hero',
                            'settings' => [
                                'category' => 'Encryption',
                                'title' => 'Software & Tools',
                                'subtitle' => '"Tools are not the end—they are vessels of thought."',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 2. Main Download Card
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-main-download-card',
                            'settings' => [
                                'badge' => 'Premium Members',
                                'title' => 'Financial engineering software package',
                                'description' => 'All-around quantitative tool, driving investment innovation',
                                'file_url' => ['url' => '#'],
                                'button_text' => 'Download',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 3. Announcements & Videos (两列)
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                // 左列 - Announcements
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 50],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-section-title',
                            'settings' => [
                                'title' => 'Update Announcement and Patch Download',
                                'subtitle' => 'Real-time upgrade, ensuring system stability and security',
                                'alignment' => 'left',
                            ],
                        ],
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-announcements-list',
                            'settings' => [
                                'posts_per_page' => 1,
                            ],
                        ],
                    ],
                ],
                // 右列 - Video Tutorials
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 50],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-section-title',
                            'settings' => [
                                'title' => 'Video Tutorials',
                                'subtitle' => 'Comprehensive video guides for all skill levels',
                                'alignment' => 'left',
                            ],
                        ],
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-video-tutorials',
                            'settings' => [
                                'posts_per_page' => 4,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        // 4. Get Started CTA
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'rena-get-started-cta',
                            'settings' => [
                                'category' => 'Encryption',
                                'title' => 'Ready to Get Started?',
                                'description' => 'Join our member platform to access all software tools, documentation, and ongoing updates. Premium members receive priority support and early access to new features.',
                                'login_text' => 'Login to Download',
                                'register_text' => 'Create Account',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
    update_post_meta($page_id, '_elementor_page_settings', []);
}


// Home 页面中文版 Elementor 数据
function rena_set_home_elementor_data_zh($page_id) {
    $elementor_data = [
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-hero-section',
                    'settings' => [
                        'title_line1' => '在算法与人性的交汇处',
                        'title_line2' => '我们看到金融的未来',
                        'description' => '技术的尽头是美学，金融的尽头是智慧',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-company-tabs',
                    'settings' => [
                        'tab1_title' => '公司概览',
                        'tab1_content' => 'Renaissance Technologies由数学家James Simons于1980年代创立，被誉为"量化投资的巅峰象征"。',
                        'tab2_title' => '公司定位',
                        'tab2_content' => 'Renaissance Technologies of Canada Ltd.定位为量化金融的先驱。',
                        'tab3_title' => '量化机构',
                        'tab3_content' => '作为量化机构，Renaissance Technologies of Canada Ltd.站在金融创新的前沿。',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'settings' => ['_column_size' => 100],
                'elements' => [
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-mission-vision',
                        'settings' => [
                            'title' => '使命与愿景',
                            'subtitle' => '致力于通过人工智能和数学模型重塑金融工程系统，以科学研究为基础，以产业应用为驱动',
                        ],
                    ],
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-feature-cards',
                        'settings' => [
                            'card1_title' => '安全',
                            'card1_description' => '构建可靠的系统基础',
                            'card2_title' => '创新',
                            'card2_description' => '把握市场机遇',
                            'card3_title' => '精准',
                            'card3_description' => '在毫厘之间把握收益',
                            'card4_title' => '卓越',
                            'card4_description' => '阐述全球视野和资本力量',
                        ],
                    ],
                ],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'settings' => ['_column_size' => 100],
                'elements' => [
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-section-title',
                        'settings' => [
                            'title' => '媒体报道',
                            'subtitle' => '关注前沿技术和资本动态',
                        ],
                    ],
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-media-reports',
                        'settings' => ['posts_per_page' => 3],
                    ],
                ],
            ]],
        ],
    ];
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
}

// Research 页面中文版
function rena_set_research_elementor_data_zh($page_id) {
    $elementor_data = [
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-research-hero',
                    'settings' => [
                        'title' => '研究卓越',
                        'subtitle' => '我们不追求短期的"超额收益"——我们追求可重复、可验证、可分享的金融科学结构。',
                        'feature1_title' => '化繁为简',
                        'feature1_description' => '多维建模简化复杂金融系统',
                        'feature2_title' => 'Alpha到洞察',
                        'feature2_description' => '每个alpha模型都转化为可操作的市场情报',
                        'feature3_title' => 'AI金融伦理',
                        'feature3_description' => '负责任的实施和道德的AI应用',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => '科学家', 'subtitle' => '以科学为基石，探索金融未知']],
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-scientists-list'],
                ],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => '成功案例', 'subtitle' => '以卓越表现验证模型价值']],
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-cases-list', 'settings' => ['posts_per_page' => 3, 'columns' => '3']],
                ],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-commercialization-path',
                    'settings' => [
                        'title' => '商业化路径',
                        'subtitle' => '我们的研究到市场管道确保科学突破转化为实际应用。',
                    ],
                ]],
            ]],
        ],
    ];
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
}

// Downloads 页面中文版
function rena_set_downloads_elementor_data_zh($page_id) {
    $elementor_data = [
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-downloads-hero',
                    'settings' => [
                        'category' => '加密',
                        'title' => '软件与工具',
                        'subtitle' => '"工具不是目的——它们是思想的载体。"',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-main-download-card',
                    'settings' => [
                        'badge' => '高级会员',
                        'title' => '金融工程软件包',
                        'description' => '全方位量化工具，驱动投资创新',
                        'button_text' => '下载',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 50],
                    'elements' => [
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => '更新公告和补丁下载', 'subtitle' => '实时升级，确保系统稳定和安全', 'alignment' => 'left']],
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-announcements-list', 'settings' => ['posts_per_page' => 1]],
                    ],
                ],
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 50],
                    'elements' => [
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => '视频教程', 'subtitle' => '全面的视频指南', 'alignment' => 'left']],
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-video-tutorials', 'settings' => ['posts_per_page' => 4]],
                    ],
                ],
            ],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-get-started-cta',
                    'settings' => [
                        'category' => '加密',
                        'title' => '准备开始了吗？',
                        'description' => '加入我们的会员平台，访问所有软件工具、文档和持续更新。',
                        'login_text' => '登录下载',
                        'register_text' => '创建账户',
                    ],
                ]],
            ]],
        ],
    ];
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
}

// Home 页面德语版 Elementor 数据
function rena_set_home_elementor_data_fr($page_id) {
    $elementor_data = [
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-hero-section',
                    'settings' => [
                        'title_line1' => 'An der Schnittstelle von Algorithmen und Menschlichkeit',
                        'title_line2' => 'Wir sehen die Zukunft der Finanzen',
                        'description' => 'Das Ende der Technologie ist Ästhetik, das Ende der Finanzen ist Weisheit',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-company-tabs',
                    'settings' => [
                        'tab1_title' => 'UNTERNEHMENSÜBERSICHT',
                        'tab1_content' => 'Renaissance Technologies wurde in den 1980er Jahren vom Mathematiker James Simons gegründet und gilt als "Höhepunkt der quantitativen Investition".',
                        'tab2_title' => 'UNTERNEHMENSPOSITIONIERUNG',
                        'tab2_content' => 'Renaissance Technologies of Canada Ltd. positioniert sich als Pionier im quantitativen Finanzwesen.',
                        'tab3_title' => 'QUANTITATIVE INSTITUTION',
                        'tab3_content' => 'Als quantitative Institution steht Renaissance Technologies of Canada Ltd. an der Spitze der Finanzinnovation.',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-mission-vision',
                        'settings' => [
                            'title' => 'Mission und Vision',
                            'subtitle' => 'Wir sind bestrebt, das Finanztechniksystem durch künstliche Intelligenz und mathematische Modelle umzugestalten',
                        ],
                    ],
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-feature-cards',
                        'settings' => [
                            'card1_title' => 'Sicherheit',
                            'card1_description' => 'Aufbau einer zuverlässigen Systemgrundlage',
                            'card2_title' => 'Innovation',
                            'card2_description' => 'Wer Marktchancen nutzt',
                            'card3_title' => 'Präzise',
                            'card3_description' => 'Erfasst den Spielraum eines Haares',
                            'card4_title' => 'Exzellent',
                            'card4_description' => 'Globale Perspektiven ausarbeiten',
                        ],
                    ],
                ],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-section-title',
                        'settings' => [
                            'title' => 'Rapports médiatiques',
                            'subtitle' => 'Fokus auf Spitzentechnologie und Kapitaldynamik',
                        ],
                    ],
                    [
                        'id' => uniqid(),
                        'elType' => 'widget',
                        'widgetType' => 'rena-media-reports',
                        'settings' => ['posts_per_page' => 3],
                    ],
                ],
            ]],
        ],
    ];
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
}

// Research 页面德语版
function rena_set_research_elementor_data_fr($page_id) {
    $elementor_data = [
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-research-hero',
                    'settings' => [
                        'title' => 'Forschungsexzellenz',
                        'subtitle' => 'Wir streben keine kurzfristigen "Überrenditen" an - wir streben wiederholbare, verifizierbare und teilbare finanzwissenschaftliche Strukturen an.',
                        'feature1_title' => 'Komplexität zu Klarheit',
                        'feature1_description' => 'Mehrdimensionale Modellierung zur Vereinfachung komplexer Finanzsysteme',
                        'feature2_title' => 'Alpha zu Einblick',
                        'feature2_description' => 'Jedes Alpha-Modell wird in umsetzbare Marktinformationen übersetzt',
                        'feature3_title' => 'Ethik in KI-Finanzen',
                        'feature3_description' => 'Verantwortungsvolle Implementierung und ethische KI-Anwendungen',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => 'Wissenschaftler', 'subtitle' => 'Auf Wissenschaft als Grundstein basierend']],
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-scientists-list'],
                ],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => 'Cas de succès', 'subtitle' => 'Mit exzellenter Leistung den Wert des Modells überprüfen']],
                    ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-cases-list', 'settings' => ['posts_per_page' => 3, 'columns' => '3']],
                ],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-commercialization-path',
                    'settings' => [
                        'title' => 'Kommerzialisierungspfad',
                        'subtitle' => 'Unsere Forschungs-zu-Markt-Pipeline stellt sicher, dass wissenschaftliche Durchbrüche in praktische Anwendungen umgesetzt werden.',
                    ],
                ]],
            ]],
        ],
    ];
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
}

// Downloads 页面德语版
function rena_set_downloads_elementor_data_fr($page_id) {
    $elementor_data = [
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-downloads-hero',
                    'settings' => [
                        'category' => 'Verschlüsselung',
                        'title' => 'Software & Werkzeuge',
                        'subtitle' => '"Werkzeuge sind nicht das Ziel - sie sind Gefäße des Denkens."',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-main-download-card',
                    'settings' => [
                        'badge' => 'Premium-Mitglieder',
                        'title' => 'Finanztechnik-Softwarepaket',
                        'description' => 'Umfassendes quantitatives Werkzeug',
                        'button_text' => 'Télécharger',
                    ],
                ]],
            ]],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 50],
                    'elements' => [
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => 'Update-Ankündigungen', 'subtitle' => 'Echtzeit-Upgrade', 'alignment' => 'left']],
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-announcements-list', 'settings' => ['posts_per_page' => 1]],
                    ],
                ],
                [
                    'id' => uniqid(),
                    'elType' => 'column',
                'settings' => ['_column_size' => 100],
                    'settings' => ['_column_size' => 50],
                    'elements' => [
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-section-title', 'settings' => ['title' => 'Tutoriels vidéo', 'subtitle' => 'Umfassende Video-Anleitungen', 'alignment' => 'left']],
                        ['id' => uniqid(), 'elType' => 'widget', 'widgetType' => 'rena-video-tutorials', 'settings' => ['posts_per_page' => 4]],
                    ],
                ],
            ],
        ],
        [
            'id' => uniqid(),
            'elType' => 'section',
            'settings' => [],
            'elements' => [[
                'id' => uniqid(),
                'elType' => 'column',
                'settings' => ['_column_size' => 100],
                'elements' => [[
                    'id' => uniqid(),
                    'elType' => 'widget',
                    'widgetType' => 'rena-get-started-cta',
                    'settings' => [
                        'category' => 'Verschlüsselung',
                        'title' => 'Bereit anzufangen?',
                        'description' => 'Treten Sie unserer Mitgliederplattform bei, um auf alle Software-Tools zuzugreifen.',
                        'login_text' => 'Se connecter zum Télécharger',
                        'register_text' => 'Konto erstellen',
                    ],
                ]],
            ]],
        ],
    ];
    update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
}
