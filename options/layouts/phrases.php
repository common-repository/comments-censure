<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div class="tab-content tab-phrases-content cc-hidden">
    <table class="wp-list-table widefat plugins">
        <tbody>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseEmailSubject"><?php _e('Email subject', 'comments-censure'); ?></label>
                </th>
                <td>
                    <input type="text" id="phraseEmailSubject" class="cc-text-field w85" name="phraseEmailSubject" value="<?php echo wp_unslash($this->optionsSerialized->phraseEmailSubject); ?>" placeholder="<?php _e('New unwanted comment', 'comments-censure'); ?>"/>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseEmailContent"><?php _e('Email content', 'comments-censure'); ?></label>
                    <p class="howto cc-description cc-note"><?php _e('Note* Do not change/remove "%s", this will be used for getting comment url', 'comments-censure'); ?></p>
                    <p class="howto cc-description cc-note"><?php _e('Note* comment url and other HTML tags will be stripped if you change mail content type to text/plain', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="phraseEmailContent" class="w85" name="phraseEmailContent" rows="3" cols="50" placeholder='<?php _e('New unwanted comment was posted, to moderate please <a href="%s" target="_blank">Click here</a>', 'comments-censure'); ?>'><?php echo wp_unslash($this->optionsSerialized->phraseEmailContent); ?></textarea>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseUncensoredWordFound"><?php _e('Uncensored word detected', 'comments-censure'); ?></label>
                    <p class="howto cc-description cc-note"><?php _e('Note* Do not change/remove "%s", this will be used for getting uncensored word', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="phraseUncensoredWordFound" class="w85" rows="3" cols="50" disabled="disabled" placeholder="<?php _e('Error: uncensored word detected - %s', 'comments-censure'); ?>"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseExternalUrlFound"><?php _e('External url detected', 'comments-censure'); ?></label>
                    <p class="howto cc-description cc-note"><?php _e('Note* Do not change/remove "%s", this will be used for getting external url', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="phraseExternalUrlFound" class="w85" name="phraseExternalUrlFound" rows="3" cols="50" disabled="disabled" placeholder="<?php _e('Error: external url detected - %s', 'comments-censure'); ?>"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseBlockedOnUncensored"><?php _e('Temporarily blocked for posting a comment with uncensored word', 'comments-censure'); ?></label>
                    <p class="howto cc-description cc-note"><?php _e('Note* Do not change/remove "%d", this will be used for getting expiration time dynamically', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="phraseBlockedOnUncensored" class="w85" rows="3" cols="50" disabled="disabled" placeholder="<?php _e('You have been blocked for posting a comment with uncensored word until %s', 'comments-censure'); ?>"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseBlockedOnExternal"><?php _e('Temporarily blocked for posting a comment with external url', 'comments-censure'); ?></label>
                    <p class="howto cc-description cc-note"><?php _e('Note* Do not change/remove "%d", this will be used for getting expiration time dynamically', 'comments-censure'); ?></p>
                </th>
                <td>
                    <textarea id="phraseBlockedOnExternal" class="w85" name="phraseBlockedOnExternal" rows="3" cols="50" disabled="disabled" placeholder="<?php _e('You have been blocked for posting a comment with external url until %s', 'comments-censure'); ?>"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>            
            <tr scope="row">
                <th class="w40">
                    <label for="phraseBlockedIp"><?php _e('IP blocked', 'comments-censure'); ?></label>
                </th>
                <td>
                    <textarea id="phraseBlockedIp" class="w85" rows="3" cols="50" disabled="disabled" placeholder="<?php _e('Your IP address has been blocked for posting a comment', 'comments-censure'); ?>"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>
            <tr scope="row">
                <th class="w40">
                    <label for="phraseBlockedEmail"><?php _e('Email blocked', 'comments-censure'); ?></label>
                </th>
                <td>
                    <textarea id="phraseBlockedEmail" class="w85" rows="3" cols="50" disabled="disabled" placeholder="<?php _e('Your email address has been blocked for posting a comment', 'comments-censure'); ?>"></textarea>
                    <sup class="cc-in-pro"><?php echo $proVersion; ?></sup>
                </td>
            </tr>            
        </tbody>
    </table>
</div>