
<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Tag {
	public $id;
	public $title;
	public $state;
	public $alias;

	public function show_admin_form() {
		$this->form = new Form();
		$this->form->load_json(CMSPATH . "/tags/");
	}

	public function load($id) {
		$info = CMS::Instance()->pdo->query('select * from tags where id=' . $id)->fetch();
		$this->id = $info->id;
		$this->title = $info->title;
		$this->state = $info->state;
		$this->note = $info->note;
		$this->alias = $info->alias;
		$this->filter = $info->filter;
		$this->description = $info->description;
		$this->image = $info->image;
		$this->public = $info->public;
	}

	public static function get_all_tags() {
		$query = "select * from tags";
		return CMS::Instance()->pdo->query($query)->fetchAll();
	}

	public static function get_tag_content_types($id) {
		$query = "select content_type_id from tag_content_type where tag_id=?";
		$stmt = CMS::Instance()->pdo->prepare($query);
		$stmt->execute(array($id));
		return $stmt->fetchAll();
	}

	public function save($required_details_form) {
		// update this object with submitted and validated form info
		$this->title = $required_details_form->get_field_by_name('title')->default;
		$this->state = $required_details_form->get_field_by_name('state')->default;
		$this->note = $required_details_form->get_field_by_name('note')->default;
		$this->alias = $required_details_form->get_field_by_name('alias')->default;
		$this->filter = $required_details_form->get_field_by_name('filter')->default;
		$this->image = $required_details_form->get_field_by_name('image')->default;
		$this->description = $required_details_form->get_field_by_name('description')->default;
		$this->public = $required_details_form->get_field_by_name('public')->default;

		if ($this->id) {
			// update
			$query = "update tags set state=?, public=?, title=?, alias=?, image=?, note=?, description=?, filter=? where id=?";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$params = array($this->state, $this->public, $this->title, $this->alias, $this->image, $this->note, $this->description, $this->filter, $this->id) ;
			$result = $stmt->execute( $params );
			
			if ($result) {
				CMS::Instance()->queue_message('Tag updated','success',Config::$uripath . '/admin/tags/show');	
			}
			else {
				CMS::Instance()->queue_message('Tag failed to save','danger', $_SERVER['REQUEST_URI']);	
			}
		}
		else {
			// new
			$query = "insert into tags (state,public,title,alias,note,filter,description,image) values(?,?,?,?,?,?,?)";
			$stmt = CMS::Instance()->pdo->prepare($query);
			$params = array($this->state, $this->public, $this->title, $this->alias, $this->note, $this->filter, $this->description, $this->image);
			$result = $stmt->execute( $params );
			if ($result) {
				CMS::Instance()->queue_message('New tag saved','success',Config::$uripath . '/admin/tags/show');	
			}
			else {
				CMS::Instance()->queue_message('New tag failed to save','danger',Config::$uripath . $_SERVER['REQUEST_URI']);	
			}
		}
	}
}