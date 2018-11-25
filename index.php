<?php

require_once 'vendor/autoload.php';
require_once 'config/main.php';

//(new App\TelegramBot())->setWebhook();
(new App\TelegramBot())->sendMessage();