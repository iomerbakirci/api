<?php

use MatMuh\Table;

class TblToken extends Table
{
    protected $_name = 'tbl_token';
    protected $_primary = 'token';
    protected $_sequence = null;

    /**
     * Kullanıcının token'ı varsa bulur yoksa yeni ekler
     *
     * @param int $user_id
     * @return boolean|string
     */
    public function getToken($user_id)
    {
        $app = \Slim\Slim::getInstance();
        $client_key = $app->client_key;

        if(!$client_key || !$user_id)
            return false;

        $row = $this->select(array('where' => array('user_id' => $user_id, 'client_key' => $client_key, 'status' => 1), 'limit' => 1));

        if(!$row) {
            $token = md5(time() . '_' . $user_id . '_' . $client_key);

            if($this->insert(array('user_id' => $user_id, 'client_key' => $client_key, 'token' => $token)))
                return $token;
        }
        else
            return $row['token'];

        return false;
    }

    /**
     * Token'a bağlı kullanıcı bilgisini döndürür
     *
     * @param string $token
     * @return array|boolean
     */
    public function getUser($token)
    {
        $app = \Slim\Slim::getInstance();
        $client_key = $app->client_key;

        if(!$client_key || !$token)
            return false;

        $row = $this->select(array('where' => array('client_key' => $client_key, 'token' => $token, 'status' => 1), 'limit' => 1));

        if(!$row)
            return false;
        else {
            $tblUser = new TblUser();
            $user = $tblUser->selectRow($row['user_id']);
            return $user;
        }

        return false;
    }
}