<?php
$theme_uri = get_template_directory_uri();
$site_logo = get_theme_mod('site_logo', $theme_uri . '/assets/img/logo.svg');

// 页脚描述 - 使用 Polylang String Translations
$footer_description = get_theme_mod('footer_description', 'All rights reserved. The information on this website is for informational and discussion purposes only and does not constitute any issuance. Issuance can only be made by delivering a confidential issuance memorandum to appropriate investors. Past performance is not a guarantee of future performance. www.renfundx.com is the only official website of Renaissance Technologies of Canada Ltd.. Renaissance Technologies and any of its affiliated companies do not operate any other public websites. Any websites claiming to be associated with our company or our funds are not legitimate.');
if (function_exists('pll__')) {
    $footer_description = pll__($footer_description);
}

// WhatsApp 链接 - 全语言通用
$whatsapp_link = get_theme_mod('footer_whatsapp_link', 'https://wa.me/message/7O5Y2WOR6HEPF1');

// WhatsApp 按钮文字 - 使用 Polylang String Translations
$whatsapp_text = get_theme_mod('footer_whatsapp_text', 'WhatsApp Consultation');
if (function_exists('pll__')) {
    $whatsapp_text = pll__($whatsapp_text);
}

// 版权文字 - 使用 Polylang String Translations
$footer_copyright = get_theme_mod('footer_copyright', '© 2025 Renaissance Technologies of Canada Ltd.');
if (function_exists('pll__')) {
    $footer_copyright = pll__($footer_copyright);
}
?>
<!-- Footer -->
<footer id="member" class="footer">
    <div class="container">
        <div class="row align-items-start">
            <div class="col-lg-8">
                <div class="footer-brand">
                    <img src="<?php echo esc_url($site_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="footer-logo">
                </div>
                <p class="footer-description">
                    <?php echo wp_kses_post($footer_description); ?>
                </p>
            </div>

            <div class="col-lg-4">
                <div class="newsletter-section">
                    <h5 class="newsletter-title" data-translate="newsletter-title"><?php echo esc_html__('Get free information', 'renaissance'); ?></h5>
                    <p class="newsletter-subtitle" data-translate="newsletter-subtitle"><?php echo esc_html__('In our weekly newsletter.', 'renaissance'); ?></p>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" class="newsletter-input" id="newsletter-email" placeholder="<?php echo esc_attr__('Enter your email', 'renaissance'); ?>" data-translate="newsletter-placeholder" required>
                        <button type="submit" class="btn-subscribe" data-translate="newsletter-button"><?php echo esc_html__('Subscribe', 'renaissance'); ?></button>
                    </form>
                    <div class="newsletter-message" style="display: none; margin-top: 10px;"></div>
                    <div class="whatsapp-contact-section">
                        <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" class="btn-whatsapp-contact">
                            <i class="bi bi-whatsapp"></i>
                            <span><?php echo esc_html($whatsapp_text); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <?php
                    if (has_nav_menu('footer')) {
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'container'      => 'div',
                            'container_class' => 'footer-links',
                            'items_wrap'     => '%3$s',
                            'walker'         => new class extends Walker_Nav_Menu {
                                function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                    $url = !empty($item->url) ? $item->url : '';
                                    $title = apply_filters('the_title', $item->title, $item->ID);
                                    $translate_attr = '';
                                    
                                    // Map common menu items to data-translate keys
                                    $translate_map = [
                                        'Privacy Policy' => 'footer-privacy',
                                        'Risk Warning' => 'footer-risk',
                                        'Contact Information' => 'footer-contact',
                                        'Investor Relations' => 'footer-investor',
                                    ];
                                    if (isset($translate_map[$title])) {
                                        $translate_attr = ' data-translate="' . esc_attr($translate_map[$title]) . '"';
                                    }
                                    
                                    $output .= '<a href="' . esc_url($url) . '"' . $translate_attr . '>' . esc_html($title) . '</a>';
                                    
                                    // Add separator except for last item
                                    if (!$item->_rena_is_last) {
                                        $output .= '<span class="separator">·</span>';
                                    }
                                }
                            }
                        ]);
                    } else {
                        // Fallback footer links
                        ?>
                        <div class="footer-links">
                            <a href="<?php echo esc_url(rena_get_translated_page_url('privacy-policy')); ?>" data-translate="footer-privacy"><?php echo esc_html__('Privacy Policy', 'renaissance'); ?></a>
                            <span class="separator">·</span>
                            <a href="<?php echo esc_url(rena_get_translated_page_url('risk-warning')); ?>" data-translate="footer-risk"><?php echo esc_html__('Risk Warning', 'renaissance'); ?></a>
                            <span class="separator">·</span>
                            <a href="<?php echo esc_url(rena_get_translated_page_url('contact')); ?>" data-translate="footer-contact"><?php echo esc_html__('Contact Information', 'renaissance'); ?></a>
                            <span class="separator">·</span>
                            <a href="<?php echo esc_url(rena_get_translated_page_url('investor-relations')); ?>" data-translate="footer-investor"><?php echo esc_html__('Investor Relations', 'renaissance'); ?></a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-lg-6 text-end">
                    <p class="copyright"><?php echo esc_html($footer_copyright); ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
// 订阅表单配置
window.renaNewsletter = {
    ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('rena_newsletter_subscription'); ?>',
    pageId: <?php echo get_queried_object_id(); ?>
};
</script>
