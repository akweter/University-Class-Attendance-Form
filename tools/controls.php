<?php
    $arr_cookie_options = array (
        'expires' => time() + 60*60*24, // Cookie exist for a day
        'path' => '../', 
        'domain' => '.localhost', // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        'httponly' => true,    // or false
        'samesite' => 'None' // None || Lax  || Strict
        );
?>