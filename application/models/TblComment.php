<?php
use MatMuh\Table;

class TblComment extends Table
{
	protected $_name = 'tbl_comment';
	protected $_primary = 'id';
	protected $_sequence = 'post_id';
	protected $_cols = array('id', 'post_id', 'comment_author', 'comment_date', 'comment_content', 'comment_status');

	public function add($data)
	{
		$checkComment = $this->commentControl($data);

		if (is_array($checkComment))
			return $checkComment;

		$data = $this->filterData($data);
		$id = $this->insert($data);

		if($id) {
			$comment = $this->selectRow($id);
			return array('status' => 'success', 'comment' => $comment);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	public function get($id)
    {
        $comment = $this->selectRow($id);

        if ($comment)
            return array('status' => 'success', 'comment' => $comment);
        else
            return array('message' => 'Hatalı ID!');
    }

    public function upgrade($data)
	{
		$checkComment = $this->commentControl($data);

		if (is_array($checkComment))
			return $checkComment;

		$data = $this->filterData($data);

		if ($this->update($data["id"], $data)) {
			$comment = $this->selectRow($data['id']);
			return array('status' => 'success', 'comment' => $comment);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	public function search($data)
	{
		$where = array();

		if ($data['id'])
			$where['id'] = $data['id'];
		else if ($data['post_id'])
			$where['post_id'] = $data['post_id'];

		if ($data['comment_status'])
			$where['comment_status'] = $data['comment_status'];

		$param = array('where' => $where, 'order' => 'comment_date');

		if ($data['limit'])
			$param['limit'] = $data['limit'];

		if ($data['offset'])
			$param['offset'] = $data['offset'];

		if ($data['total_count'])
			$param['total_count'] = $data['total_count'];

		$rows = $this->select($param);

		return array('status' => 'success', 'comment' => $rows);
	}

	private function commentControl($data)
	{
		$error = "";
		$post = new TblPost();

		if (!$data['post_id'])
			$error = "Post ID boş olamaz";
		else if (!$post->exist(array("id" => $data["post_id"], "post_status" => 1)))
			$error = "Hatalı Post ID!";
		else if (!$data["comment_author"])
			$error = "Geçersiz kullanıcı!";
		else if (!$data['comment_content'])
			$error = "Boş yorum girildi!";

		if($error)
			return array('message' => $error);
		else
			return true;
	}
}