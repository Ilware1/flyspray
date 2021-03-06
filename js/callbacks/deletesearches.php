<?php
/*
    This script is the AJAX callback that deletes a user's saved search
*/

define('IN_FS', true);

require_once('../../header.php');
$baseurl = dirname(dirname($baseurl)) .'/' ;

if (Cookie::has('flyspray_userid') && Cookie::has('flyspray_passhash')) {
    $user = new User(Cookie::val('flyspray_userid'));
    $user->check_account_ok();
    
    if( !Post::has('csrftoken') ){
        header(':', true, 428); # 'Precondition Required'
        die('missingtoken');
    }elseif( Post::val('csrftoken')==$_SESSION['csrftoken']){
        # empty
    }else{
        header(':', true, 412); # 'Precondition Failed'
        die('wrongtoken');
    }

    if (!$user->isAnon()) {
        $db->Query('DELETE FROM {searches} WHERE id = ? AND user_id = ?', array(Post::num('id'), $user->id));
        echo $db->AffectedRows();
    }
}

?>
