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

//    echo PHP_EOL . PHP_EOL . PHP_EOL;
    for ($i = 0; $i < 20; $i++) {
        new Users(['name' => 'Иваны', 'surname   ' => 'Ацфацfe', 'date_birth' => '20-01-2017', 'gender' => 1]);
    }

    $pr1 = new Users(371);
    if ($pr1) {
        $pr1->name    = 'Коля';
        $pr1->surname = "Николаев";
        $pr1->savePerson();
    }
    $pr2 = new Users(['name' => 'Николай', 'surname   ' => 'Николаевич', 'date_birth' => '20-01-2000', 'gender' => 1]);
    if ($pr2) {
        $pr2->name    = 'Коля';
        $pr2->surname = "Николаев";
        $pr2->savePerson();
    }
    $age    = Users::agePerson($pr2->date_birth);
    $gender = Users::genderName($pr2->gender);
    echo $age . " " . $gender . PHP_EOL;

    $k   = new UsersList();
    $vf1 = new UsersList(['condition' => 'more']);
    $vf2 = new UsersList(['condition' => 'less', 'id' => 20]);
    $vf3 = new UsersList(['condition' => 'noequal', 'id' => 20]);

    $persons = $k->getPersonsArray();
    echo $k->deletePersons();
    echo "DONE:" . PHP_EOL;
} catch (\Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
}
