<?php
if (!defined('ABSPATH')) {
    exit();
}
$search = $this->optionsSerialized->search;
$replace = $this->dbManager->getReplaceWords();
$exportSearchReplace = '';
if (($search && is_array($search)) && ($replace && is_array($replace)) && (count($search) == count($replace))) {
    for ($i = 0; $i < count($search); $i++) {
        $exSW = esc_html(wp_unslash($search[$i]));
        $exRW = esc_html(wp_unslash($replace[$i]));
        $exportSearchReplace .= "$exSW=$exRW";
        $exportSearchReplace .= PHP_EOL;
    }
}
?>
<div class="tab-content tab-export-content cc-hidden">
    <table class="wp-list-table widefat plugins">
        <tbody>
            <tr scope="row">
                <th class="w40"><label for="ccExportOptions"><?php _e('Export current options', 'comments-censure'); ?></label></th>
                <td><textarea id="ccExportOptions" class="cc-export-options-area w100" rows="5" cols="50"><?php echo maybe_serialize(get_option(self::OPTION_MAIN)); ?></textarea></td>
            </tr>
            <tr scope="row">
                <th class="w40"><label for="ccExportWords"><?php _e('Export current words', 'comments-censure'); ?></label></th>
                <td><textarea id="ccExportWords" class="cc-export-words-area w100" rows="5" cols="50"><?php echo $exportSearchReplace; ?></textarea></td>
            </tr>
        </tbody>
    </table>
</div>