<?php

if (!defined('ABSPATH')) {
    exit();
}

class CCOptions implements CCConstants {

    private $optionsSerialized;
    private $dbManager;

    public function __construct($dbManager, $optionsSerialized) {
        $this->dbManager = $dbManager;
        $this->optionsSerialized = $optionsSerialized;
    }

    public function mainForm() {
        if (isset($_POST['ccSaveOptions'])) {
            if (!current_user_can('manage_options')) {
                die(_e('Hacker?', 'comments-censure'));
            }
            check_admin_referer('cc_options_form');

            if (isset($_POST['wordsPerPage'][0]) && ($perPage = absint($_POST['wordsPerPage'][0]))) {
                $this->optionsSerialized->wordsPerPage = $perPage;
            } else {
                $this->optionsSerialized->wordsPerPage = isset($_POST['wordsPerPage'][1]) && ($perPage = absint($_POST['wordsPerPage'][1])) ? $perPage : $this->optionsSerialized->wordsPerPage;
            }
            $this->optionsSerialized->isGlobalReplacement = isset($_POST['isGlobalReplacement']) && ($igr = intval($_POST['isGlobalReplacement'])) ? $igr : 0;
            $this->optionsSerialized->globalReplacement = isset($_POST['globalReplacement']) && ($gr = esc_html(wp_unslash(trim($_POST['globalReplacement'])))) ? $gr : __('[censored]', 'comments-censure');
            $this->optionsSerialized->isFilterEmail = isset($_POST['isFilterEmail']) && ($ife = intval($_POST['isFilterEmail'])) ? $ife : 0;
            $this->optionsSerialized->usersToNotify = isset($_POST['usersToNotify']) && ($emails = esc_html(trim($_POST['usersToNotify']))) ? $emails : '';
            $this->optionsSerialized->phraseEmailSubject = isset($_POST['phraseEmailSubject']) && ($phraseEmailSubject = esc_html(trim($_POST['phraseEmailSubject']))) ? $phraseEmailSubject : __('New unwanted comment', 'comments-censure');
            $this->optionsSerialized->phraseEmailContent = isset($_POST['phraseEmailContent']) && ($phraseEmailContent = trim($_POST['phraseEmailContent'])) ? $phraseEmailContent : __('New unwanted comment was posted, to moderate please <a href="%s" target="_blank">Click here</a>', 'comments-censure');
            if (isset($_FILES["ccImportFile"]) && $_FILES["ccImportFile"]["type"] == 'text/plain' && $_FILES["ccImportFile"]["error"] == 0) {
                $this->dbManager->addDefaultWords($_FILES["ccImportFile"]["tmp_name"]);
            }

            $this->optionsSerialized->updateOptions();
            $this->saveWords();
        }
        include 'html-options.php';
    }

    private function saveWords() {
        if (isset($_POST['search']) && is_array($_POST['search']) && ($search = $_POST['search'])) {
            $replace = isset($_POST['replace']) && is_array($_POST['replace']) ? $_POST['replace'] : array();
            $ids = isset($_POST['wordIds']) && is_array($_POST['wordIds']) ? $_POST['wordIds'] : array();
            $words = array();
            if (count($search) == count($replace)) {
                for ($i = 0; $i < count($search); $i++) {
                    $sW = $search[$i] ? $search[$i] : '';
                    if ($sW) {
                        $wId = $ids[$i];
                        $rW = isset($replace[$i]) && trim($replace[$i]) ? trim($replace[$i]) : CCHelper::makeCensoredWord(wp_unslash($sW), '*');
                        $words[] = array('id' => $wId, 'search' => $sW, 'replace' => $rW);
                    }
                }
                $this->dbManager->addWords($words);
            }
        }
    }

}
