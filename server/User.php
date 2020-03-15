<?php

namespace EchoBot;

class User
{
    protected $resource_id;
    protected $user_id;
    protected $username;
    protected $role;

    public function __construct($resourceId)
    {
        $this->resource_id = $resourceId;
    }

    public function __destruct()
    {
        $this->resource_id = null;
        $this->user_error = null;
        $this->username = null;
        $this->role = null;
    }

    
    public function importUserInfo($user_id, $username, $role)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->role = $role;
    }

    public function getUserInfo()
    {
        return array(['resource_id'=>$this->resource_id, 'user_id' => $this->user_id, 'username' => $this->username, 'role' => $this->role]);
    }
}
