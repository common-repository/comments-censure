<?php

if (!defined('ABSPATH')) {
    exit();
}

class CCDBManager implements CCConstants {

    private $db;
    private $tblBadWords;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->tblBadWords = $wpdb->prefix . 'cc_bad_words';
    }

    /**
     * creates table for censored words
     */
    public function createTables($networkWide) {
        global $wpdb;
        if (is_multisite() && $networkWide) {
            $blogIds = $this->db->get_col("SELECT `blog_id` FROM {$wpdb->blogs}");
            foreach ($blogIds as $blogId) {
                switch_to_blog($blogId);
                $this->createTable();
                restore_current_blog();
            }
        } else {
            $this->createTable();
        }
    }

    private function createTable() {
        global $wpdb;
        $wordsTable = $wpdb->prefix . 'cc_bad_words';
        if (!($wpdb->get_var("SHOW TABLES LIKE '$wordsTable'"))) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $sql = "CREATE TABLE $wordsTable(`id` INT(11) NOT NULL AUTO_INCREMENT, `search` VARCHAR(255) NOT NULL, `replace` VARCHAR(255) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `search` (`search`)) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
            if (dbDelta($sql)) {
                $path = CC_DIR_PATH . '/assets/words/words-' . get_locale() . '.txt';
                $path = function_exists('file_exists') && file_exists($path) ? $path : '';
                $this->addDefaultWords($path);
            }
        }
    }

    public function onNewBlog($blogId, $userId, $domain, $path, $siteId, $meta) {
        if (is_plugin_active_for_network(CC_DIR_NAME . '/cc-core.php')) {
            switch_to_blog($blogId);
            $this->createTable();
            restore_current_blog();
        }
    }

    function onDeleteBlog($tables) {
        global $wpdb;
        $tables[] = $wpdb->prefix . 'cc_bad_words';
        return $tables;
    }

    public function getIncrementValue() {
        $sql = "SHOW TABLE STATUS LIKE '" . $this->tblBadWords . "';";
        $tblInfo = $this->db->get_row($sql, ARRAY_A);
        return $tblInfo['Auto_increment'];
    }

    public function getCensoredWords($perPage = 0, $offset = 0) {
        if ($perPage) {
            $sql = $this->db->prepare("SELECT * FROM $this->tblBadWords ORDER BY `id` ASC LIMIT %d OFFSET %d;", $perPage, $offset);
        } else {
            $sql = "SELECT COUNT(*) AS `count` FROM $this->tblBadWords ORDER BY `id` ASC;";
        }
        return $this->db->get_results($sql, ARRAY_A);
    }

    public function addDefaultWords($path) {
        $words = $this->getDefaultWords($path);
        foreach ($words as $word) {
            $sW = wp_unslash(trim($word['search']));
            $rW = wp_unslash(trim($word['replace']));
            if ($this->isWordExists($sW)) {
                continue;
            }
            if ($sW) {
                $sql = $this->db->prepare("INSERT INTO $this->tblBadWords(`search`, `replace`) VALUES(%s, %s);", $sW, $rW);
                $this->db->query($sql);
            }
        }
    }

    private function getDefaultWords($path) {
        $words = array();
        if ((function_exists('file_exists') && file_exists($path)) && (function_exists('file_get_contents') && ($file = file_get_contents($path)))) {
            $content = $file;
        } else {
            $content = false;
        }
        if ($content) {
            $allWords = preg_split("#((\r?\n)|(\r\n?))#isu", $content);
            if ($allWords && is_array($allWords)) {
                foreach ($allWords as $s) {
                    $sW = trim($s);
                    if ($sW) {
                        $rW = CCHelper::makeCensoredWord($sW, '*');
                        $words[] = array('search' => $sW, 'replace' => $rW);
                    }
                }
            }
        }
        return $words;
    }

    /**
     * @param type $id the search and replace words id in db
     * @return type true on success false otherwise
     */
    public function removeWords($id = 0) {
        if (current_user_can('manage_options')) {
            if ($id) {
                $sql = $this->db->prepare("DELETE FROM $this->tblBadWords WHERE `id` = %d", $id);
                $this->db->query($sql);
            } else {
                if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::ACTION_REMOVE_ALL)) {
                    $sql = "TRUNCATE $this->tblBadWords;";
                    $this->db->query($sql);
                    wp_redirect(get_admin_url() . self::PAGE_SUB_MENU . '?page=' . self::PAGE_SETTINGS);
                }
            }
        }
    }

    public function getSearchWords() {
        $sql = "SELECT `search` FROM $this->tblBadWords ORDER BY `id` ASC;";
        return CCHelper::matrixToArray($this->db->get_results($sql, ARRAY_N));
    }

    public function getReplaceWords() {
        $sql = "SELECT `replace` FROM $this->tblBadWords ORDER BY `id` ASC;";
        return CCHelper::matrixToArray($this->db->get_results($sql, ARRAY_N));
    }

    public function addWords($words) {
        if ($words && is_array($words)) {
            foreach ($words as $word) {
                $sW = wp_unslash(trim($word['search']));
                $rW = wp_unslash(trim($word['replace']));
                if ($sW) {
                    if ($wId = intval($word['id'])) {
                        $sql = $this->db->prepare("UPDATE $this->tblBadWords SET `search` = %s, `replace` = %s WHERE `id` = %d;", $sW, $rW, $wId);
                    } else {
                        if ($this->isWordExists($sW)) {
                            continue;
                        }
                        $sql = $this->db->prepare("INSERT INTO $this->tblBadWords(`search`, `replace`) VALUES(%s, %s);", $sW, $rW);
                    }
                    $this->db->query($sql);
                }
            }
            return true;
        }
        return false;
    }

    public function isWordExists($word) {
        if (!$word) {
            return false;
        }
        $isExists = $this->db->prepare("SELECT `id` FROM $this->tblBadWords WHERE `search` = %s;", $word);
        return $this->db->get_var($isExists);
    }

    public function adminGetSearchWords($s) {
        $result = false;
        if ($s) {
            $search = "%" . $this->db->esc_like($s) . "%";
            $sql = "SELECT * FROM $this->tblBadWords WHERE `search` LIKE '$search' ORDER BY `search` ASC;";
            $result = $this->db->get_results($sql, ARRAY_A);
        }
        return $result;
    }

}
