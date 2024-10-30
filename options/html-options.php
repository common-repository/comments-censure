<?php
if (!defined('ABSPATH')) {
    exit();
}
$wordsOffset = 0;
$offset = 0;
$allWords = $this->dbManager->getCensoredWords();
$countPages = ceil($allWords[0]['count'] / $this->optionsSerialized->wordsPerPage);
if (isset($_GET['wordsOffset']) && ($wo = intval($_GET['wordsOffset']))) {
    $wordsOffset = ($wo <= $countPages) ? $wo * $this->optionsSerialized->wordsPerPage : 0;
    $offset = ($wo <= $countPages) ? $wo : 0;
}

$searchPlaceHolder = __('Search', 'comments-censure');
$replacePlaceHolder = __('Replace (Leave empty to autogenerate)', 'comments-censure');
$removeButtonTitle = __('Remove', 'comments-censure');
$imgReplaceButtonTitle = __('Replace with image', 'comments-censure');
$proVersion = __('(PRO version)', 'comments-censure');
$importTxtWordsDesc = __('TXT file should contain words separated by single line-break.', 'comments-censure');
$leaveEmptyOr0Desc = __('Leave empty or set 0 for disabling', 'comments-censure');
$usersToNotifyDesc = __('Comma separated email addresses to get notifications if unwanted comment was detected', 'comments-censure');
$usersToNotifyDescNote = __("Note* this option does not disable wordpress' new comment notifications", 'comments-censure');

$mainPage = get_admin_url() . self::PAGE_SUB_MENU . '?page=' . self::PAGE_SETTINGS;
$words = $this->dbManager->getCensoredWords($this->optionsSerialized->wordsPerPage, $wordsOffset);

$isGlobalReplacement = isset($this->optionsSerialized->isGlobalReplacement) ? $this->optionsSerialized->isGlobalReplacement : 0;
$globalReplacement = isset($this->optionsSerialized->globalReplacement) ? $this->optionsSerialized->globalReplacement : __('[censored]', 'comments-censure');
?>
<div class="cc-options w99">
    <h1><img width="80" src="<?php echo plugins_url(CC_DIR_NAME . '/assets/img/icon-128x128.png'); ?>" align="absmiddle"/>&nbsp; <?php _e('Comments Censure', 'comments-censure'); ?></h1><br />

    <form method="get" class="cc-search-words-form">
        <div class='cc-search-words'>
            <input type='text' class='cc-search-words-input' placeholder='<?php _e('search', 'comments-censure'); ?>' value='' />
            <button type='submit' class='button button-primary cc-search-words-button cc-not-clicked'>
                <span class='dashicons dashicons-search '></span>
            </button>
            <img id="cc-search-words-loading" src="<?php echo plugins_url(CC_DIR_NAME . '/assets/img/loading.gif'); ?>" width="24" height="24" style="position: absolute; right: 35px; top: 4px; display: none;"/>
        </div>
    </form>

    <form action="<?php echo "$mainPage"; ?>" method="post" name="<?php echo self::PAGE_SETTINGS; ?>" class="cc-form" enctype="multipart/form-data">
        <?php wp_nonce_field('cc_options_form'); ?>               

        <div class="cc-options-wrapper">
            <h2 class="nav-tab-wrapper">
                <a href="#cc-tab-words" id="tab-words" class="nav-tab nav-tab-active">
                    <span class="dashicons dashicons-media-text"></span>
                    <span class="tab-title"><?php _e('Words', 'comments-censure'); ?></span>
                </a>
                <a href="#cc-tab-settings" id="tab-settings" class="nav-tab">
                    <span class="dashicons dashicons-admin-generic"></span>
                    <span class="tab-title"> <?php _e('Main Settings', 'comments-censure'); ?></span>
                </a>
                <a href="#cc-tab-phrases" id="tab-phrases" class="nav-tab">
                    <span class="dashicons dashicons-media-text"></span>
                    <span class="tab-title"> <?php _e('Phrases', 'comments-censure'); ?></span>
                </a>
                <a href="#cc-tab-export" id="tab-export" class="nav-tab">
                    <span class="dashicons dashicons-download"></span>
                    <span class="tab-title"><?php _e('Export', 'comments-censure'); ?></span>
                </a>
                <a href="#cc-tab-import" id="tab-import" class="nav-tab">
                    <span class="dashicons dashicons-upload"></span>
                    <span class="tab-title"><?php _e('Import', 'comments-censure'); ?></span>
                </a>
            </h2>
            <div class="nav-tab-content-wrapper">
                <?php
                include_once 'layouts/words.php';
                include_once 'layouts/settings.php';
                include_once 'layouts/phrases.php';
                include_once 'layouts/export.php';
                include_once 'layouts/import.php';
                ?>
            </div>

        </div>

        <table class="form-table cc-form-table">
            <tbody>
                <tr valign="top">
                    <td colspan="4">
                        <p class="submit">
                            <?php $actionUrl = admin_url('admin-post.php/?action=' . self::ACTION_RESET_OPTIONS); ?>
                            <a style="float: left;" class="button button-secondary" href="<?php echo wp_nonce_url($actionUrl, self::NONCE_RESET_OPTIONS); ?>"><?php _e('Reset Options', 'comments-censure'); ?></a>
                            <input style="float: right;" type="submit" class="button button-primary ccSaveOptions" name="ccSaveOptions" value="<?php _e('Save Changes', 'comments-censure'); ?>" />
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="update" />
    </form>
    <div class="cc-hidden cc-clone">
        <div id="ccrow-incrementId" class="cc-row">
            <div class="cc-col cc-flex-grow"><input type="text" name="search[]" class="cc-search cc-text-field w100" placeholder="<?php echo $searchPlaceHolder; ?>"/></div>
            <div class="cc-col cc-flex-grow cc-col-replace"><input type="text" name="replace[]" class="cc-replace cc-text-field w100" placeholder="<?php echo $replacePlaceHolder; ?>"/><span id="cc-replace-image-incrementId" title="<?php echo $imgReplaceButtonTitle . $proVersion; ?>" class="dashicons dashicons-format-image cc-replace-image cc-disabled">&nbsp;</span></div>
            <div class="cc-col cc-flex-shrink"><button type="button" id="ccremove-incrementId" class="button button-primary cc-remove"><?php echo $removeButtonTitle; ?></button> <input type="hidden" name="wordIds[]" value="0"/></div>
        </div>
    </div>
</div>