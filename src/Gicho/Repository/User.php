<?php


namespace Gicho\Repository;


class User
{
    protected $data = [
        'ca' => [
            'username' => 'ca',
            'name' => 'Chris Armitage',
        ],
        'hl' => [
            'username' => 'hl',
            'name' => 'Howard Lovecraft',
        ],
        'hw' => [
            'username' => 'hw',
            'name' => 'Herbert West',
        ],
        'cw' => [
            'username' => 'cw',
            'name' => 'Charles Ward',
        ],
    ];

    public function getAll()
    {
        return array_values($this->data);
    }

    public function get($username)
    {
        return $this->data[$username];
    }
}
