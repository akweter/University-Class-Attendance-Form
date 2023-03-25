<?php
error_reporting(E_ERROR || E_WARNING);
session_start();


    // Setting date and tome format
    $date = new DateTime();
    $date->setTimeZone(new DateTimeZone('Africa/Accra'));
    $current_time = $date->format("H:i:s A");
    $current_date = $date->format('D d m Y');

?>