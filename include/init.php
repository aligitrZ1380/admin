<?php

if (!file_exists('../config.php')) {
    die();
}
if (!defined('SB_PATH')) define('SB_PATH', dirname(dirname(__FILE__)));
require('../config.php');
require('functions.php');
sb_init_translations();
if (sb_isset($_GET, 'mode') == 'tickets') {
    sb_component_tickets();
} else {
    sb_component_chat();
}
echo '<!-- Support Board - https://board.support -->';
die();

function sb_component_chat() {
    sb_js_global();
    sb_css();
    $header_headline = sb_get_setting('header-headline');
    $header_message = sb_get_setting('header-msg');
    $background = sb_get_setting('header-img');
    $icon = sb_get_setting('chat-icon');
    $header_type = sb_get_setting('header-type', 'agents');
    $disable_dashboard = sb_get_setting('disable-dashboard');
    $texture = sb_get_setting('chat-background');
    $css = '';
    if (sb_get_setting('rtl') || in_array(sb_get_user_language(), ['ar', 'he', 'ku', 'fa', 'ur'])) {
        $css .= ' sb-rtl';
    }
    if (sb_get_setting('chat-position') == 'left') {
        $css .= ' sb-chat-left';
    }
    if ($disable_dashboard) {
        $css .= ' sb-dashboard-disabled';
    }
    if (empty($icon)) {
        $icon = sb_get_setting('chat-sb-icons');
        if (!empty($icon)) {
            $icon = SB_URL . '/media/' . $icon;
        }
    }
?>
<div class="sb-main sb-chat sb-no-conversations<?php echo $css ?>" style="display: none; transition: none;">
    <div class="sb-body">
        <div class="sb-scroll-area<?php if ($texture != '') echo ' sb-texture-' . substr($texture, -5, 1) ?>">
            <div class="sb-header sb-header-main sb-header-type-<?php echo $header_type ?>" <?php if ($background != '') echo 'style="background-image: url(' . $background . ')"' ?>>
                <?php if (!$disable_dashboard) echo '<i class="sb-icon-close sb-dashboard-btn"></i>'; ?>
                <div class="sb-content">
                    <?php if ($header_type == 'brand') echo '<div class="sb-brand"><img src="' . sb_get_setting('brand-img') . '" alt="" /></div>' ?>
                    <div class="sb-title">
                        <?php echo sb_($header_headline != '' ? $header_headline : 'Support Board Chat') ?>
                    </div>
                    <div class="sb-text">
                        <?php echo sb_($header_message != '' ? $header_message : 'We are an experienced team that provides fast and accurate answers to your questions.') ?>
                    </div>
                    <?php

    if ($header_type == 'agents') {
        $agents = sb_db_get('SELECT first_name, profile_image FROM sb_users WHERE user_type = "agent" OR user_type = "admin" LIMIT 3', false);
        $code = '';
        for ($i = 0; $i < count($agents); $i++) {
            $code .= '<div><span>' . $agents[$i]['first_name'] . '</span><img src="' . $agents[$i]['profile_image'] . '" alt="" /></div>';
        }
        echo '<div class="sb-profiles">' . $code . '</div>';
    }

                    ?>
                </div>
            </div>
            <div class="sb-list sb-active"></div>
            <div class="sb-dashboard">
                <div class="sb-dashboard-conversations">
                    <div class="sb-title">
                        <?php sb_e('Conversations') ?>
                    </div>
                    <ul class="sb-user-conversations"></ul>
                    <?php if (!sb_get_multi_setting('departments-settings', 'departments-dashboard') && !$disable_dashboard) echo '<div class="sb-btn sb-btn-new-conversation">' . sb_('New conversation') . '</div>' ?>
                </div>
                <?php if (sb_get_multi_setting('departments-settings', 'departments-dashboard')) sb_departments('dashboard') ?>
                <?php if (sb_get_setting('articles-active')) echo sb_get_rich_message('articles') ?>
            </div>
            <div class="sb-panel sb-panel-articles"></div>
        </div>
        <?php sb_component_editor(); ?>
    </div>
    <div class="sb-chat-btn">
        <span data-count="0"></span>
        <img class="sb-icon" alt="" src="<?php echo $icon != '' ? $icon : SB_URL . '/media/button-chat.svg' ?>" />
        <img class="sb-close" alt="" src="<?php echo SB_URL ?>/media/button-close.svg" />
    </div>
    <i class="sb-icon sb-icon-close sb-responsive-close-btn"></i>
    <audio id="sb-audio" preload="auto">
        <source src="<?php echo SB_URL ?>/media/sound.mp3" type="audio/mpeg">
    </audio>
    <audio id="sb-audio-out" preload="auto">
        <source src="<?php echo SB_URL ?>/media/sound-out.mp3" type="audio/mpeg">
    </audio>
    <div class="sb-lightbox-media">
        <div></div>
        <i class="sb-icon-close"></i>
    </div>
    <div class="sb-lightbox-overlay"></div>
</div>
<?php } ?>