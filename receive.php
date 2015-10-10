<?php
    define("MESSAGE_QUEUE_KEY", 123);
    define("SEMAPHORE_KEY", 765);
    define("SHARED_MEMORY_KEY", 258);
    define("VARIABLE_KEY", 409);

    $semaphore = sem_get(SEMAPHORE_KEY);
    sem_acquire($semaphore);

    $sharedMemory = shm_attach(SHARED_MEMORY_KEY);

    $messageSharer = false;

    if (!shm_has_var($sharedMemory, VARIABLE_KEY)) {
        $messageSharer = true;
        $messageQueue = msg_get_queue(MESSAGE_QUEUE_KEY);
        msg_receive($messageQueue, 0, $messageType, 1024, $message);
        shm_put_var($sharedMemory, VARIABLE_KEY, $message);
        sem_release($semaphore);
    }

    if ($messageSharer) {
        sem_acquire($semaphore);
    }

    $message = shm_get_var($sharedMemory, VARIABLE_KEY);

    sem_release($semaphore);

    if ($messageSharer) {
        sem_acquire($semaphore);
        shm_remove_var($sharedMemory, VARIABLE_KEY);
        sem_release($semaphore);
    }

    echo $message;
?>
