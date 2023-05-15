<?php

namespace app\users;

use app\users\Users;
use Exception;

class UsersList
{
    public static $className = __CLASS__;
    public $humas = [];
    const TABLE      = 'slamx';
    const TYPE_ARRAY = 'array';

    public function __construct(array|object $where = [])
    {
        if (!class_exists(Users::class)) {
            throw new Exception("Not found class: " . Users::class);
        }
        $this->pdo   = self::connect();
        $this->humas = $this->getIdPersonsId($where);
    }


    public static function connect()
    {
        require_once 'connectDB.php';

        return connectDB::getInstance();
    }

    protected function getIdPersonsId($where)
    {
        if (isset($where['condition'])) {
            switch ($where['condition']) {
                case 'more':
                    $sql = "select `id` from " . self::TABLE . " where id >:id";
                    break;
                case 'less':
                    $sql = "select `id` from " . self::TABLE . " where id <:id";
                    break;
                case 'noequal':
                    $sql = "select `id` from " . self::TABLE . " where id !=:id";
                    break;
            }

            return $this->pdo->query($sql, self::TYPE_ARRAY, ['id' => $where['id'] ?? 0]);
        } else {
            $sql    = "select `id` from " . self::TABLE;
            $result = $this->pdo->query($sql, self::TYPE_ARRAY);
            $result = $this->formatter($result);

            return $result;
        }
    }

    protected function formatter(array $data): array
    {
        if (!empty($data)) {
            $array = [];
            foreach ($data as $key => $value) {
                $array[] = $value['id'];
            }

            return $array;
        }
    }

    public function getPersonsArray()
    {
        $result = [];
        if (!empty($this->humas)) {
            foreach ($this->humas as $key => $id) {
                $person = new Users($id);
                if ($person) {
                    $result[] = $person;
                }
            }
        }

        return $result;
    }

    public function deletePersons()
    {
        $count = 0;
        if (!empty($this->humas)) {
            foreach ($this->humas as $key => $id) {
                $person = new Users($id);
                if ($person->deletePerson()) $count++;
            }
        }
        echo PHP_EOL . "delete " . $count . " persons" . PHP_EOL;
    }
}