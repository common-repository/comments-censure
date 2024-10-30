<?php

if (!defined('ABSPATH')) {
    exit();
}

interface CCConstants {

    const PAGE_SUB_MENU = 'options-general.php';
    const PAGE_SETTINGS = 'comments_censure';
    const OPTION_VERSION = 'comments_censure_version';
    const OPTION_MAIN = 'comments_censure_options';
    const ACTION_REMOVE_WORD = 'ccRemoveWord';
    const ACTION_REMOVE_ALL = 'ccRemoveWords';
    const ACTION_IMPORT_OPTIONS = 'ccImportOptions';
    const ACTION_IMPORT_WORDS = 'ccImportWords';
    const ACTION_SEARCH_WORDS = 'ccAdminSearchWords';
    const ACTION_ADD_WORDS = 'ccAddWords';
    const ACTION_RESET_OPTIONS = 'ccResetOptions';
    const NONCE_RESET_OPTIONS = 'cc_reset_options_nonce';
    const UNCENSORED_WORD_DETECTED = 1;

}
