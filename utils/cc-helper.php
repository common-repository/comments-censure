<?php

if (!defined('ABSPATH')) {
    exit();
}

class CCHelper implements CCConstants {

    private $dbManager;
    private $optionsSerialized;
    private $isUnwanted = false;

    public function __construct($dbManager, $optionsSerialized) {
        $this->dbManager = $dbManager;
        $this->optionsSerialized = $optionsSerialized;
    }

    public static function makeCensoredWord($word, $char) {
        $word = trim($word);
        $length = function_exists('mb_strlen') ? mb_strlen($word) : strlen($word);
        if ($length > 2) {
            $first = function_exists('mb_substr') ? mb_substr($word, 0, 1) : substr($word, 0, 1);
            $repeat = str_repeat($char, $length - 2);
            $last = function_exists('mb_substr') ? mb_substr($word, $length - 1, 1) : substr($word, $length - 1, 1);
            $replaced = $first . $repeat . $last;
            return $replaced;
        }
        return $word;
    }

    public static function matrixToArray($result) {
        $r = array();
        if ($result && is_array($result)) {
            foreach ($result as $res) {
                $r[] = $res[0];
            }
        }
        return $r;
    }

    public static function sortWordsByLength(&$array) {
        usort($array, function($a, $b) {
            $res = function_exists('mb_strlen') ? mb_strlen($b) - mb_strlen($a) : strlen($b) - strlen($a);
            return $res;
        });
    }

    public function removeWord() {
        $msgArray = array('code' => 0);
        if (isset($_POST['ccAjaxData']) && $data = trim($_POST['ccAjaxData'])) {
            parse_str($data);
            $id = intval(trim($id));
            $this->dbManager->removeWords($id);
            $msgArray['code'] = 1;
        }
        wp_die(json_encode($msgArray));
    }

    public function removeWords() {
        $this->dbManager->removeWords();
    }

    public function searchAndReplace($comment) {
        if (trim($comment) && $this->optionsSerialized->search && ($this->optionsSerialized->replace || $this->optionsSerialized->isGlobalReplacement)) {
            $comment = html_entity_decode($comment, CommentCensure::$ENT, "UTF-8");
            $s = $this->optionsSerialized->search;
            $r = $this->optionsSerialized->replace;
            if ($this->optionsSerialized->isGlobalReplacement && $this->optionsSerialized->globalReplacement) {
                $words = '';
                foreach ($this->optionsSerialized->search as $s) {
                    $words .= preg_quote($s) . "|";
                }
                $words = trim($words, "|");
                $pattern = "#\b($words)\b(?=[^>]*(<|$))#isu";
                $comment = preg_replace($pattern, $r[0], $comment);
            } else if (count($s) == count($r)) {
                for ($i = 0; $i < count($s); $i++) {
                    $searchThis = preg_quote(wp_unslash($s[$i]));
                    $replaceWith = wp_unslash($r[$i]);
                    $pattern = "#\b($searchThis)\b(?=[^>]*(<|$))#isu";
                    $comment = preg_replace($pattern, $replaceWith, $comment);
                }
            }
        }
        return $comment;
    }

    public function emailSearchAndReplace($mailData) {
        if (isset($mailData['message'])) {
            $mailData['message'] = $this->searchAndReplace($mailData['message']);
        }
        return $mailData;
    }

    public function importOptions() {
        $msg = __('Option not imported', 'comments-censure');
        if (isset($_POST['ccAjaxData']) && current_user_can('manage_options')) {
            $data = wp_unslash(trim($_POST['ccAjaxData']));
            if ($data && ($o = maybe_unserialize($data))) {
                if ($o && is_array($o) && update_option(self::OPTION_MAIN, maybe_serialize($o))) {
                    $msg = __('Option imported successfully', 'comments-censure');
                }
            }
        }
        wp_die($msg);
    }

    public function importWords() {
        $msg = __('Words not imported', 'comments-censure');
        if (isset($_POST['ccAjaxData']) && current_user_can('manage_options')) {
            $data = wp_unslash(trim($_POST['ccAjaxData']));
            $data = preg_split("#((\r?\n)|(\r\n?))#isu", $data);
            if ($data && is_array($data)) {
                $words = array();
                foreach ($data as $d) {
                    $line = explode('=', $d);
                    if ($line && is_array($line) && count($line) == 2) {
                        $words[] = array('search' => $line[0], 'replace' => $line[1]);
                    }
                }
                if ($this->dbManager->addWords($words)) {
                    $msg = __('Words imported successfully', 'comments-censure');
                }
            }
        }
        wp_die($msg);
    }

    public function addWords() {
        if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::ACTION_ADD_WORDS)) {
            $path = CC_DIR_PATH . '/assets/words/words-' . get_locale() . '.txt';
            $path = function_exists('file_exists') && file_exists($path) ? $path : CC_DIR_PATH . '/assets/words/words-en_US.txt';
            $this->dbManager->addDefaultWords($path);
            exit(wp_redirect(get_admin_url() . self::PAGE_SUB_MENU . '?page=' . self::PAGE_SETTINGS));
        }
    }

    public function resetOptions() {
        if (current_user_can('manage_options') && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::NONCE_RESET_OPTIONS)) {
            delete_option(self::OPTION_MAIN);
            $this->optionsSerialized->addOptions();
            $this->optionsSerialized->initOptions(get_option(self::OPTION_MAIN));
        }
        exit(wp_redirect(admin_url(self::PAGE_SUB_MENU . '?page=' . self::PAGE_SETTINGS)));
    }

    public function adminSearchWords() {
        $response = array('code' => 0);
        if (isset($_POST['ccAjaxData']) && trim($_POST['ccAjaxData'])) {
            $data = array();
            parse_str($_POST['ccAjaxData'], $data);
            if ($data && is_array($data) && isset($data['s']) && ($s = trim($data['s']))) {
                $result = $this->dbManager->adminGetSearchWords($s);
                if ($result && is_array($result)) {
                    $html = '';
                    $isGlobalReplacement = isset($this->optionsSerialized->isGlobalReplacement) ? $this->optionsSerialized->isGlobalReplacement : 0;
                    $disabled = $isGlobalReplacement ? 'disabled="disabled"' : '';
                    foreach ($result as $res) {
                        $id = intval($res['id']);
                        $search = esc_attr($res['search']);
                        $replace = esc_attr($res['replace']);
                        $html .= "<div id='ccrow-$id' class='cc-row'>";
                        $html .= "<div class='cc-col cc-flex-grow'><input value='$search' name='search[]' class='cc-search cc-text-field w100' placeholder='" . __('Search', 'comments-censure') . "' type='text'></div>";
                        $html .= "<div class='cc-col cc-flex-grow cc-col-replace'>";
                        $html .= "<input id='cc-replace-$id' value='$replace' name='replace[]' class='cc-text-field cc-replace w100' placeholder='" . __('Replace (Leave empty to autogenerate)', 'comments-censure') . "' $disabled type='text'>";
                        $html .= "<span id='cc-replace-image-$id' title='" . __('Replace with image(PRO version)', 'comments-censure') . "' class='dashicons dashicons-format-image cc-replace-image  cc-disabled'>&nbsp;</span>";
                        $html .= "</div>";
                        $html .= "<div class='cc-col cc-flex-shrink'><button type='button' id='ccremove-$id' class='button button-primary cc-remove'>" . __('Remove', 'ceomment-censure') . "</button> <input name='wordIds[]' value='$id' type='hidden'></div>";
                        $html .= "</div>";
                    }
                    $response['code'] = 1;
                    $response['data'] = $html;
                }
            }
        }
        wp_die(json_encode($response));
    }

    public function checkComment($approved, $commentdata) {
        $content = $commentdata['comment_content'];
        $this->isUnwanted = $this->isUnwanted($content);
        return $approved;
    }

    private function isUnwanted($content) {
        $content = html_entity_decode($content, CommentCensure::$ENT, "UTF-8");
        if (trim($content) && $this->optionsSerialized->search && is_array($this->optionsSerialized->search)) {
            $words = '';
            foreach ($this->optionsSerialized->search as $s) {
                $words .= preg_quote($s) . "|";
            }
            $words = trim($words, "|");
            $pattern = "#\b($words)\b(?=[^>]*(<|$))#isu";
            $matches = array();
            if (preg_match($pattern, $content, $matches)) {
                $data = array();
                $data['err_code'] = self::UNCENSORED_WORD_DETECTED;
                $data['err_found'] = $matches[1];
                return $data;
            }
        }

        return false;
    }

    public function emailToUsers($comment_ID, $approved, $commentdata) {
        /*
         * in this case the email will be send on any comment status
         * you can add filter and return statuses on which the email will be send to users
         * example array(1, 0)
         */
        $notifyOnlyStatuses = apply_filters('comments_censure_pro_is_notify', array(0, 1, 'spam'), $approved, $commentdata);
        if (is_array($this->isUnwanted) && is_array($notifyOnlyStatuses) && in_array($approved, $notifyOnlyStatuses)) {
            $items = array_map('trim', explode(',', $this->optionsSerialized->usersToNotify));
            if ($items && is_array($items)) {
                $mailingDetails = $this->getEmailDetails($comment_ID);
                $receivers = '';
                foreach ($items as $item) {
                    if (filter_var($item, FILTER_VALIDATE_EMAIL) && $item != $commentdata['comment_author_email']) {
                        $receivers .= "$item,";
                    }
                }
                if ($receivers) {
                    wp_mail(trim($receivers, ','), $mailingDetails['subject'], $mailingDetails['message'], $mailingDetails['headers']);
                }
            }
        }
    }

    private function getEmailDetails($comment_ID) {
        $editCommentLink = admin_url('comment.php?action=editcomment&amp;c=') . $comment_ID;
        $editCommentLink = apply_filters('get_edit_comment_link', $editCommentLink);
        $emailDetails = array();
        $headers = array();
        $contentType = apply_filters('wp_mail_content_type', 'text/html');
        $fromName = apply_filters('wp_mail_from_name', get_option('blogname'));
        $fromEmail = apply_filters('wp_mail_from', get_option('admin_email'));
        $contentTypeHeader = "Content-Type: $contentType; charset=UTF-8";
        $fromHeader = "From: $fromName <" . $fromEmail . "> \r\n";
        $headers[] = $contentTypeHeader;
        $headers[] = $fromHeader;
        $emailDetails['headers'] = $headers;
        $emailDetails['contentType'] = $contentType;
        $emailDetails['fromName'] = $fromName;
        $emailDetails['fromEmail'] = $fromEmail;
        $emailDetails['subject'] = $this->optionsSerialized->phraseEmailSubject ? $this->optionsSerialized->phraseEmailSubject : __('New unwanted comment', 'comments-censure');
        $emailDetails['message'] = $this->optionsSerialized->phraseEmailContent ? sprintf($this->optionsSerialized->phraseEmailContent, $editCommentLink) : sprintf(__('New unwanted comment was posted, to moderate please <a href="%s" target="_blank">Click here</a>', 'comments-censure'), $editCommentLink);
        return $emailDetails;
    }

}
