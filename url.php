<?php

include 'func.inc.php';
    
    if (isset($_POST['url'])) {
        $url = trim($_POST['url']);

        if (empty($url)) {
            echo 'error_no_url';
        } else if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            echo 'error_invalid_url';
        } else if (is_min($url)) {
            echo 'error_is_min';
        } else {
            while (!code_exists($code = gen_code())) {
                echo 'http://dragit.in/'.shorten($url, $code);
                break 1;
            }
        }
    }
?>
