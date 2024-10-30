<?php
if (!defined('ABSPATH')) {
    exit();
}
$disabled = $isGlobalReplacement ? 'disabled="disabled"' : '';
$isPerPageAdded = false;
?>
<div class="tab-content tab-words-content cc-hidden cc-show">
    <?php
    $paginationHtml = '';
    if ($words && is_array($words)) {
        $paginationHtml .= "<div class='cc-pagination'>";
        if ($countPages > 1) {
            $paginationHtml .= "<div class='cc-pages'>";
            for ($i = 0; $i < $countPages; $i++) {
                $page = $i + 1;
                $pageUrl = $i ? "$mainPage&wordsOffset=$i" : $mainPage;
                $paginationHtml .= ($i == $offset) ? "<span class='cc-page cc-page-current'>$page</span>" : "<a href='$pageUrl' class='cc-page'>$page</a>";
            }
            $paginationHtml .= "</div>";
        }
        $paginationHtml .= "<div class='cc-words-per-page-wrap'>";
        $paginationHtml .= "<label for='wordsPerPage'>" . __('Words per page', 'comments-censure') . "</label> ";
        $paginationHtml .= "<input type='number' id='wordsPerPage' name='wordsPerPage[]' placeholder='" . $this->optionsSerialized->wordsPerPage . "'/>";
        $paginationHtml .= "</div>";
        $paginationHtml .= "<div style='clear:both;'></div>";
        $paginationHtml .= "</div>";
        echo $paginationHtml;
    }
    ?>
    <div class="cc-words-wrapper">
        <?php
        if ($words && is_array($words)) {
            foreach ($words as $w) {
                $sVal = esc_html(wp_unslash($w['search']));
                $rVal = esc_html(wp_unslash($w['replace']));
                ?>
                <div id="ccrow-<?php echo $w['id']; ?>" class="cc-row">
                    <div class="cc-col cc-flex-grow"><input value="<?php echo $sVal; ?>" type="text" name="search[]" class="cc-search cc-text-field w100" placeholder="<?php echo $searchPlaceHolder ?>"/></div>
                    <div class="cc-col cc-flex-grow cc-col-replace">
                        <input id="cc-replace-<?php echo $w['id']; ?>" value="<?php echo $rVal; ?>" type="text" name="replace[]" class="cc-text-field cc-replace w100" placeholder="<?php echo $replacePlaceHolder ?>" <?php echo $disabled; ?>/>
                        <span id="cc-replace-image-<?php echo $w['id']; ?>" title="<?php echo $imgReplaceButtonTitle . " " . $proVersion; ?>" class="dashicons dashicons-format-image cc-replace-image cc-disabled">&nbsp;</span>
                    </div>
                    <div class="cc-col cc-flex-shrink"><button type="button" id="ccremove-<?php echo $w['id']; ?>" class="button button-primary cc-remove"><?php echo $removeButtonTitle; ?></button> <input type="hidden" name="wordIds[]" value="<?php echo $w['id']; ?>"/></div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php echo $paginationHtml; ?>
    <div style="margin: 15px 7px;">
        <button id="ccAddMore" class="button button-secondary" type="button"><?php _e('Add New', 'comments-censure'); ?></button>
        <?php
        if ($words) {
            $removeWords = admin_url("admin-post.php/?action=" . self::ACTION_REMOVE_ALL);
            ?>
            <a href="<?php echo wp_nonce_url($removeWords, self::ACTION_REMOVE_ALL); ?>" class="button button-secondary cc-remove-all">
                <?php _e('Remove All Words', 'comments-censure'); ?>
            </a>
        <?php } ?>
    </div>
    <div style="clear: both;"></div>    
</div>