<?php
use MatMuh\Table;

class TblUser extends Table
{
    protected $_name = 'tbl_user';
    protected $_primary = 'id';
    protected $_sequence = 'id';
    protected $_cols = array('id', 'facebook_id', 'first_name', 'last_name', 'email', 'password', 'gsm', 'date_of_birth', 'gender', 'city', 'profile_picture', 'role_id', 'status');

    /**
     * Kullanıcı kayıt işlemi
     *
     * @param array $data
     * @return array
     */
    public function register($data)
    {
        $error = "";

        if(!$data['first_name'])
            $error = "Ad alanı boş olamaz!";
        else if(!$data['last_name'])
            $error = "Soyad alanı boş olamaz!";
        else if(!$data['email'])
            $error = "E-posta alanı boş olamaz!";
        else if(!$data['password'])
            $error = "Parola alanı boş olamaz!";
        else if($this->select(array('where' => array('email' => $data['email']), 'limit' => 1)))
            $error = "E-posta adresi kullanılıyor!";

        if($error)
            return array('message' => $error);

        $data = $this->filterData($data);
        $id = $this->insert($data);

        if($id)
            $user = $this->selectRow($id);
        else
            return array('message' => 'Kayıt sırasında bir hata oluştu');

        return array('status' => 'success', 'user' => $user);
    }



    /**
     * Kullanıcı login işlemi
     *
     * @param array $data
     * @return array
     */
    public function signIn($data)
    {
        $error = "";

        if(!$data['email'])
            $error = "E-posta alanı boş olamaz!";
        else if(!$data['password'])
            $error = "Parola alanı boş olamaz!";

        if($error)
            return array('message' => $error);

        $user = $this->select(array('where' => array('email' => $data['email']), 'limit' => 1));

        if($user['password'] === $data['password']) {
            $tblToken = new TblToken();
            $token = $tblToken->getToken($user['id']);
            unset($user['password']);

            return array('status' => 'success','access_token' => $token, 'user' => $user);
        }
        else
            return array('message' => 'Kullanıcı bilgileri hatalı!');
    }



    /**
     * Kullanıcı bilgilerini günceller
     *
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function updateUser($id, $data)
    {
        if(!$id)
            return false;

        $newPassword = $data["new_password"];
        $data = $this->filterData($data);

        if ($this->update($id, $data)) {
            if ($newPassword) {
                $user = $this->selectRow($id);
                $user["new_password"] = $newPassword;
            }

            return true;
        }

        return false;
    }



    /**
     * Facebook Login
     *
     * @param array $data
     * @return array
     */
    public function facebookLogin($data)
    {
        $error = "";

        if (!$data["facebook_token"])
            $error = "Facebook token eksik!";

        if ($error)
            return array("message" => $error);

        include("../library/MatMuh/Facebook/facebook.php");

        $config = array(
            "appId" => "241440346040435",
            "secret" => "b4ec15b4839ee21068d77228a62664e4",
            "fileUpload" => false,
            "allowSignedRequest" => false
        );

        $fb = new Facebook($config);
        $fb->setAccessToken($data["facebook_token"]);
        $fbID = $fb->getUser();

        if ($fbID) {
            try {
                $fbUser = $fb->api("/me");
            }
            catch (FacebookApiException $e) {
                return array("message" => $e->getMessage());
            }
        }
        else
            return array("message" => "Facebook token hatalı!");

        if (!$fbUser)
            return array("message" => "Facebook ile email bilgisine erişilemiyor!");

        $exist = $this->select(array("where" => array("email" => $fbUser["email"]), "limit" => 1));

        if ($exist) {
            if (!$exist["facebook_id"])
                $this->update($exist["id"], array("facebook_id" => $fbUser["id"]));
        }
        else {
            $data["facebook_id"] = $fbUser["id"];
            $data["first_name"] = $fbUser["first_name"];
            $data["last_name"] = $fbUser["last_name"];
            $data["email"] = $fbUser["email"];
            $data["password"] = md5($fbUser["id"]);

            $exist = $this->register($data);
            $exist = $exist["user"];
        }

        $tblToken = new TblToken();
        $token = $tblToken->getToken($exist["id"]);

        return array("status" => "success", "access_token" => $token, "user" => $exist);
    }
}