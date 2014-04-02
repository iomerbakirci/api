<?php
use MatMuh\Table;

class TblGallery extends Table
{
	protected $_name = 'tbl_gallery';
	protected $_primary = 'id';
	protected $_sequence = 'gallery_type';
	protected $_cols = array('id', 'gallery_type', 'gallery_name', 'gallery_status');

	public function add($data)
	{
		$checkGallery = $this->galleryControl($data);

		if (is_array($checkGallery))
			return $checkGallery;

		$data = $this->filterData($data);
		$id = $this->insert($data);

		if($id) {
			$gallery = $this->selectRow($id);
			return array('status' => 'success', 'gallery' => $gallery);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	public function upgrade($data)
	{
		$checkGallery = $this->galleryControl($data);

		if (is_array($checkGallery))
			return $checkGallery;

		$data = $this->filterData($data);

		if ($this->update($data["id"], $data)) {
			$gallery = $this->selectRow($data['id']);
			return array('status' => 'success', 'gallery' => $gallery);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	public function get($id)
	{
		$gallery = $this->selectRow($id);

		if (!$gallery)
			return array('message' => 'Hatalı ID!');

		if ($gallery['gallery_type'] == 'photo')
			$sql = "SELECT g.*, p.id AS photo_id, p.photo_url FROM tbl_gallery g LEFT JOIN tbl_photo_gallery p ON p.gallery_id=g.id WHERE gallery_type='photo' AND gallery_id=$gallery[id]";
		else if ($gallery['gallery_type'] == 'video')
			$sql = "SELECT g.*, v.id AS video_id, v.video_url FROM tbl_gallery g LEFT JOIN tbl_video_gallery v ON v.gallery_id=g.id WHERE gallery_type='video' AND gallery_id=$gallery[id]";

		$rows = $this->customQuery($sql);

		return array('status' => 'success', 'gallery' => $rows);
    }

	private function galleryControl($data)
	{
		$error = "";

		if ($data["gallery_type"] != 'photo' && $data['gallery_type'] != 'video')
			$error = "Geçersiz galeri tipi!";

		if (!$data['gallery_name'])
			$error = 'Galeri adı eksik!';

		if($error)
			return array('message' => $error);
		else
			return true;
	}
}