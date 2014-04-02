<?php
use MatMuh\Table;

class TblCategory extends Table
{
	protected $_name = 'tbl_category';
	protected $_primary = 'id';
	protected $_sequence = 'category_slug';
	protected $_cols = array('id', 'category_slug', 'category_name', 'category_parent_id', 'category_status');

	public function add($data)
	{
		$checkCategory = $this->categoryControl($data);

		if (is_array($checkCategory))
			return $checkCategory;

		$data = $this->filterData($data);
		$id = $this->insert($data);

		if($id) {
			$category = $this->selectRow($id);
			return array('status' => 'success', 'category' => $category);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	public function get($id)
    {
        $category = $this->selectRow($id);

        if ($category)
            return array('status' => 'success', 'category' => $category);
        else
            return array('message' => 'Hatalı ID!');
    }

	public function upgrade($data)
	{
		$checkCategory = $this->categoryControl($data);

		if (is_array($checkCategory))
			return $checkCategory;

		$data = $this->filterData($data);

		if ($this->update($data["id"], $data)) {
			$category = $this->selectRow($data["id"]);
			return array('status' => 'success', 'category' => $category);
		}
		else
			return array('message' => 'Kayıt sırasında bir hata oluştu');
	}

	public function search($data)
	{
		$where = array();

		if ($data['category_slug'])
			$where['category_slug'] = $data['category_slug'];

		if ($data['category_name'])
			$where['category_name%'] = $data['category_name'];

		$param = array('where' => $where, 'order' => 'category_slug');

		if ($data['limit'])
			$param['limit'] = $data['limit'];

		if ($data['offset'])
			$param['offset'] = $data['offset'];

		if ($data['total_count'])
			$param['total_count'] = $data['total_count'];

		$rows = $this->select($param);

		return array('status' => 'success', 'category' => $rows);
	}

	private function categoryControl($data)
	{
		$error = "";

		if (!$data['category_name'])
			$error = "Kategori adı boş olamaz";
		else if ($data["category_parent_id"] && !$this->exist(array("id" => $data["category_parent_id"], "category_status" => 1)))
			$error = "Hatalı kategori ebeveyni!";

		if (!$data["category_slug"])
			$data["category_slug"] = \MatMuh\Helper::fixString($data["category_name"]);

		if ($slug = $this->select(array('where' => array("category_slug" => $data["category_slug"], "category_status" => 1), 'limit' => 1))) {
			if ($data["id"]) {
				if ($slug["id"] != $data["id"])
					$error = "Bu kategori mevcut!";
			}
			else {
				if ($slug)
					$error = "Bu kategori mevcut!";
			}
		}

		if($error)
			return array('message' => $error);
		else
			return true;
	}
}