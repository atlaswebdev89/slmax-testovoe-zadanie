<?php
declare(strict_types=1);

namespace app\users;

use Exception;

class Users
{

    public int $id;
    public string $name;
    public string $surname;
    public string $dateBirth;
    public bool $gender;
    public string $cityOfBirth;

    public static string $men = "Муж";
    public static string $women = "Жен";

    public function __construct()
    {
        echo "CLASS CREATED" . PHP_EOL;
    }

    public function __destruct()
    {
        echo "Object delete" . PHP_EOL;
    }

    public static function agePerson(string $date): int
    {
        $currentYear = date('Y');
        $yearPerson  = strtotime($date); //unixtime
        $age         = $currentYear - date('Y', $yearPerson);
        if (date('md', $yearPerson) > date('md')) {
            $age--;
        }

        return $age;
    }

    public static function genderName(bool $value): string
    {
        if (isset($value) && gettype($value) == 'boolean') {
            return ($value) ? static::$men : static::$women;
        }
        throw new Exception("Don't return sex persons");
    }
}