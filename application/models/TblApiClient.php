<?php
use MatMuh\Table;

class TblApiClient extends Table
{
    protected $_name = 'tbl_api_client';
    protected $_primary = 'client_key';
    protected $_sequence = null;

    public function check($data)
    {
        $error = "";

        if(!$data['client_key'])
            $error = "Client Key boş olamaz!";
        else if(!$data['client_secret'])
            $error = "Client Secret boş olamaz!";

        if($error)
            return array('message' => $error);

        $row = $this->selectRow($data['client_key']);

        if($row['client_secret'] == $data['client_secret'])
            return true;

        return array('status' => 'error', 'message' => "Client bilgileri hatalı!");
    }
}