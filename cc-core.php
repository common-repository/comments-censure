<?php

/**
 * Plugin Name: Comments Censure
 * Description: The plugin filters uncensored comments before showing them to end-users.
 * Version: 1.0.2
 * Author: gVectors Team
 * Author URI: http://gvectors.com/
 * Text Domain: comments-censure
 * Domain Path: /languages/
 */
if (!defined('ABSPATH')) {
    exit();
}

define('CC_DIR_PATH', dirname(__FILE__));
define('CC_DIR_NAME', basename(CC_DIR_PATH));

include_once 'includes/cc-constants.php';
include_once 'includes/cc-dbManager.php';
include_once 'options/cc-optionsSerialized.php';
include_once 'options/cc-options.php';
include_once 'utils/cc-helper.php';

if (!class_exists('CommentCensure')) {

    class CommentCensure implements CCConstants {

        private $version;
        private $dbManager;
        private $options;
        private $optionsSerialized;
        private $helper;
        private $phpversion;
        public static $ENT = ENT_QUOTES;

        public function __construct() {
            $this->phpversion = phpversion();
            $this->version = get_option(self::OPTION_VERSION);
            if (!$this->version) {
                $this->version = '1.0.0';
                add_option(self::OPTION_VERSION, $this->version);
            }
            $this->dbManager = new CCDBManager();
            $this->optionsSerialized = new CCOptionsSerialized($this->dbManager);
            $this->options = new CCOptions($this->dbManager, $this->optionsSerialized);
            $this->helper = new CCHelper($this->dbManager, $this->optionsSerialized);
            register_activation_hook(__FILE__, array($this->dbManager, 'createTables'));
            add_action('wpmu_new_blog', array(&$this->dbManager, 'onNewBlog'), 10, 6);
            add_filter('wpmu_drop_tables', array(&$this->dbManager, 'onDeleteBlog'));
            add_action('admin_notices', array(&$this, 'notices'));
            add_action('admin_init', array(&$this, 'version'));
            add_action('plugins_loaded', array(&$this, 'textDomain'));
            add_action('admin_menu', array(&$this, 'addOptionMenu'), 99);
            add_action('admin_enqueue_scripts', array(&$this, 'adminOptions'));
            add_action('wp_ajax_' . self::ACTION_REMOVE_WORD, array(&$this->helper, 'removeWord'));
            add_action('wp_ajax_' . self::ACTION_SEARCH_WORDS, array(&$this->helper, 'adminSearchWords'));
            add_action('admin_post_' . self::ACTION_REMOVE_ALL, array(&$this->helper, 'removeWords'));
            add_action('admin_post_' . self::ACTION_ADD_WORDS, array($this->helper, 'addWords'));
            add_action('admin_post_' . self::ACTION_RESET_OPTIONS, array($this->helper, 'resetOptions'));
            add_filter('comment_text', array(&$this->helper, 'searchAndReplace'));
            if ($this->optionsSerialized->isFilterEmail) {
                add_filter('wp_mail', array(&$this->helper, 'emailSearchAndReplace'));
            }

            add_action('wp_ajax_' . self::ACTION_IMPORT_OPTIONS, array(&$this->helper, 'importOptions'));
            add_action('wp_ajax_' . self::ACTION_IMPORT_WORDS, array(&$this->helper, 'importWords'));
            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array(&$this, 'settingsLink'));

            add_filter('pre_comment_approved', array(&$this->helper, 'checkComment'), 10, 2);
            add_action('comment_post', array(&$this->helper, 'emailToUsers'), 10, 3);

            if (version_compare($this->phpversion, '5.4.0', '>=') && defined(ENT_HTML5)) {
                self::$ENT = ENT_HTML5;
            }
        }

        public function notices() {
            if (current_user_can('manage_options') && is_plugin_active(CC_DIR_NAME . '/cc-core.php') && !$this->dbManager->getSearchWords()) {
                $url = wp_nonce_url(admin_url("admin-post.php/?action=" . self::ACTION_ADD_WORDS), self::ACTION_ADD_WORDS);
                $addWords = "<a href='$url'>" . __('Add default words', 'comments-censure') . "</a>";
                echo "<div class='error' style='padding:10px;'>" . __('Comments Censure:', 'comments-censure') . " $addWords</div>";
            }
        }

        public function version() {
            $pluginData = get_plugin_data(__FILE__);
            if (version_compare($pluginData['Version'], $this->version, '>')) {
                $options = get_option(self::PAGE_SETTINGS);
                $this->addNewOptions($options);
                update_option(self::OPTION_VERSION, $pluginData['Version']);
            }
        }

        private function addNewOptions($options) {
            $this->optionsSerialized->initOptions($options);
            $newOptions = $this->optionsSerialized->toArray();
            update_option(self::PAGE_SETTINGS, $newOptions);
        }

        public function textDomain() {
            load_plugin_textdomain('comments-censure', false, CC_DIR_NAME . '/languages/');
            $rW = esc_html(wp_unslash(trim($this->optionsSerialized->globalReplacement)));
            $this->optionsSerialized->search = $this->dbManager->getSearchWords();
            $this->optionsSerialized->replace = ($this->optionsSerialized->isGlobalReplacement && trim($this->optionsSerialized->globalReplacement)) ? array($rW) : $this->dbManager->getReplaceWords();
        }

        public function adminOptions() {
            $args = array(
                'ccAjaxUrl' => admin_url('admin-ajax.php'),
                'mainPage' => self::PAGE_SUB_MENU . '?page=' . self::PAGE_SETTINGS,
                'actionRemoveWord' => self::ACTION_REMOVE_WORD,
                'removeAll' => __('Do you really want remove all search/replace combinations', 'comments-censure'),
                'importOptions' => __('Do you really want import new options', 'comments-censure'),
                'actionImportOptions' => self::ACTION_IMPORT_OPTIONS,
                'importOptionsEmpty' => __('Options can not be empty!', 'comments-censure'),
                'importWords' => __('Do you really want import new words', 'comments-censure'),
                'actionImportWords' => self::ACTION_IMPORT_WORDS,
                'importWordsEmpty' => __('Words can not be empty!', 'comments-censure'),
                'actionSearchWords' => self::ACTION_SEARCH_WORDS,
                'searchTextLength' => __('Search text cannot be empty!', 'comments-censure'),
                'searchNoResult' => __('No result', 'comments-censure'),
            );
            if (is_rtl()) {
                wp_register_style('cc-options-rtl-css', plugins_url(CC_DIR_NAME . '/assets/css/cc-options-rtl.css'));
                wp_enqueue_style('cc-options-rtl-css');
            } else {
                wp_register_style('cc-options-css', plugins_url(CC_DIR_NAME . '/assets/css/cc-options.css'));
                wp_enqueue_style('cc-options-css');
            }
            wp_register_script('cc-options-js', plugins_url(CC_DIR_NAME . '/assets/js/cc-options.js'), array('jquery'));
            wp_enqueue_script('cc-options-js');
            wp_localize_script('cc-options-js', 'ccJsObj', $args);
        }

        public function addOptionMenu() {
            $menuTitle = __('Comments Censure', 'comments-censure');
            add_submenu_page(self::PAGE_SUB_MENU, $menuTitle, $menuTitle, 'manage_options', self::PAGE_SETTINGS, array(&$this->options, 'mainForm'));
        }

        public function settingsLink($links) {
            $settingsLink = '<a href="' . self::PAGE_SUB_MENU . '?page=' . self::PAGE_SETTINGS . '">' . __('Settings', 'comments-censure') . '</a>';
            array_unshift($links, $settingsLink);
            return $links;
        }

    }

    $commentCensure = new CommentCensure();
} else {
    echo "Class CommentCensure already exists";
}