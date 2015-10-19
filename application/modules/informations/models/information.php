<?php
class Information extends ORM {

    var $table = 'informations';
	
	var $has_one = array('user','category');

    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
}
?>