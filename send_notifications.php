<?php
function sendEmail($to, $subject, $message){
    $headers = "From: Royal Drapes <noreply@royaldrapes.com>";
    mail($to, $subject, $message, $headers);
}

function sendSMS($mobile, $message){
    // API READY (example)
    // file_get_contents("https://smsapi.com/send?...");

    // For now (LOG)
    file_put_contents("../sms_log.txt", "$mobile : $message\n", FILE_APPEND);
}
