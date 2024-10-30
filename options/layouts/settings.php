<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div class="tab-content tab-settings-content cc-hidden">
    <table class="wp-list-table widefat plugins">
        <tbody>            
            <tr scope="row">
                <th class="w50">
                    <label for="ccImportFile"><?php _e('Import censored words from .txt file', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php echo $importTxtWordsDesc; ?></p>
                    <p class="howto cc-description cc-note"><?php _e('Note* the file you are going to import must be UTF-8 encoding, otherwise may be problems during the import', 'comments-censure'); ?></p>
                </th>
                <td><input type="file" id="ccImportFile" class="cc-import-file" name="ccImportFile" accept=".txt"/></td>
            </tr>            
            <tr scope="row">
                <th class="w50"><label for="isGlobalReplacement"><?php _e('Use global replacement for all censored words', 'comments-censure'); ?></label></th>
                <td>
                    <fieldset>
                        <label for="globalReplacement"><input <?php checked($isGlobalReplacement == 1); ?> type="radio" value="1" id="globalReplacement" class="cc-global-replacement" name="isGlobalReplacement" />&nbsp;&nbsp;<span><?php _e('Yes', 'comments-censure'); ?></span></label><br/>
                        <label for="notGlobalReplacement"><input <?php checked($isGlobalReplacement == 0); ?> type="radio" value="0" id="notGlobalReplacement" class="cc-global-replacement" name="isGlobalReplacement" />&nbsp;&nbsp;<span><?php _e('No (custom replacements)', 'comments-censure'); ?></span></label>
                    </fieldset><br/>
                    <div id="globalReplacementWrap" class="globalReplacementWrap w85">
                        <input value="<?php echo $globalReplacement; ?>" type="text" id="cc-replace-0" class="cc-text-field cc-replace w100" name="globalReplacement" />
                        <span title="<?php echo $imgReplaceButtonTitle . " " . $proVersion; ?>" id="cc-replace-image-0" class="dashicons dashicons-format-image cc-replace-image cc-disabled">&nbsp;</span>
                    </div>
                    <sup class="cc-in-pro cc-global-replacement-image"><?php echo $proVersion; ?></sup>
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="imageReplacementWidth"><?php _e('Image replacement sizes', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php _e('Image replacement width and height', 'comments-censure'); ?></p>
                </th>
                <td>
                    <div>
                        <input type="number" value="28" id="imageReplacementWidth" disabled="disabled" /> <?php _e('width in pixels', 'comments-censure'); ?>
                        <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                    </div>
                    <div>
                        <input type="number" value="28" id="imageReplacementHeight" disabled="disabled" /> <?php _e('height in pixels', 'comments-censure'); ?>
                        <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                    </div>
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="isFilterEmail"><?php _e('Filter emails', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php _e('Filter email content before sending', 'comments-censure'); ?></p>
                </th>
                <td><input type="checkbox" value="1" id="isFilterEmail" name="isFilterEmail" <?php checked($this->optionsSerialized->isFilterEmail == 1); ?> /></td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label><?php _e('Select an action when uncensored word detected in comment', 'comments-censure'); ?></label>
                </th>
                <td>
                    <fieldset>
                        <div>
                            <input type="radio" value="" id="uncensoredWordDoNothing" disabled="disabled" />
                            <label for="uncensoredWordDoNothing"><?php _e('Ignore', 'comments-censure'); ?></label>
                            <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                        </div>
                        <div>
                            <input type="radio" value="" id="uncensoredWordDisallow" disabled="disabled" />
                            <label for="uncensoredWordDisallow"><?php _e('Pre-submit validation and restriction', 'comments-censure'); ?></label>
                        </div>
                        <div>
                            <input type="radio" value="" id="uncensoredWordModerate" disabled="disabled" />
                            <label for="uncensoredWordModerate"><?php _e('Pending moderation', 'comments-censure'); ?></label>
                        </div>
                        <div>
                            <input type="radio" value="" id="uncensoredWordSpam" disabled="disabled" />
                            <label for="uncensoredWordSpam"><?php _e('Set as Spam', 'comments-censure'); ?></label>
                        </div>
                        <div>
                            <input type="radio" value="" id="uncensoredWordTrash" disabled="disabled" />
                            <label for="uncensoredWordTrash"><?php _e('Move to Trashed', 'comments-censure'); ?></label>
                        </div>                        
                    </fieldset>
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label><?php _e('Select an action when external URL detected in comment', 'comments-censure'); ?></label>                    
                </th>
                <td>
                    <fieldset>
                        <div>
                            <input type="radio" value="" id="extUrlDoNothing" disabled="disabled" />
                            <label for="extUrlDoNothing"><?php _e('Ignore', 'comments-censure'); ?></label>
                            <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                        </div>
                        <div>
                            <input type="radio" value="disallow" id="extUrlDisallow" disabled="disabled" />
                            <label for="extUrlDisallow"><?php _e('Pre-submit validation and restriction', 'comments-censure'); ?></label>
                        </div>
                        <div>
                            <input type="radio" value="moderate" id="extUrlModerate" disabled="disabled" />
                            <label for="extUrlModerate"><?php _e('Pending moderation', 'comments-censure'); ?></label>
                        </div>
                        <div>
                            <input type="radio" value="spam" id="extUrlSpam" disabled="disabled" />
                            <label for="extUrlSpam"><?php _e('Set as Spam', 'comments-censure'); ?></label>
                        </div>
                        <div>
                            <input type="radio" value="trash" id="extUrlTrash" disabled="disabled" />
                            <label for="extUrlTrash"><?php _e('Move to Trashed', 'comments-censure'); ?></label>
                        </div>
                    </fieldset>                    
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="whiteListedDomains"><?php _e('Whitelisted external domains', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php echo __('Comma separated domain names', 'comments-censure'); ?></p>
                    <p class="howto cc-description cc-note"><?php echo __('1) Note* write only domain names without - www.', 'comments-censure'); ?></p>
                    <p class="howto cc-description cc-note"><?php echo __('2) Note* this will cover most of all cases (mostly recommended)', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="whiteListedDomains" class="w85" rows="3" cols="50" placeholder="google.com, subdomain1.google.com, subdomain3.google.com" disabled="disabled"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="blockOnUncensored"><?php _e("Block user if comment contains uncensored word", 'comments-censure'); ?></label>
                </th>
                <td>
                    <div>
                        <input type="checkbox" value="" id="blockOnUncensored" disabled="disabled" />
                        <span><?php _e('for', 'comments-censure'); ?></span>
                        <input style="vertical-align:middle;" type="number" value="3" id="blockOnUncensoredExpire" disabled="disabled" />
                        <span><?php _e('days', 'comments-censure'); ?></span>
                        <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                    </div>
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="blockOnExternal"><?php _e("Block user if comment contains external url", 'comments-censure'); ?></label>
                </th>
                <td>
                    <div>
                        <input type="checkbox" value="" id="blockOnExternal" disabled="disabled" />
                        <span><?php _e('for', 'comments-censure'); ?></span>
                        <input style="vertical-align:middle;" type="number" value="3" id="blockOnExternalExpire" disabled="disabled" />
                        <span><?php _e('days', 'comments-censure'); ?></span>
                        <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                    </div>
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="blockedIps"><?php _e('Blocked ip addresses', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php echo __('Comma separated IP addresses for hard blocking', 'comments-censure'); ?></p>
                    <p class="howto cc-description cc-note"><?php echo __('Note* in order to block IP subnet, use pattern 127.0.*', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="blockedIps" class="w85" rows="3" cols="50" placeholder="127.0.0.1, 127.0.*" disabled="disabled"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>                        
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="blockedEmails"><?php _e('Blocked email addresses', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php echo __('Comma separated emails addresses for hard blocking', 'comments-censure'); ?></p>
                    <p class="howto cc-description cc-note"><?php echo __('Note* to block entire domain use pattern *@example.com', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="blockedEmails" class="w85" rows="3" cols="50" placeholder="user@example.com, *@example.com" disabled="disabled"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>                        
                </td>
            </tr>
            <tr scope="row">
                <th class="w50">
                    <label for="usersToNotify"><?php _e('Email addresses to get notifications', 'comments-censure'); ?></label>
                    <p class="howto cc-description"><?php echo $usersToNotifyDesc; ?></p>
                    <p class="howto cc-description cc-note"><?php echo $usersToNotifyDescNote; ?></p>
                </th>
                <td>
                    <textarea id="usersToNotify" class="w85" name="usersToNotify" rows="3" cols="50" placeholder="user@example.com, user1@example.com"><?php echo $this->optionsSerialized->usersToNotify; ?></textarea>
                </td>
            </tr>
        </tbody>
    </table>
</div>