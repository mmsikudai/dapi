<?php
require_once 'ClassAutoLoad.php';

$mailCnt = [
    'name_from' => 'Alex Okama',
    'mail_from' => 'alex@gmail.com',
    'name_to' => 'Mazziq Studios',
    'mail_to' => 'mazziqstudios@gmail.com',
    'subject' => 'Greetings from BBIT DevOps',
    'body' => 'Welcome to <b>BBIT DevOps</b>! This is a new semester of learning and growth.'
];

$ObjSendMail->Send_Mail($conf, $mailCnt);