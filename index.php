<?php

declare(strict_types=1);

namespace app\users;

use app\users\Users;
use app\users\UsersList;

error_reporting(E_ALL);
// Вывод ошибок
ini_set('display_errors', 'on');
//    ini_set('display_errors', 1);
ini_set('html_errors', 'off');

const BASE_PATH = __DIR__;
const CLASSES   = BASE_PATH . '/classes';

require_once CLASSES . '/Users.php';
require_once CLASSES . '/UsersList.php';
require_once CLASSES . '/User.php';
try {

//    $r = new UsersList();
//    print_r($r);
//    echo $r::class . PHP_EOL;
//    echo get_class($r) . PHP_EOL;
//
//    echo $r->getClassDynamic($r) . PHP_EOL;
//    echo UsersList::getClass() . PHP_EOL;
//    echo UsersList::getClassName() . PHP_EOL;
//
//
//    $a = new Users(['123' => 'asd']);
//    echo $a->genderName((boolean)344) . PHP_EOL;
//
//    echo User::getClassName() . PHP_EOL;
//
//
////    $b              = new Users(123);
////    $b->cityOfBirth = "Brest";
////    $b->dateBirth   = "1989-01-11";
////    $b->gender      = true;
////    $b->name        = "Dima";
////
////    foreach ($b as $key => $item) {
////        print_r($item);
////    }
//    $d = new Users(['ety' => 123]);
//    $k = new Users(['ety' => 123]);
//
//////    echo time();
////    echo date('md') . PHP_EOL;
////    echo date('Y-m-d') . PHP_EOL;
//    echo PHP_EOL . PHP_EOL . PHP_EOL;
//
//    echo Users::agePerson(date: "1989/01/11");
//
//
//    echo PHP_EOL . PHP_EOL . PHP_EOL;

    $c = new Users(['name' => 'Иваны', 'surname   ' => 'Ацфацfe', 'date_birth' => '20-01-2017', 'gender' => 0]);
    print_r($c);
//    $c->deletePerson();
    print_r($c);
    $c->gender = '1';
    $c->name   = "Коля";
    $c->savePerson();
    print_r($c);
    $r = new Users(96);
    print_r($r);
    echo $c->deletePerson();
} catch (\Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
}
