<?php
class Content extends ORM {

    var $table = 'contents';
	
	var $has_one = array('user','download');

    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
}
?>