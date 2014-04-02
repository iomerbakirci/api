<?php
use MatMuh\Table;

class TblVideoGallery extends Table
{
	protected $_name = 'tbl_video_gallery';
	protected $_primary = 'id';
	protected $_sequence = 'gallery_id';
	protected $_cols = array('id', 'gallery_id', 'video_url');

	public function add($data)
	{
		$checkVideo = $this->videoControl($data);

		if (is_array($checkVideo))
			return $checkVideo;

		$data = $this->filterData($data);
		$id = $this->insert($data);

		if($id) {
			$video = $this->selectRow($id);
			return array('status' => 'success', 'video' => $video);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	private function videoControl($data)
	{
		$error = "";
		$gallery = new TblGallery();

		if (!$data['gallery_id'])
			$error = 'Gallery ID eksik!';
		else if (!$gallery->exist(array("id" => $data["gallery_id"], 'gallery_type' => 'video', "gallery_status" => 1)))
			$error = "Hatalı galeri ID!";

		if($error)
			return array('message' => $error);
		else
			return true;
	}
}