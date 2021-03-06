<?php

class Galleries extends Admin_Controller{
	
	function __construct(){
		parent::__construct();	
	}
	
	function index($id=FALSE)
	{
		$data['categories'] = new Category($id);
		$galleries = new Gallery();
		if(@$_POST['category_id'])$id=$_POST['category_id'];
		$data['galleries'] = $galleries->where('category_id',$id)->order_by('orderlist','asc')->get();
		$this->template->build('admin/gallery_index',$data);
	}
	
	function form($cat_id,$id=FALSE)
	{
		$data['categories'] = new Category($cat_id);
		$data['galleries'] = new Gallery($id);
		$this->template->build('admin/gallery_form',$data);
	}
	
	function save($id=false)
	{	
		if($_POST)
		{
			fix_file($_FILES['filesToUpload']);
			foreach($_FILES['filesToUpload'] as $key => $item)
			{
				if($item)
				{
					$gallery = new Gallery();
					if($_FILES['filesToUpload'][$key]['name'])
					{
						
						$gallery->image = $gallery->upload($_FILES['filesToUpload'][$key],'uploads/gallery');
						$gallery->category_id = $id;
						$gallery->user_id = $this->session->userdata('id');
						$gallery->save();
					}		
				}
			}

			set_notify('success', lang('save_data_complete'));
		}
		redirect('galleries/admin/galleries/index/'.$_POST['category_id']);
	}
	
	function delete($cat_id,$id=FALSE)
	{
		if($id)
		{
			$gallery = new Gallery($id);
			$gallery->delete_file($gallery->id,'uploads/galleries/','image');
			$gallery->delete();
			set_notify('success', lang('delete_data_complete'));
		}
		redirect('galleries/admin/galleries/index/'.$cat_id);
	}
	
	function save_orderlist($id=FALSE){
		if($_POST)
		{
			foreach($_POST['orderlist'] as $key => $item)
			{
				if($item)
				{
					$gallery = new Gallery(@$_POST['orderid'][$key]);
					$gallery->from_array(array('orderlist' => $item));
					$gallery->save();
				}
			}
			set_notify('success', lang('save_data_complete'));
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function delete_image(){
		$gallery = new Gallery($_POST['id']);
		$gallery->delete_file($gallery->id,'uploads/gallery/','image');
		$gallery->delete();
	}
}

?>