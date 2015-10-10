<?php
    define("MESSAGE_QUEUE_KEY", 123);

    $message = "User: " . $_GET["message"];
    $messageQueue = msg_get_queue(MESSAGE_QUEUE_KEY);
    msg_send($messageQueue, 12, $message);

    echo "message sent";
?>
