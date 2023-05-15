<?php

namespace app\users;

class connectDB
{
    private static $connect;
    private static $_instance;

    private static $db = [
        'host'     => 'localhost:8805',
        'dbname'   => 'slmax',
        'user'     => 'user',
        'password' => 'user'
    ];

    protected function __construct()
    {
        $this->connectDB();
    }

    protected function connectDB()
    {
        if (self::$connect instanceof \PDO) {
            return self::$connect;
        }
        self::$connect = new \PDO ("mysql:host=" . self::$db['host'] . ";dbname=" . self::$db['dbname'] . ";charset=utf8", self::$db['user'], self::$db['password']);
        self::$connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    static public function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getConnect()
    {
        return self::$connect;
    }

    public function query($sql, $type, array $data = null)
    {
        switch ($type) {
            case 'single':
                $row = self::$connect->prepare($sql);
                $row->execute($data);

                return $row->fetch();
            case 'array':
                $row = self::$connect->prepare($sql);
                $row->execute($data);

                return $row->fetchAll();
            case 'count':
                $row = self::$connect->prepare($sql);
                $row->execute($data);

                return $row->rowCount();
            case 'insert':
                $row = self::$connect->prepare($sql);
                $row->execute($data);

                return self::$connect->lastInsertId();
                break;
            case 'change':
                $row = self::$connect->prepare($sql);
                $row->execute($data);

                return $row->rowCount();
                break;
        }
    }


}