<?php

declare(strict_types=1);

namespace app\users;

use app\users\Users;
use app\users\UsersList;

error_reporting(E_ALL);
// Вывод ошибок
ini_set('display_errors', 'on');
//    ini_set('display_errors', 1);
ini_set('html_errors', 'on');

const BASE_PATH = __DIR__;
const CLASSES   = BASE_PATH . '/classes';

require_once CLASSES . '/Users.php';
require_once CLASSES . '/UsersList.php';
require_once CLASSES . '/User.php';
try {
    $r = new UsersList();
    print_r($r);
    echo $r::class . PHP_EOL;
    echo get_class($r) . PHP_EOL;

    echo $r->getClassDynamic($r) . PHP_EOL;
    echo UsersList::getClass() . PHP_EOL;
    echo UsersList::getClassName() . PHP_EOL;


    $a = new Users();
    echo $a->genderName((boolean)3441) . PHP_EOL;

    echo User::getClassName() . PHP_EOL;
    unset($a);

//    echo time();
    echo date('md') . PHP_EOL;
    echo date('Y-m-d') . PHP_EOL;
    echo PHP_EOL . PHP_EOL . PHP_EOL;

    echo Users::agePerson("1989/01/11");
} catch (\Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
}
