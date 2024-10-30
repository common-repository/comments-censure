<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div class="tab-content tab-import-content cc-hidden">
    <table class="wp-list-table widefat plugins">
        <tbody>
            <tr scope="row">
                <th class="w40"><label for="ccImportOptions"><?php _e('Import options', 'comments-censure'); ?></label></th>
                <td>
                    <textarea id="ccImportOptions" class="cc-import-options-area w100" rows="5" cols="50"></textarea>
                    <div>
                        <button type="button" class="button button-primary cc-btn-import-options"><?php _e('Import Options', 'comments-censure'); ?></button>
                        <img id="cc-import-options-loading" src="<?php echo plugins_url(CC_DIR_NAME . '/assets/img/loading.gif'); ?>" width="24" height="24" style="display:none;"/>
                    </div>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40"><label for="ccImportWords"><?php _e('Import words', 'comments-censure'); ?></label></th>
                <td>
                    <textarea id="ccImportWords" class="cc-import-words-area w100" rows="5" cols="50"></textarea>
                    <div>
                        <button type="button" class="button button-primary cc-btn-import-words"><?php _e('Import Words', 'comments-censure'); ?></button>
                        <img id="cc-import-words-loading" src="<?php echo plugins_url(CC_DIR_NAME . '/assets/img/loading.gif'); ?>" width="24" height="24" style="display:none;"/>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>