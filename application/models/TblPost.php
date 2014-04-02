<?php
use MatMuh\Table;

class TblPost extends Table
{
    protected $_name = 'tbl_post';
    protected $_primary = 'id';
    protected $_sequence = 'id';
    protected $_cols = array('id', 'post_author', 'post_type', 'post_title', 'post_content', 'post_excerpt', 'post_url', 'post_thumbnail', 'post_banner', 'photo_gallery_id', 'video_gallery_id', 'tags', 'post_status');

    public function add($data)
    {
        $checkPost = $this->postControl($data);

        if (is_array($checkPost))
            return $checkPost;

        if (is_array($data["tags"]))
            $data["tags"] = json_encode($data["tags"]);

        $categories = $data["categories"];

        $data = $this->filterData($data);
        $id = $this->insert($data);

        if ($id) {
            if (is_array($categories)) {
                $tblPostCategory = new TblPostCategory();

                foreach ($categories as $categoryID) {
                    $tblPostCategory->insert(array("post_id" => $id, "category_id" => $categoryID));
                }
            }

            $post = $this->selectRow($id);
            return array('status' => 'success', 'post' => $post);
        }
        else
            return array('message' => 'Kayıt sırasında bir hata oluştu');
    }

    public function get($id)
    {
        $post = $this->selectRow($id);

        if ($post)
            return array('status' => 'success', 'post' => $post);
        else
            return array('message' => 'Hatalı ID!');
    }

    public function upgrade($data)
    {
        $checkPost = $this->postControl($data);

        if (is_array($checkPost))
            return $checkPost;

        if (is_array($data["tags"]))
            $data["tags"] = json_encode($data["tags"]);

        $categories = $data["categories"];

        $data = $this->filterData($data);

        if ($this->update($data["id"], $data)) {
            if (is_array($categories)) {
                $tblPostCategory = new TblPostCategory();

                $tblPostCategory->delete(array("post_id" => $data["id"]));

                foreach ($categories as $categoryID) {
                    $tblPostCategory->insert(array("post_id" => $data["id"], "category_id" => $categoryID));
                }
            }

            $post = $this->selectRow($data["id"]);
            return array('status' => 'success', 'post' => $post);
        }
        else
            return array('message' => 'Kayıt sırasında bir hata oluştu');
    }

    public function search($data)
    {
        $where = array();

        if ($data['post_type'])
            $where['post_type'] = $data['post_type'];

        if ($data['post_title'])
            $where['post_title%'] = $data['post_title'];

        if ($data['category'])
            $where[] = "id IN(SELECT post_id FROM tbl_post_category WHERE category_id=$data[category])";

        $param = array('where' => $where, 'order' => 'post_title');

        if ($data['limit'])
            $param['limit'] = $data['limit'];

        if ($data['offset'])
            $param['offset'] = $data['offset'];

        if ($data['total_count'])
            $param['total_count'] = $data['total_count'];

        $rows = $this->select($param);

        return array('status' => 'success', 'post' => $rows);
    }

    private function postControl($data)
    {
        $error = "";

        if (!$data['post_type'])
            $error = "Yazı türü boş olamaz";
        else if ($data['post_type'] != "page" && $data["post_type"] != "post")
            $error = "Hatalı yazı türü!";
        else if (!$data["post_title"])
            $error = "Yazı başlığı eksik!";

        else if ($title = $this->select(array('where' => array('post_title' => $data['post_title'], 'post_status' => 1), 'limit' => 1))) { 
            if ($data["id"]) {
                if ($title["id"] != $data["id"])
                    $error = "Bu başlık kullanılıyor!";
            }
            else {
                if ($title)
                    $error = "Bu başlık kullanılıyor!";
            }
        }

        if (!$data["post_url"])
            $data["post_url"] = \MatMuh\Helper::fixString($data["post_title"]);

        if ($url = $this->select(array('where' => array('post_url' => $data['post_url'], 'post_status' => 1), 'limit' => 1))) {
            if ($data["id"]) {
                if ($url["id"] != $data["id"])
                    $error = "Bu URL kullanılıyor!";
            }
            else {
                if ($url)
                    $error = "Bu URL kullanılıyor!";
            }
        }

        if ($error)
            return array('message' => $error);
        else
            return true;
    }
}