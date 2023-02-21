<?php

	echo get_header();

	$user_id = get_current_user_id();


	  global $wpdb;

	  if($_REQUEST['delete_id'] != ""){
        $kk = $wpdb->delete( 'rss_feed_url', array( 'id' => $_REQUEST['delete_id'] ) );
        if($kk){
            do_action('wp_ajax_feed_generator_cron');
        }
      }

	  
	  if($_REQUEST['feed_url'] != ""){
        $query = "INSERT INTO rss_feed_url (feed_url,user_id,merge_name,merge_url) VALUES ('".$_REQUEST['feed_url']."','".$user_id."','".urldecode($_REQUEST['merge_name'])."','".$_REQUEST['merge_url']."')";
        

	$sql = $wpdb->query($query);

	$inserted_id = $wpdb->insert_id;
	// $inserted_ids[] = $wpdb->insert_id;
if($inserted_id ){
            do_action('wp_ajax_feed_generator_cron');
        }

	$newdata = file_get_contents(trim($_REQUEST['feed_url']));

	file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$inserted_id.'.xml', $newdata);
      }
	  
if($_REQUEST['merge_name'] != "")
	  	$feed_urls = $wpdb->get_results("SELECT * FROM rss_feed_url WHERE user_id = '".$user_id."' AND merge_name = '".urldecode($_REQUEST['merge_name'])."';");

	  ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />



<div class="container my_feeds">
    <?php 
if($kk) echo '<div class="sucs_msg">Successfully deleted with the feed</div>';
if($inserted_id) echo '<div class="sucs_msg">Successfully Added with the new feed</div>';
?>
<div class="add_btn"><a href="javascript:void(0);"><input type="button" name="Add_feed" value="Add Feed"></a></div>
<form style="display: none;" method="get" action="" name="add_feed" class="add_feed">
<div class="feed-section">
<label class="feed_label">Feed URL</label>
<input type="text" name="feed_url" class="feed_url">
<input type="hidden" name="merge_name" value="<?php echo urldecode($_REQUEST['merge_name']); ?>">
<input type="hidden" name="merge_url" value="<?php echo $feed_urls[0]->merge_url; ?>">
<input type="submit" name="add_new_feed" class="add_new_feed" value="Add New Feed">
</div>
</form>
<table id="table_id" class="display table_trade">
    <thead style="text-transform: uppercase;">
        <tr>
            <th>
            	id
            </th>
            <th>
            	Feed url
            </th>
            <th>
            	Feed Merge name
            </th>
            <th>
            	Action
            </th>
        </tr>
    </thead>
    <tbody>
    	<?php
    	$i = 1;
    	foreach ($feed_urls as $key) {
    		?>
    <tr>
    	<td>
    		<?php echo $i; ?>
    	</td>
    	<td>
    		<a href="<?php echo $key->feed_url; ?>"><?php echo $key->feed_url; ?></a>
    	</td>
    	<td>
    		<?php echo $key->merge_name; ?>
    	</td>
    	<td>
    		<a href="javascript:void(0);" data-id="<?php echo $key->id; ?>" class="btn btn-danger delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
    	</td>
    </tr>
    		<?php
    		$i++;
    	}
    	?>
    </tbody>
</table>
</div>

<script type="text/javascript">
	jQuery(document).ready( function () {
		jQuery('.add_btn').click(function() {
		jQuery('.add_feed').toggle();
	});
    jQuery('#table_id').DataTable();
} );

	jQuery('.delete').click(function() {
        //console.log('<?php echo get_site_url().'/my-feed-list?merge_name='.$key->merge_name.'&'; ?>delete_id='+$(this).attr('data-id'));
		 window.location.replace('<?php echo get_site_url().'/my-feed-list?merge_name='.urldecode($key->merge_name).'&'; ?>delete_id='+$(this).attr('data-id'));
	});

</script>
<style type="text/css">
	input.feed_url {
    width: 50% !important;
    font-size: 17px;
    display: inline-block;
    position: relative;
    margin: 0 20px;
}

label.feed_label {
    width: auto;
    font-size: 17px;
    display: inline-block;
    position: relative;
}

input.add_new_feed {
    font-size: 15px;
    padding: 5px 9px;
    border-radius: 4px;
    background-color: blue;
    color: #fff;
    font-weight: 600;
}

.feed-section {
    margin-bottom: 20px;
}
	.container.my_feeds {
    width: 80%;
    margin: 0 auto;
    margin-bottom: 30px;
    margin-top: 30px;
}

div#table_id_length label {
    font-size: 16px;
}

div#table_id_length label select {
    font-size: 13px;
}
.sucs_msg {
    font-size: 18px;
    padding: 15px;
    color: green;
}
.add_btn input {
    padding: 5px 15px;
    font-size: 16px;
    margin-bottom: 20px;
    border-radius: 4px;
    color: white;
    background-color: blue;
    font-weight: bold;
}
</style>

	  <?php

	  echo get_footer();
?>