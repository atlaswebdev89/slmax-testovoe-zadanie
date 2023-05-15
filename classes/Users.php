<?php
declare(strict_types=1);

namespace app\users;

use Exception;

class Users
{

    protected int $id;
    public string $name;
    public string $surname;
    public string|null $date_birth;
    public $gender;
    public string|null $city_of_birth;

    public static string $men = "Муж";
    public static string $women = "Жен";
    protected $pdo;

    public static $count = 0;

    const TYPE_SINGLE = 'single';
    const TYPE_ARRAY  = 'array';
    const TYPE_COUNT  = 'count';
    const TYPE_CHANGE = 'change';
    const TYPE_INSERT = 'insert';
    const TABLE       = 'slamx';

    public function __construct(array|int $data = [])
    {
        // conect to Mysql
        $this->pdo = $this->connect();

        if (!empty($data) && gettype($data) == 'integer') {
            try {
                $this->getPerson($data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        if (gettype($data) == 'array') {
            $data = $this->spaceDeleteKeys($data);
            $data = $this->validate($data);
            $data = $this->formatterInput($data);

            $this->createPerson($data);
        }
    }


    public function connect()
    {
        require_once 'connectDB.php';

        return connectDB::getInstance();
    }


    protected function load(array|object $array): void
    {
        foreach ($array as $attr => $item) {
            $this->$attr = $item;
        }
    }

    protected function spaceDeleteKeys(array|object $data): array
    {
        // delete space in name key
        $new = [];
        foreach ($data as $key => $value) {
            $new[trim($key)] = $value;
        }
        $data = $new;
        unset($new);

        return $data;
    }

    protected function validate(array|object $data): array
    {
        if (isset($data['name']) && isset($data['surname'])) {
            if (!preg_match('/^[a-zA-Zа-яА-Я]+$/u', $data['name'])) {
                throw new Exception("Name must be only letter");
            }
            if (!preg_match('/^[a-zA-Zа-яА-Я]+$/u', $data['surname'])) {
                throw new Exception("SurName must be only letter");
            }
        }
        if (isset($data['gender']) && !in_array($data['gender'], ['1', '0'])) {
            throw new Exception("Set field Gender must be '1' or '0'");
        }

        return $data;
    }

    protected function formatterInput(array|object $data): array
    {
        $birth = (isset($data['date_birth']) && !empty($data['date_birth'])) ? date('Y-m-d', strtotime($data['date_birth'])) : null;

        return [
            'name'          => $data['name'] ?? '',
            'surname'       => $data['surname'] ?? '',
            'date_birth'    => $birth,
            'gender'        => $data['gender'] ?? null,
            'city_of_birth' => $data['city_of_birth'] ?? null
        ];
    }

    protected function formatterOutput(array|object $data)
    {
        return [
            'id'            => $data['id'],
            'name'          => $data['name'] ?? '',
            'surname'       => $data['surname'] ?? '',
            'date_birth'    => $data['date_birth'] ?? '',
            'gender'        => $data['gender'] ?? '',
            'city_of_birth' => $data['city_of_birth'] ?? null
        ];
    }

    public function formaterPerson(array|object $data = [])
    {
        $human     = new \stdClass();
        $attr      = get_object_vars($this);
        $attr      = $this->formatterInput($attr);
        $human->id = ($this->id) ?? '';
        unset($attr['pdo']);
        foreach ($attr as $key => $item) {
            $human->$key = $item;
        }
        if (in_array('age', $data)) {
            $human->age = self::agePerson($human->date_birth);
        }

        if (in_array('pretty_gender', $data)) {
            $human->gender = self::genderName(((bool)$attr['gender']));
        }


        return $human;
    }

    public function savePerson(array|object $data = [])
    {
        $attr = get_object_vars($this);
        $attr = $this->validate($attr);
        $attr = $this->formatterInput($attr);
        if (isset($this->id)) {
            $attr['id'] = $this->id;
            $this->updatePerson($attr);
        } else {
            $this->createPerson($attr);
        }
    }

    protected function createPerson(array|object $data)
    {
        $sql = "insert into `" . self::TABLE . "` (name, surname, date_birth, gender, city_of_birth) values (:name, :surname, :date_birth, :gender, :city_of_birth)";
        $id  = $this->pdo->query($sql, self::TYPE_INSERT, $data);

        if ($id) {
            $sql   = "select * from `" . self::TABLE . "` where id=:id";
            $query = $this->pdo->query($sql, self::TYPE_SINGLE, ['id' => (int)$id]);

            if (!empty($query)) {
                $query = $this->formatterOutput($query);
                $this->load($query);
            }
        }
    }

    protected function updatePerson(array|object $data)
    {
        $sql    = "update slamx set name=:name, surname=:surname, gender=:gender, date_birth=:date_birth, city_of_birth=:city_of_birth where id=:id";
        $result = $this->pdo->query($sql, self::TYPE_CHANGE, $data);
        if (!empty($result)) {
            echo PHP_EOL . "DATA UPDATE person with id=" . $data['id'] . PHP_EOL;
        }
    }

    protected function getPerson(int $id)
    {
        $sql    = "select * from `" . self::TABLE . "` where id=:id";
        $result = $this->pdo->query($sql, self::TYPE_SINGLE, ['id' => $id]);
        if (!empty($result)) {
            $result = $this->formatterOutput($result);
            $this->load($result);
        } else {
            throw new Exception("Not found person in databases with id=" . $id);
        }
    }

    public function deletePerson()
    {
        if (isset($this->id)) {
            $sql    = "delete from `" . self::TABLE . "` where id=:id";
            $result = $this->pdo->query($sql, self::TYPE_CHANGE, ['id' => $this->id]);
            if (!empty($result)) {
                $this->attrDelete();

                return true;
            }
        }
    }

    protected function attrDelete()
    {
        foreach ($this as $key => $attr) {
            if ($key !== 'pdo')
                unset($this->$key);
        }
    }


    public static function agePerson(string $date = null): int|null
    {
        if (!isset($date)) return null;
        $currentYear = date('Y');
        $yearPerson  = strtotime($date); //unixtime
        $age         = $currentYear - date('Y', $yearPerson);
        if (date('md', $yearPerson) > date('md')) {
            $age--;
        }

        return $age;
    }

    public static function genderName(bool|int $value): string
    {

        if (isset($value)) {
            return ($value) ? static::$men : static::$women;
        }
        throw new Exception("Don't return gender persons");
    }
}