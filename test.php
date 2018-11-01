<?php 
	include_once '../../../wp-load.php';
	 if(!empty($_POST)){
		$activation_key = $_POST['activation_key'];
		// $activation_key = 'd007209d0af37aa9d0063d285fc581de67172844';
	 	$table_name  = $wpdb->prefix."activation_key";
		$site_url		= $_POST['site_url'];
		$key_result = $wpdb->get_results("SELECT * FROM $table_name WHERE activation_key ='$activation_key';",ARRAY_A);
		if(!empty($key_result[0])){
			$column_values = array('activation_key_status' =>'1','site_url'=>$site_url,'plugin_communication_key'=>$_POST['plugin_communication_key']);
			$where = array('activation_key'=>$_POST['activation_key']);
			$update_activation = $wpdb->update($table_name,$column_values,$where);
			if($update_activation){
				echo json_encode(array('status'=>'1','message'=>'Key Verified Successfully'));
			}else{
				echo json_encode(array('status'=>'2','message'=>'Correct Key But Not Updated'));
			}
		}else{
			echo json_encode(array('status'=>'0','message'=>'Enter Wrong Key Contact Admin'));
		}
	}else{
		echo 'All Code Delete . Are You Happy !';
	}
 ?>
