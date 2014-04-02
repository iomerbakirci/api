<?php
use MatMuh\Table;

class TblPhotoGallery extends Table
{
	protected $_name = 'tbl_photo_gallery';
	protected $_primary = 'id';
	protected $_sequence = 'gallery_id';
	protected $_cols = array('id', 'gallery_id', 'photo_url');

	public function add($data)
	{
		$checkPhoto = $this->photoControl($data);

		if (is_array($checkPhoto))
			return $checkPhoto;

		$data = $this->filterData($data);
		$id = $this->insert($data);

		if($id) {
			$photo = $this->selectRow($id);
			return array('status' => 'success', 'photo' => $photo);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	private function photoControl($data)
	{
		$error = "";
		$gallery = new TblGallery();

		if (!$data['gallery_id'])
			$error = 'Gallery ID eksik!';
		else if (!$gallery->exist(array("id" => $data["gallery_id"], 'gallery_type' => 'photo', "gallery_status" => 1)))
			$error = "Hatalı galeri ID!";

		if($error)
			return array('message' => $error);
		else
			return true;
	}
}