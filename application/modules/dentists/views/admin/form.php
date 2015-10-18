<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="icon-home home-icon"></i>
			<a href="#">หน้าแรก</a>

			<span class="divider">
				<i class="icon-angle-right arrow-icon"></i>
			</span>
		</li>
		<li class="active">ฟอร์ม</li>
	</ul><!--.breadcrumb-->

	<!-- <div class="nav-search" id="nav-search">
		<form class="form-search" />
			<span class="input-icon">
				<input type="text" placeholder="Search ..." class="input-small nav-search-input" id="nav-search-input" autocomplete="off" />
				<i class="icon-search nav-search-icon"></i>
			</span>
		</form>
	</div> -->
	<!--#nav-search-->
</div>

<div class="page-content">
	<div class="page-header position-relative">
		<h1>
			ทีมทันตแพทย์ของเรา
			<!-- <small>
				<i class="icon-double-angle-right"></i>
				<?=@$_GET['category']?>
			</small> -->
		</h1>
	</div><!--/.page-header-->

	<div class="row-fluid">
		<div class="span12">
			<!--PAGE CONTENT BEGINS-->
				<form class="form-horizontal" method="post" action="dentists/admin/dentists/save/<?php echo $rs->id ?>" enctype="multipart/form-data">
					
					<div class="control-group">
					    <label class="control-label" for="name">ภาษา</label>
					    <div class="controls lang">
					      <a href="th" class="active flag th">ไทย</a>
					      <a href="en" class="flag en">อังกฤษ</a>
					    </div>
					</div>
					
					<div class="control-group">
			            <label class="control-label" for="id-input-file-1">รูปภาพ</label>
			            <div class="controls">
			                <?php if($rs->image):?>
			                <img class="img" style="width:100px;" src="<?php echo (is_file('uploads/dentists/'.$rs->image))? 'uploads/dentists/'.$rs->image : 'media/images/webboard/noavatar.gif' ?>"  /> <br><br>
			                <?php endif;?>
			                <div class="input-xxlarge" style="width:544px;">
			                    <input type="file" id="id-input-file-1" name="image"/>
			                </div>
			            </div>
			        </div>
					
					<div class="control-group">
						<label class="control-label">ชื่อ</label>
						<div class="controls">
							<input  rel="th" id="name" class="span6" type="text" name="name[th]" value="<?php echo lang_decode($rs->name,'th')?>">
					        <input  rel="en" id="name" class="span6" type="text" name="name[en]" value="<?php echo lang_decode($rs->name,'en')?>">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">ความชำนาญ</label>
						<div class="controls">
							<input  rel="th" id="experience" class="span6" type="text" name="experience[th]" value="<?php echo lang_decode($rs->experience,'th')?>">
					        <input  rel="en" id="experience" class="span6" type="text" name="experience[en]" value="<?php echo lang_decode($rs->experience,'en')?>">
						</div>
					</div>
					
					<div class="control-group">
			            <label class="control-label" for="form-field-9">รายละเอียด</label>
			            <div class="controls">
			            	<div rel="th"><textarea name="detail[th]" class="full tinymce"><?php echo lang_decode($rs->detail,'th')?></textarea></div>
							<div rel="en"><textarea name="detail[en]" class="full tinymce"><?php echo lang_decode($rs->detail,'en')?></textarea></div>
			            </div>
			        </div>
			        
					<!-- <div class="control-group">
						<label class="control-label">ไฟล์แนบ</label>
						<div class="controls">
							<input class="input-xxlarge" type="text" name="files" value="<?php echo $rs->files?>"/>
							<input class="btn btn-mini btn-info" type="button" name="browse" value="เลือกไฟล์" onclick="browser($(this).prev(),'file')" />
						</div>
					</div> -->
					
					<div class="control-group">
						<label class="control-label">หมวดหมู่</label>
						<div class="controls">
							<?php echo form_dropdown('category_id',$rs->category->get_option(),$rs->category_id,'class="span4"');?>
						</div>
					</div>
					
					<div class="form-actions">
						<?php echo form_referer() ?>
						<button class="btn btn-large btn-info" type="submit">
							<i class="icon-ok bigger-110"></i>บันทึก
						</button>

						&nbsp; &nbsp; &nbsp;
						<button class="btn btn-large" type="reset">
							<i class="icon-undo bigger-110"></i>รีเซ็ต
						</button>
                                                &nbsp; &nbsp; &nbsp;
						<a class="btn btn-large" href="talks/admin/talks" >กลับ</a>
					</div>
				
				</form>
				
			<!--PAGE CONTENT ENDS-->
		</div><!--/.span-->
	</div><!--/.row-fluid-->
</div>

<!-- Load TinyMCE -->
<script type="text/javascript" src="media/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="media/tiny_mce/config.js"></script>
<script type="text/javascript">
tiny('detail[th],detail[en]');

$(function() {
	$("[rel=en]").hide();
	$(".lang a").click(function(){
		$("[rel=" + $(this).attr("href") + "]").show().siblings().hide();
		$(this).addClass('active').siblings().removeClass('active');
		return false;
	})
	
	$('#id-input-file-1 , #id-input-file-2').ace_file_input({
		no_file:'ไม่มีไฟล์แนบ...',
		btn_choose:'แนบไฟล์',
		btn_change:'เปลี่ยน',
		droppable:false,
		onchange:null,
		thumbnail:false //| true | large
		//whitelist:'gif|png|jpg|jpeg'
		//blacklist:'exe|php'
		//onchange:''
		//
	});
});
</script>