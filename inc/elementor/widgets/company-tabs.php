<?php
/**
 * Elementor Company Tabs Widget
 * 公司介绍标签页（带自动切换动画）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Company_Tabs_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-company-tabs';
    }

    public function get_title() {
        return __('Company Tabs', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_categories() {
        return ['renaissance-dynamic'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'tab1_section',
            [
                'label' => __('Tab 1', 'renaissance'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tab1_title',
            [
                'label' => __('Title', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'COMPANY OVERVIEW',
            ]
        );

        $this->add_control(
            'tab1_content',
            [
                'label' => __('Content', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 8,
                'default' => 'Renaissance Technologies was founded by mathematician James Simons...',
            ]
        );

        $this->end_controls_section();

        // Tab 2
        $this->start_controls_section(
            'tab2_section',
            [
                'label' => __('Tab 2', 'renaissance'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tab2_title',
            [
                'label' => __('Title', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'COMPANY POSITIONING',
            ]
        );

        $this->add_control(
            'tab2_content',
            [
                'label' => __('Content', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 8,
                'default' => 'Renaissance Technologies of Canada Ltd. positions itself...',
            ]
        );

        $this->end_controls_section();

        // Tab 3
        $this->start_controls_section(
            'tab3_section',
            [
                'label' => __('Tab 3', 'renaissance'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tab3_title',
            [
                'label' => __('Title', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'QUANTITATIVE INSTITUTION',
            ]
        );

        $this->add_control(
            'tab3_content',
            [
                'label' => __('Content', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 8,
                'default' => 'As a quantitative institution, Renaissance Technologies...',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="hero-info-section">
            <div class="info-tabs-container">
                <!-- 选项卡导航 -->
                <div class="info-tabs-nav">
                    <button class="info-tab-btn" data-tab="company-overview">
                        <span><?php echo esc_html($settings['tab1_title']); ?></span>
                    </button>
                    <button class="info-tab-btn" data-tab="company-positioning">
                        <span><?php echo esc_html($settings['tab2_title']); ?></span>
                    </button>
                    <button class="info-tab-btn active" data-tab="executive-summary">
                        <span><?php echo esc_html($settings['tab3_title']); ?></span>
                    </button>
                </div>

                <!-- 选项卡内容 -->
                <div class="info-tabs-content">
                    <div class="info-tab-pane" id="company-overview">
                        <div class="tab-content-wrapper">
                            <p class="tab-description"><?php echo esc_html($settings['tab1_content']); ?></p>
                        </div>
                    </div>

                    <div class="info-tab-pane" id="company-positioning">
                        <div class="tab-content-wrapper">
                            <p class="tab-description"><?php echo esc_html($settings['tab2_content']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-tab-pane active" id="executive-summary">
                        <div class="tab-content-wrapper">
                            <p class="tab-description"><?php echo esc_html($settings['tab3_content']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Hero Info Tabs 自动切换
        (function() {
            const tabButtons = document.querySelectorAll('.info-tab-btn');
            const tabPanes = document.querySelectorAll('.info-tab-pane');
            let currentIndex = 0;
            let autoSwitchTimer = null;
            let isUserInteracting = false;

            function switchToTab(index) {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                tabButtons[index].classList.add('active');
                const targetTab = tabButtons[index].getAttribute('data-tab');
                document.getElementById(targetTab).classList.add('active');
                currentIndex = index;
            }

            function autoSwitchNext() {
                if (isUserInteracting) return;
                const nextIndex = (currentIndex + 1) % tabButtons.length;
                switchToTab(nextIndex);
                startAutoSwitch();
            }

            function startAutoSwitch() {
                clearTimeout(autoSwitchTimer);
                if (!isUserInteracting) {
                    autoSwitchTimer = setTimeout(autoSwitchNext, 8000);
                }
            }

            function stopAutoSwitch() {
                clearTimeout(autoSwitchTimer);
            }

            tabButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    isUserInteracting = true;
                    stopAutoSwitch();
                    switchToTab(index);
                    setTimeout(() => {
                        isUserInteracting = false;
                        startAutoSwitch();
                    }, 3000);
                });
            });

            const tabContainer = document.querySelector('.info-tabs-nav');
            if (tabContainer) {
                tabContainer.addEventListener('mouseenter', () => {
                    isUserInteracting = true;
                    stopAutoSwitch();
                });
                tabContainer.addEventListener('mouseleave', () => {
                    setTimeout(() => {
                        isUserInteracting = false;
                        startAutoSwitch();
                    }, 1000);
                });
            }

            startAutoSwitch();
        })();
        </script>
        <?php
    }
}

