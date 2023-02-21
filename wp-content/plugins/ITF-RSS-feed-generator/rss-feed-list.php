<?php
    echo get_header();

    $user_id = get_current_user_id();


      global $wpdb;

     if($_REQUEST['merge_name'] != ""){
           
        $feed_delete = $wpdb->delete('rss_feed_url', array( 'merge_name' => urldecode($_REQUEST['merge_name'])));
    
         if($feed_delete)
         {
            do_action('wp_ajax_feed_generator_cron');

         } 
      }

        $feed_urls = $wpdb->get_results("SELECT * FROM rss_feed_url WHERE user_id = '".$user_id."' group by merge_name;");

        // echo "<pre>";print_r($feed_urls);exit;

        
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container my_feeds">
	    <?php 
	if($feed_delete) echo '<div class="sucs_msg">Successfully deleted with the feed</div>';
?>
 <div class="add_btn"><h3>Number of times accessed with a Merger : <?php echo count($feed_urls); ?> </h3></div>
<div class="add_btn"><a href="<?php echo get_site_url(); ?>/rss-generation"><input type="button" name="Add_feed" value="Generate New Merge"></a></div>
<table id="table_id" class="display table_trade">
    <thead style="text-transform: uppercase;">
        <tr>
            <th>
                id
            </th>
            <th>
                Feed Merged url
            </th>
            <th>
                Feed Merge name
            </th>
            <th>
                Action
            </th>
              <th>
                Delete
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
            <?php $replaced = str_replace(' ', '-', $key->merge_name); ?>
            <a href="<?php echo get_site_url().'/feed/'.$replaced; ?>"><?php echo get_site_url().'/feed/'.$replaced; ?></a>
        </td>
        <td>
            <?php echo $key->merge_name; ?>
        </td>
        <td>
            <a href="<?php echo get_site_url().'/my-merge-list/?merge_name='.urlencode($key->merge_name); ?>">Add / Remove Feeds</a>
        </td>
         <td>
           <a href="javascript:void(0);" data-id="<?php echo urlencode($key->merge_name); ?>" class="btn btn-danger delete-feed" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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

jQuery('.delete-feed').click(function() {
         console.log('<?php echo get_site_url();?>/my-merges/?merge_name='+$(this).attr('data-id'));
         window.location.replace('<?php echo get_site_url();?>/my-merges/?merge_name='+$(this).attr('data-id'));
    });

    jQuery('#table_id').DataTable();
} );


</script>
<style type="text/css">
	.sucs_msg {
    font-size: 18px;
    padding: 15px;
    color: green;
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