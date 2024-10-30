<?php

if (!defined('ABSPATH')) {
    exit();
}

class CCOptionsSerialized implements CCConstants {

    private $dbManager;
    public $wordsPerPage;
    public $isGlobalReplacement;
    public $globalReplacement;
    public $isFilterEmail;
    public $usersToNotify;
    /* PHRASES AND TEXTS */
    public $phraseEmailSubject;
    public $phraseEmailContent;
    /* SEARCH AND REPLACE ARRAYS */
    public $search;
    public $replace;

    public function __construct($dbManager) {
        $this->dbManager = $dbManager;
        $this->addOptions();
        $this->initOptions(get_option(self::OPTION_MAIN));
    }

    public function addOptions() {
        $options = array(
            'wordsPerPage' => 10,
            'isGlobalReplacement' => 0,
            'globalReplacement' => __('[censored]', 'comments-censure'),
            'isFilterEmail' => 1,
            'usersToNotify' => '',
            'phraseEmailSubject' => __('New unwanted comment', 'comments-censure'),
            'phraseEmailContent' => __('New unwanted comment was posted, to moderate please <a href="%s" target="_blank">Click here</a>', 'comments-censure'),
        );
        add_option(self::OPTION_MAIN, $options, '', 'no');
    }

    public function initOptions($options) {
        $o = maybe_unserialize($options);
        $this->wordsPerPage = isset($o['wordsPerPage']) && ($perPage = absint($o['wordsPerPage'])) ? $perPage : 10;
        $this->isGlobalReplacement = isset($o['isGlobalReplacement']) && ($isGlobal = intval($o['isGlobalReplacement'])) ? $isGlobal : 0;
        $this->globalReplacement = isset($o['globalReplacement']) && ($globalReplacement = esc_html(wp_unslash(trim($o['globalReplacement'])))) ? $globalReplacement : __('[censored]', 'comments-censure');
        $this->isFilterEmail = isset($o['isFilterEmail']) && ($isFilterEmail = intval($o['isFilterEmail'])) ? $isFilterEmail : 0;
        $this->usersToNotify = isset($o['usersToNotify']) && ($emails = esc_html(trim($o['usersToNotify']))) ? $emails : '';
        $this->phraseEmailSubject = isset($o['phraseEmailSubject']) && ($phraseEmailSubject = wp_unslash(esc_html(trim($o['phraseEmailSubject'])))) ? $phraseEmailSubject : __('New unwanted comment', 'comments-censure');
        $this->phraseEmailContent = isset($o['phraseEmailContent']) && ($phraseEmailContent = wp_unslash(trim($o['phraseEmailContent']))) ? $phraseEmailContent : __('New unwanted comment was posted, to moderate please <a href="%s" target="_blank">Click here</a>', 'comments-censure');
    }

    public function toArray() {
        $options = array(
            'wordsPerPage' => $this->wordsPerPage,
            'isGlobalReplacement' => $this->isGlobalReplacement,
            'globalReplacement' => $this->globalReplacement,
            'isFilterEmail' => $this->isFilterEmail,
            'usersToNotify' => $this->usersToNotify,
            'phraseEmailSubject' => $this->phraseEmailSubject,
            'phraseEmailContent' => $this->phraseEmailContent,
        );
        return $options;
    }

    public function updateOptions() {
        update_option(self::OPTION_MAIN, $this->toArray());
    }

}
