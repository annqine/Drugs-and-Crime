<?php

class App
{
    private static $db;
    private static $cart = null;
    static function cart() {
        if (self::$cart == null) {
            self::$cart = new CartSession(self::$db);
        }
        return self::$cart;
    }

    static function db() {
        return self::$db;
    }

    static function init() {
        self::$db = new DB([
            'host' => 'localhost',
            'user' => 'root',
            'password' => 'Borodina2005',
            'db' => 'relational_base',
        ]);

    }
}
