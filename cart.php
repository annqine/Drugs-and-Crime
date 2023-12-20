<?php
class Cart
{
    protected $db;
    protected $items = [];

    public function count() { return count($this->items); }
    public function items() { return $this->items; }
    public function isEmpty() { return empty($this->items); }
    protected function get() {}
    public function clear() {}
    protected function deleteItem($i) {}
    protected function addItem($item) {}
    public function __construct($db)
    {
        $this->db = $db;
        $ids = [];

        foreach ($this->get() as $i => $v) {
            $ids[] = $v['id'];
        }

        if ($ids) {
            $drug = $this->db->find('drugs')->where('id', 'IN' , $ids)->rows();
            foreach ($drug as $v) {
                $drugInfo[$v['id']] = ['country' => $v['Country/Territory'], 'drug' => $v['Drug Group']];
            }

            $items = [];

            foreach ($this->get() as $i => $v) {
                if (isset($drugInfo[$v['id']])) {
                    $items[] = [
                        'id' => $v['id'],
                        'country' => $drugInfo[$v['id']]['country'],
                        'drug' => $drugInfo[$v['id']]['drug'],
                    ];
                }
            }

            $this->items = $items;
        }
    }


    public function remove($id)
    {
        foreach ($this->get() as $i => $v) {
            if ($v['id'] == $id) {
                $this->deleteItem($i);
            }
        }
    }

    public function add($id)
    {
        if (!is_numeric($id)) return;

        $drug = $this->db->find('drugs')->where('id', '=', $id)->limit(1)->one();

        if (!$drug) return;
        $f = false;

        foreach ($this->get() as $i => $v) {
            if ($v['id'] == $id) {
                $f = true;
            }
        }

        if (!$f) {
            $this->addItem(['id' => $id]);
        }
    }
}

class CartSession extends Cart
{
    protected $key = 'cart';

    public function __construct($db)
    {
        //session_start(); // Добавьте эту строку
        parent::__construct($db);
    } 

    public function isEmpty()
    {
        return (!isset($_SESSION[$this->key]) || empty($_SESSION[$this->key]));
    }

    protected function get()
    {
        if ($this->isEmpty()) return [];
        return $_SESSION[$this->key];
    }

    public function clear()
    {
        unset($_SESSION[$this->key]);
    }

    protected function deleteItem($i)
    {
        unset($_SESSION[$this->key][$i]);
    }

    protected function addItem($item)
    {
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }

        $_SESSION[$this->key][] = $item;
        var_dump($_SESSION[$this->key]);
    }
}

class CartCookies extends Cart
{
    protected $key = 'cart';
    protected $items = [];

    public function save()
    {
        setcookie($this->key, serialize($this->items), time() + 60 * 60 * 5);
    }

    public function isEmpty()
    {
        return (!isset($_COOKIE[$this->key]));
    }

    protected function get()
    {
        if ($this->isEmpty()) return null;

        if (!$this->items) {
            $this->items = unserialize($_COOKIE[$this->key]);
        }

        return $this->items;
    }

    public function clear()
    {
        unset($this->items);
        setcookie($this->key, '', time() - 3600);
    }

    protected function deleteItem($i)
    {
        unset($this->items[$i]);

        if (empty($this->items)) {
            $this->clear();
        } else {
            $this->save();
        }
    }

    protected function addItem($item)
    {
        $this->items[] = $item;
        $this->save();
    }
}
