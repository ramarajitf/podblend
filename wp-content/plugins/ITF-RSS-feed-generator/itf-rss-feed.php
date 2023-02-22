<?php
/*
Plugin Name: RSS Feed generator by ITF
Description: This plugin is used to combine RSS feeds to a single RSS feed
Author: ITFlex
Version: 1.0.0
Author URI: https://www.itflexsolutions.com
*/

/*if(is_admin())
    $rss_base_url =   get_site_url().'/wp-admin/admin.php?';
else
    $rss_base_url = get_site_url();
*/

add_action('admin_menu','itf_rss_feed');

    function itf_rss_feed() {

        add_menu_page(__('RSS Feed Generator'), //page title
        __('RSS Feed generator'), //menu title
        'manage_options', //capabilities
        'rss_feed_generator', //menu slug
        'rss_feed_generator' //function
        );

        // this is a submenu
    add_submenu_page('rss_feed_generator', //parent slug
    __('RSS Feeds'), //page title
    __('RSS Feeds'), //menu title
    'manage_options', //capability
    'rss_feed_list', //menu slug
    'rss_feed_list'); //function

    #satheesh updated on 31-01-2023
    add_submenu_page('rss_feed_generator', //parent slug
    __('Settings'), //page title
    __('Settings'), //menu title
    'manage_options', //capability
    'feed_settings', //menu slug
    'feed_settings'); //function 

    add_submenu_page('rss_feed_generator', //parent slug
    __(''), //page title
    __(''), //menu title
    'manage_options', //capability
    'update_feed', //menu slug
    'update_feed'); //function   
    #satheesh updated end on 31-01-2023

     add_submenu_page('rss_feed_generator', //parent slug
    __(''), //page title
    __(''), //menu title
    'manage_options', //capability
    'feed_url_list', //menu slug
    'feed_url_list'); //function 

    add_submenu_page('rss_feed_generator', //parent slug
    __(''), //page title
    __(''), //menu title
    'manage_options', //capability
    'update_channel', //menu slug
    'update_channel'); //function  


    }

    add_action( 'wp_ajax_feed_generator_cron', 'feed_generator_cron' );

add_action( 'wp_ajax_nopriv_feed_generator_cron', 'feed_generator_cron' );



function feed_generator_cron(){


    //  mail('kasi@itflexsolutions.com','test','test message');
      // error_reporting(0);
      global $wpdb;

        $get_merge_url = $wpdb->get_results("SELECT * FROM feed_url_cron;");        

    for ($i=0; $i < count($get_merge_url); $i++) { 
        feed_generator($get_merge_url[$i]->merge_url);
    }

}

function date_sor1($d, $m) {
    return strtotime($d['pubDate']) - strtotime($m['pubDate']);
}
 function date_sort1($a, $b) {
    return strtotime($a->pubDate) - strtotime($b->pubDate);
}

    function feed_generator($merge_url=''){

     // mail('kasi@itflexsolutions.com','test','test message');
      // error_reporting(0);
      global $wpdb;

        $get_merge_groups = $wpdb->get_results("SELECT * FROM rss_feed_url where merge_url = '".$merge_url."' group by merge_name;");   

for ($q=0; $q < count($get_merge_groups); $q++) { 	
	echo $q.'k';

	$get_urls = $wpdb->get_results("SELECT * FROM rss_feed_url where user_id=".$get_merge_groups[$q]->user_id." AND merge_name='".$get_merge_groups[$q]->merge_name."';");
     $invalidurl = false;$html_item = '';
	       
            $user_id = $get_merge_groups[$q]->user_id;
            $merge_name = $get_merge_groups[$q]->merge_name;
            // $url = $get_merge_groups[$q]->feed_url;
            $chanel_title = $get_merge_groups[$q]->chanel_title;
            $chanel_link = $get_merge_groups[$q]->chanel_link;
            $chanel_language =$get_merge_groups[$q]->chanel_language;
            $chanel_copyright = $get_merge_groups[$q]->chanel_copyright;
            $chanel_description =$get_merge_groups[$q]->chanel_description;
            $chanel_image =$get_merge_groups[$q]->chanel_image;
            $chanel_explicit = $get_merge_groups[$q]->chanel_explicit;
            $chanel_type = $get_merge_groups[$q]->chanel_type;
            $chanel_subtitle = $get_merge_groups[$q]->chanel_subtitle;
            $chanel_author = $get_merge_groups[$q]->chanel_author;
            $chanel_summary = $get_merge_groups[$q]->chanel_summary;
            // $chanel_owner = $_POST['chanel_owner'];
            $chanel_name = $get_merge_groups[$q]->chanel_name;
            $chanel_email = $get_merge_groups[$q]->chanel_email;
            $chanel_i_image =$get_merge_groups[$q]->chanel_i_image;
            $chanel_categories = $get_merge_groups[$q]->chanel_categories;
            // $imp_url = explode(PHP_EOL,$url);


            $imp_url = array();
            foreach ($get_urls as $get_url) {
            	$imp_url[] = $get_url->feed_url; 
            }

// if(count($imp_url) > 50){
//         echo "<div class='error_msg'>maximum number of feeds is 50</div>";
//     }else{

 $invalidurl = false;$html_item = '';$newitem=[];$kn = 0;
 for ($i=0; $i <= count($imp_url); $i++) { 

    
    // if(strpos($imp_url[$i], "independentpodcast.network")!==false){
    //     continue;
    // }
        
        
        if(@simplexml_load_file(trim($imp_url[$i]))){
      $feeds = simplexml_load_file(trim($imp_url[$i]));   

      global $wpdb;

       /* $details = $wpdb->get_row("SELECT * FROM rss_feed_url WHERE feed_url = '".$imp_url[$i]."' AND user_id = '".$user_id."' AND merge_name = '".$merge_name."';");
echo "<pre>";

          if($details->id)
        {
        
        $existing_data = file_get_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$details->id.'.xml');

        $str_len = strlen($data);

        $current_data = file_get_contents(trim($imp_url[$i]));

        $str_len2 = strlen($current_data);      

        if($str_len != $str_len2){

            $updatedata = file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$details->id.'.xml', $current_data);
            
        }else if($str_len == $str_len2){
            continue;
        }

        }else{

     $query = "INSERT INTO rss_feed_url (feed_url,user_id,chanel_title,chanel_link,chanel_language,chanel_copyright,chanel_description,chanel_image,chanel_explicit,chanel_type,chanel_subtitle,chanel_author,chanel_summary,chanel_owner,chanel_name,chanel_email,chanel_i_image,chanel_categories,merge_name) VALUES ('".$imp_url[$i]."','".$user_id."','".$chanel_title."','".$chanel_link."','".$chanel_language."','".$chanel_copyright."','".$chanel_description."','".$chanel_image."','".$chanel_explicit."','".$chanel_type."','".$chanel_subtitle."','".$chanel_author."','".$chanel_summary."',' ','".$chanel_name."','".$chanel_email."','".$chanel_i_image."','".$chanel_categories."','".$merge_name."')";

    $sql = $wpdb->query($query);

    $inserted_id = $wpdb->insert_id;
    $inserted_ids[] = $wpdb->insert_id;

    $newdata = file_get_contents(trim($imp_url[$i]));

    file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$inserted_id.'.xml', $newdata);

}*/
if($it == '') $it = 0;

// KD edit start

$dom=new DOMDocument;
$dom->load( trim($imp_url[$i]) );

$xp=new DOMXPath( $dom );

$query='//item';
$col=$xp->query( $query);


if( $col->length > 0 ){

    foreach( $col as $node )
    { 
        
      foreach ($node->childNodes as $key => $value) {
        if($value->localName != '')
        {
         if($value->localName == 'title')
         {
            $newitem[$kn]['title'] = $value->nodeValue;
         }
         if($value->localName == 'link')
         {
            $newitem[$kn]['link'] = $value->nodeValue;
         }
         if($value->localName == 'description')
         {
            $newitem[$kn]['description'] = $value->nodeValue;
         }
         if($value->localName == 'pubDate')
         {
            $newitem[$kn]['pubDate'] = $value->nodeValue;
         }
         if($value->localName == 'episodeType')
         {
            $newitem[$kn]['episodeType'] = $value->nodeValue;
         }
         if($value->localName == 'episode')
         {
            $newitem[$kn]['episode'] = $value->nodeValue;
         }
         if($value->localName == 'author')
         {
            $newitem[$kn]['author'] = $value->nodeValue;
         }
         if($value->localName == 'subtitle')
         {
            $newitem[$kn]['subtitle'] = $value->nodeValue;
         }
         if($value->localName == 'summary')
         {
            $newitem[$kn]['summary'] = $value->nodeValue;
         }
         if($value->localName == 'duration')
         {
            $newitem[$kn]['duration'] = $value->nodeValue;
         }
         if($value->localName == 'explicit')
         {
            $newitem[$kn]['explicit'] = $value->nodeValue;
         }
         if($value->localName == 'explicit')
         {
            $newitem[$kn]['explicit'] = $value->nodeValue;
         }
        }

      }
    $kn++;
    }
}
// exit;
// KD edit end


      foreach ($feeds->channel->item as $items) {
           $postDate = $items->pubDate;
   $pubDate = date('D, d M Y',strtotime($postDate));
        $item_data[] = $items;
        // echo $items;
    $it++;
   }
     }
 }



// foreach ($newitem as $newit) {
//            $postDate = $newit['pubDate'];
//    $pubDate = date('D, d M Y',strtotime($postDate));
//         $item_da[] = $newit;
//    }


usort($newitem, "date_sor1");

$rev_item_da = array_reverse($newitem);


usort($item_data, "date_sort1");

$rev_item_data = array_reverse($item_data);

if ($channel_data == "") {
    $channel_data .= '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">';
      $channel_data .= '<channel>
<atom:link href="'.$chanel_link.'" rel="self" type="application/rss+xml"/>
<title>'.$chanel_title.'</title>
<link>'.$chanel_link.'</link>
<language>'.$chanel_language.'</language>
<copyright>'.$chanel_copyright.'</copyright>
<description>'.stripslashes($chanel_description).'</description>
<image>
<url>'.$chanel_image.'</url>
<title>'.$chanel_title.'</title>
<link>'.$chanel_link.'</link>
</image>
<itunes:explicit>'.$chanel_explicit.'</itunes:explicit>
<itunes:type>'.$chanel_type.'</itunes:type>
<itunes:subtitle>'.$chanel_subtitle.'</itunes:subtitle>
<itunes:author>'.$chanel_author.'</itunes:author>
<itunes:summary>'.stripslashes($chanel_summary).'</itunes:summary>
<itunes:owner>
<itunes:name>'.$chanel_name.'</itunes:name>
<itunes:email>'.$chanel_email .'</itunes:email>
</itunes:owner>
<itunes:image href="'.$chanel_i_image.'"/>
<itunes:category text="'.$chanel_categories .'"> </itunes:category>';

$html_item .= $channel_data; 
}

$on = 0;
foreach ($rev_item_data as $rev_item) {
   $episode = count($rev_item);
   $title = $rev_item->title;
   $link = $rev_item->link;
   $url=get_encloser($rev_item->enclosure);  
   $description = $rev_item->description;
   $postDate = $rev_item->pubDate;
   $pubDate = date('D, d M Y H:i:s',strtotime($postDate));
   $guid = $rev_item->guid;
$html_item .= '<item>
<title>'.$title.'</title>
<link>'.$link.'</link>
<description>'.$description.'</description>
<pubDate>'.$pubDate.' GMT</pubDate>
<itunes:title xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$title.'</itunes:title>
<itunes:episodeType xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['episodeType'].'</itunes:episodeType>
<itunes:episode xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['episode'].'</itunes:episode>
<itunes:author xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['author'].'</itunes:author>
<itunes:subtitle xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['subtitle'].'</itunes:subtitle>
<itunes:summary xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['summary'].'</itunes:summary>
<itunes:duration xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['duration'].'</itunes:duration>
<itunes:explicit xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['explicit'].'</itunes:explicit>
<content:encoded><p>'.$description.'</p></content:encoded>
<enclosure url="'.$rev_item->enclosure->attributes['url'].'" type="'.$rev_item->enclosure->attributes['type'].'"/>
<guid isPermaLink="false">'.$guid.'</guid>
<enclosure url="'.$url.'" length="0" type="audio/mpeg"/>
</item>';
$on++;
}


 $html_item .= '</channel>
</rss>';    

$html_item = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $html_item);

    $replaced = str_replace(' ', '-', $merge_name);

if (!file_exists(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml')) {
    mkdir(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/', 0777, true);
}
 file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml', $html_item);
 $jemi = get_site_url().'/wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml';

   // }
   //}
 echo "hi".$it.'-'.$q.'-'.$jemi;
	
 }
	// exit(); 
	  return true;

   }


    function rss_feed_generator(){
        
        if (isset($_POST['generator_submit']) && $_POST['urls'] != "") {
           $id = get_current_user_id();
           global $wpdb;
           $get_stats = "SELECT * FROM wp_pmpro_memberships_users WHERE user_id = '".$id."' AND status = 'active'";
           $sql2 = $wpdb->get_results($get_stats); 
          if (count($sql2) < 1) {
                echo "<div class='error_msg'>Please select any plan first</div>";
            }
            else{

                $user_id = get_current_user_id();
                $merge_name = $_POST['merge_name'];
                $url = $_POST['urls'];
                $chanel_title = $_POST['chanel_title'];
                $chanel_link = $_POST['chanel_link'];
                $chanel_language = $_POST['chanel_language'];
                $chanel_copyright = $_POST['chanel_copyright'];
                $chanel_description = $_POST['chanel_description'];
                $chanel_image = $_POST['chanel_image'];
                $chanel_explicit = $_POST['chanel_explicit'];
                $chanel_type = $_POST['chanel_type'];
                $chanel_subtitle = $_POST['chanel_subtitle'];
                $chanel_author = $_POST['chanel_author'];
                $chanel_summary = $_POST['chanel_summary'];
                // $chanel_owner = $_POST['chanel_owner'];
                $chanel_name = $_POST['chanel_name'];
                $chanel_email = $_POST['chanel_email'];
                $chanel_i_image = $_POST['chanel_i_image'];
                $chanel_categories = $_POST['chanel_categories'];
                $imp_url = explode(PHP_EOL,$url);
                  // echo "<pre>"; print_r(count($imp_url)) ; die;
         if(count($imp_url) > 50){
                echo "<div class='error_msg'>maximum number of feeds is 50</div>";
            }else{
            global $wpdb;
            $membership =  "SELECT * FROM rss_subscribe_settings WHERE subs_id = '".$sql2[0]->membership_id."'";
            $plan = $wpdb->get_results($membership);

            $merge_limit =  "SELECT * FROM feed_url_cron WHERE user_id = '".$user_id."'";
            $merge = $wpdb->get_results($merge_limit);
     
            /*$url_limit =  "SELECT * FROM rss_feed_url WHERE user_id = '".$user_id."'";
            $source_url = $wpdb->get_results($url_limit);*/

    if (count($merge) >= $plan[0]->feed_limit || count($imp_url) > $plan[0]->source_limit) {
            echo "<div class='error_msg'>Please Check your source or merge limit</div>";
               }
    else{
         
         $invalidurl = false;$html_item = '';$newitem=[];$kn = 0;
         for ($i=0; $i <= count($imp_url); $i++) { 

    
    if(strpos($imp_url[$i], "independentpodcast.network")!==false){
        continue;
    }
        
        
        if(@simplexml_load_file(trim($imp_url[$i]))){
      $feeds = simplexml_load_file(trim($imp_url[$i]));

		
        

      global $wpdb;

        $details = $wpdb->get_row("SELECT * FROM rss_feed_url WHERE feed_url = '".$imp_url[$i]."' AND user_id = '".$user_id."' AND merge_name = '".$merge_name."';");
	
        if($details->id)
        {
        
        $existing_data = file_get_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$details->id.'.xml');

        $str_len = strlen($data);

        $current_data = file_get_contents(trim($imp_url[$i]));

        $str_len2 = strlen($current_data);      

        if($str_len != $str_len2){

            $updatedata = file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$details->id.'.xml', $current_data);
            
        }else if($str_len == $str_len2){
            continue;
        }

        }else{

        $query = "INSERT INTO rss_feed_url (feed_url,user_id,chanel_title,chanel_link,chanel_language,chanel_copyright,chanel_description,chanel_image,chanel_explicit,chanel_type,chanel_subtitle,chanel_author,chanel_summary,chanel_owner,chanel_name,chanel_email,chanel_i_image,chanel_categories,merge_name) VALUES ('".$imp_url[$i]."','".$user_id."','".$chanel_title."','".$chanel_link."','".$chanel_language."','".$chanel_copyright."','".$chanel_description."','".$chanel_image."','".$chanel_explicit."','".$chanel_type."','".$chanel_subtitle."','".$chanel_author."','".$chanel_summary."',' ','".$chanel_name."','".$chanel_email."','".$chanel_i_image."','".$chanel_categories."','".$merge_name."')";

    $sql = $wpdb->query($query);

    $inserted_id = $wpdb->insert_id;
    $inserted_ids[] = $wpdb->insert_id;
    $newdata = file_get_contents(trim($imp_url[$i]));
    file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$inserted_id.'.xml', $newdata);

}
if($it == '') $it = 0;

// KD edit start

$dom=new DOMDocument;
$dom->load( trim($imp_url[$i]) );

$xp=new DOMXPath( $dom );

$query='//item';
$col=$xp->query( $query);


if( $col->length > 0 ){

    foreach( $col as $node )
    { 
        
      foreach ($node->childNodes as $key => $value) {
        if($value->localName != '')
        {
         if($value->localName == 'title')
         {
            $newitem[$kn]['title'] = $value->nodeValue;
         }
         if($value->localName == 'link')
         {
            $newitem[$kn]['link'] = $value->nodeValue;
         }
         if($value->localName == 'description')
         {
            $newitem[$kn]['description'] = $value->nodeValue;
         }
         if($value->localName == 'pubDate')
         {
            $newitem[$kn]['pubDate'] = $value->nodeValue;
         }
         if($value->localName == 'episodeType')
         {
            $newitem[$kn]['episodeType'] = $value->nodeValue;
         }
         if($value->localName == 'episode')
         {
            $newitem[$kn]['episode'] = $value->nodeValue;
         }
         if($value->localName == 'author')
         {
            $newitem[$kn]['author'] = $value->nodeValue;
         }
         if($value->localName == 'subtitle')
         {
            $newitem[$kn]['subtitle'] = $value->nodeValue;
         }
         if($value->localName == 'summary')
         {
            $newitem[$kn]['summary'] = $value->nodeValue;
         }
         if($value->localName == 'duration')
         {
            $newitem[$kn]['duration'] = $value->nodeValue;
         }
         if($value->localName == 'explicit')
         {
            $newitem[$kn]['explicit'] = $value->nodeValue;
         }
         if($value->localName == 'explicit')
         {
            $newitem[$kn]['explicit'] = $value->nodeValue;
         }
        }

      }
    $kn++;
    }
}
// exit;
// KD edit end

      foreach ($feeds->channel->item as $items) {
           $postDate = $items->pubDate;
   $pubDate = date('D, d M Y',strtotime($postDate));
        $item_data[] = $items;
    $it++;
   }
     }
 }

foreach ($newitem as $newit) {
           $postDate = $newit['pubDate'];
   $pubDate = date('D, d M Y',strtotime($postDate));
        $item_da[] = $newit;
   }


function date_sorr($d, $q) {
    return strtotime($d['pubDate']) - strtotime($q['pubDate']);
}
usort($newitem, "date_sorr");

$rev_item_da = array_reverse($newitem);
 function date_sort($a, $b) {
    return strtotime($a->pubDate) - strtotime($b->pubDate);
}
usort($item_data, "date_sort");

$rev_item_data = array_reverse($item_data);

if ($channel_data == "") {
    $channel_data .= '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">';
      $channel_data .= '<channel>
<atom:link href="'.$chanel_link.'" rel="self" type="application/rss+xml"/>
<title>'.$chanel_title.'</title>
<link>'.$chanel_link.'</link>
<language>'.$chanel_language.'</language>
<copyright>'.$chanel_copyright.'</copyright>
<description>'.stripslashes($chanel_description).'</description>
<image>
<url>'.$chanel_image.'</url>
<title>'.$chanel_title.'</title>
<link>'.$chanel_link.'</link>
</image>
<itunes:explicit>'.$chanel_explicit.'</itunes:explicit>
<itunes:type>'.$chanel_type.'</itunes:type>
<itunes:subtitle>'.$chanel_subtitle.'</itunes:subtitle>
<itunes:author>'.$chanel_author.'</itunes:author>
<itunes:summary>'.stripslashes($chanel_summary).'</itunes:summary>
<itunes:owner>
<itunes:name>'.$chanel_name.'</itunes:name>
<itunes:email>'.$chanel_email .'</itunes:email>
</itunes:owner>
<itunes:image href="'.$chanel_i_image.'"/>
<itunes:category text="'.$chanel_categories .'"> </itunes:category>';

$html_item .= $channel_data; 
}

$on = 0;
foreach ($rev_item_data as $rev_item) {
   $episode = count($rev_item);
   $title = $rev_item->title;
   $link = $rev_item->link;
   $url=get_encloser($rev_item->enclosure);  
   $description = $rev_item->description;
   $postDate = $rev_item->pubDate;
   $pubDate = date('D, d M Y H:i:s',strtotime($postDate));
   $guid = $rev_item->guid;
$html_item .= '<item>
<title>'.$title.'</title>
<link>'.$link.'</link>
<description>'.$description.'</description>
<pubDate>'.$pubDate.' GMT</pubDate>
<itunes:title xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$title.'</itunes:title>
<itunes:episodeType xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['episodeType'].'</itunes:episodeType>
<itunes:episode xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['episode'].'</itunes:episode>
<itunes:author xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['author'].'</itunes:author>
<itunes:subtitle xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['subtitle'].'</itunes:subtitle>
<itunes:summary xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['summary'].'</itunes:summary>
<itunes:duration xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['duration'].'</itunes:duration>
<itunes:explicit xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$rev_item_da[$on]['explicit'].'</itunes:explicit>
<content:encoded><p>'.$description.'</p></content:encoded>
<enclosure url="'.$rev_item->enclosure->attributes['url'].'" type="'.$rev_item->enclosure->attributes['type'].'"/>
<guid isPermaLink="false">'.$guid.'</guid>
<enclosure url="'.$url.'" length="0" type="audio/mpeg"/>
</item>';
$on++;
}


 $html_item .= '</channel>
</rss>';    

$html_item = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $html_item);

    $replaced = str_replace(' ', '-', $merge_name);

if (!file_exists(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml')) {
    mkdir(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/', 0777, true);
}
 file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml', $html_item);
 $jemi = get_site_url().'/wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml';

$jkons = implode(',', $inserted_ids); 

$query = "UPDATE  rss_feed_url SET merge_url = '".$jemi."' WHERE id IN (".$jkons.");";


    $sql = $wpdb->query($query);

    $querys = "INSERT INTO feed_url_cron (merge_url, user_id, merge_name) VALUES ('".$jemi."','".$user_id."','".$merge_name."')";

    $sqls = $wpdb->query($querys);
//}     
         }//count else end
		} //feed end
       } //plan else end
      
    } //submit end


        ?>
<style>
.chanl_info .form-group label, .chanl_info .form-group input {
    width: 40% !important;
    display: inline-block;
    position: relative;
}

.chanl_info .form-group label {
    width: 20% !important;
    font-size: 18px;
    font-weight: 500;
}

.chanl_info {
    border: 1px solid #0000002b;
    border-radius: 4px;
    padding: 20px;
    box-shadow: 0 0 5px 3px #0000002b;
    margin: 20px 0;
}

.chanl_info .form-group {
    margin: 6px 0;
}

.chanl_info h3 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 30px;
}


label.form-control {
    font-size: 17px;
    text-align: left !important;
    margin: 20px 0;
    display: inline-block;
    margin-right: 20px;
}

.generator_frm h1 {
    text-align: center;
}

input.merge_name {
    width: 40% !important;
    display: inline-block;
    border: 1px solid #000;
}

input.generator_submit {
}
.generator_submit input[type="submit"]:hover {
    border: 2px solid #174de7ad;
    box-shadow: 0 0 4px 1px #174de7ad;
}

.generator_frm + a {
    margin-top: 10px;
    font-size: 15px;
}

.generator_frm {
    margin-bottom: 10px;
}
.labl_form input[type="submit"] {
    display: block;
    padding: 6px;
    margin-top: 10px;
    background-color: #174de7;
    color: #fff;
    border: 1px solid;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}
.generator_frm {
    width: 90%;
    margin: 0 auto;
    display: block;
    text-align: left;
}

.generator_frm textarea {
    border: 1px solid #000;
    border-radius: 3px;
    font-size: 22px;
    width: 100%;
    margin-top: 20px;
    padding: 20px;
    height: 120px;
    resize: none;
}

.generator_frm input.generator_submit {
}

.rss_feed_generator a {
    width: 90%;
    margin: 20px auto 20px;
    display: block;
}
.sucs_msg {
    font-size: 18px;
    padding: 15px;
    color: green;
}
.merge_url_cls{
    font-size: 18px;
    padding: 15px;
}
</style>
<div class="rss_feed_generator">
    <div class="generator_frm">
            <h1>RSS Feed Generator</h1>
        <form method="post" action="" name="rss_generator">
             <?php 
                if($_POST['urls']){
                   ?>
               <div class="sucs_msg">Successfully generated with the new RSS feed Kindly find below link</div>
               <?php $replaced = str_replace(' ', '-', $merge_name); ?>
               <a class="merge_url_cls" target="_blank" href="<?php echo get_site_url().'/feed/'.$replaced; ?>">Click to open Generated XML file</a>
              <?php } ?>
            <div class="labl_form">
                <div class="chanl_info">
                    <h3>Channel Info</h3>
    <div class="form-group">
        <label for="chanel_title">Title</label>
        <input type="text" name="chanel_title" class="form-control" id="chanel_title" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_link">Link</label>
        <input type="text" name="chanel_link" class="form-control" id="chanel_link" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_language">Language</label>
        <input type="text" name="chanel_language" class="form-control" id="chanel_language" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_copyright">Copyright</label>
        <input type="text" name="chanel_copyright" class="form-control" id="chanel_copyright" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_description">Description</label>
        <input type="text" name="chanel_description" class="form-control" id="chanel_description" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_image">Image</label>
        <input type="text" name="chanel_image" class="form-control" id="chanel_image" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_explicit">iTunes: explicit</label>
        <input type="text" name="chanel_explicit" class="form-control" id="chanel_explicit" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_type">iTunes:type</label>
        <input type="text" name="chanel_type" class="form-control" id="chanel_type" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_subtitle">iTunes: subtitle</label>
        <input type="text" name="chanel_subtitle" class="form-control" id="chanel_subtitle" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_author">iTunes author</label>
        <input type="text" name="chanel_author" class="form-control" id="chanel_author" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_summary">iTunes summary</label>
        <input type="text" name="chanel_summary" class="form-control" id="chanel_summary" placeholder="">
    </div>
    <!-- <div class="form-group">
        <label for="chanel_owner">iTunes owner</label>
        <input type="text" name="chanel_owner" class="form-control" id="chanel_owner" placeholder="">
    </div> -->
    <div class="form-group">
        <label for="chanel_name">iTunes name</label>
        <input type="text" name="chanel_name" class="form-control" id="chanel_name" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_email">iTunes email</label>
        <input type="text" name="chanel_email" class="form-control" id="chanel_email" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_i_image">iTunes image</label>
        <input type="text" name="chanel_i_image" class="form-control" id="chanel_i_image" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_categories">iTunes categories</label>
        <input type="text" name="chanel_categories" class="form-control" id="chanel_categories" placeholder="">
    </div>
                </div>
                <textarea class="form-control spacer10 fix-form-width" name="urls" rows="30" cols="200"></textarea>
                <label class="form-control">Merge name</label>
                <input type="text" name="merge_name" class="merge_name">
                <input class="generator_submit" type="submit" name="generator_submit" value="Generate">
            </div>
        </form>
        </div>
</div>
<?php

    }

    function rss_feed_list(){

    $user_id = get_current_user_id();


      global $wpdb;

      if($_REQUEST['merge_name'] != ""){
        $mer_delete = $wpdb->delete('feed_url_cron', array( 'merge_name' => urldecode($_REQUEST['merge_name']) ));
        $feed_delete = $wpdb->delete('rss_feed_url', array( 'merge_name' => urldecode($_REQUEST['merge_name']) ));
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

 <div class="add_btn"><h3 id="number">Number of times accessed with a Merger : <?php echo count($feed_urls); ?> </h3></div>

<!-- satheesh changes 07-02-2023 -->
<?php 
if(is_admin())
    $addbtn_url = get_site_url().'/wp-admin/admin.php?page=rss_feed_generator';
else
    $addbtn_url = get_site_url();
 ?>
<div class="add_btn"><a href="<?php echo $addbtn_url; ?>"><input type="button" name="Add_feed" value="Generate New Merge"></a></div>
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
                Channel info
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
    <tr id="row_<?php echo $key->merge_name;?>">
        <td>
            <?php echo $i; ?>
        </td>
        <td>
            <?php $replaced = str_replace(' ', '-', $key->merge_name); ?>
            <a target="_blank" href="<?php echo get_site_url().'/feed/'.$replaced; ?>"><?php echo get_site_url().'/feed/'.$replaced; ?></a>
        </td>
        <td>
            <?php echo $key->merge_name; ?>
        </td>
       
        <td>
          <!-- Satheesh changes 07-02-2023 -->
          <?php 
            if(is_admin())
                $update_channel_url = get_site_url().'/wp-admin/admin.php?page=update_channel&merge_name='.$key->merge_name;
            else
                $update_channel_url = get_site_url().'/update-channel/?merge_name='.$key->merge_name;
                 ?>
            <a target="_blank" href="<?php echo $update_channel_url; ?>".>Update Channel Info</a>
        </td>
        <td>
          <!-- Satheesh changes 07-02-2023 -->
          <?php 
           if(is_admin())
            $add_feed_url = get_site_url().'/wp-admin/admin.php?page=feed_url_list&merge_name='.$key->merge_name;
           else
            $add_feed_url = get_site_url().'/url-list/?merge_name='.$key->merge_name;
           ?>
            <a target="_blank" href="<?php echo $add_feed_url; ?>">Add / Remove Feeds</a>
          
        </td>
        <td>
           <!-- <a href="javascript:void(0);" data-id="<?php echo urlencode($key->merge_name); ?>" class="btn btn-danger delete-feed" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a> -->
        <a href="javascript:void(0)" onclick="delete_feed('<?php echo $key->merge_name;?>')" class="del_btn"><i class="fa fa-trash"></i></a> 
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

function delete_feed(id){
    
    var base_url="<?php echo get_site_url();?>";
    //var table = $("#table_id").dataTable();
      if (confirm("Are you sure to delete?")) {  
        $.ajax({
        type: "POST",
        url: base_url +  "/wp-admin/admin-ajax.php",
        dataType: 'JSON',
        ajax: "data.json",
        data: {
            action: "delete_feed", 
            id : id
        },
        success: function(result) {
         if(result == 0){
            $("#row_"+id).remove();
            var table = document.getElementById("table_id");
            const rowCount1 = table.querySelectorAll("tr").length;
            const rowCount = table.rows.length - 1;
            if(rowCount1 - 1 == 0){
                var row = table.insertRow(); 
                var cell1 = row.insertCell(0);
                cell1.setAttribute("colspan", "6");
                cell1.innerHTML =  '<p style="text-align:center">No data available in table</p>';
                $("#table_id_info").html("Showing 0 to 0 of 0 entries"); 
                $("#number").html("Number of times accessed with a Merger : 0")
            }else{
                const myDiv = document.getElementById("table_id_info");
                myDiv.innerHTML = "Showing 1 to "+ rowCount +" of "+ rowCount +" entries";  

                const total = document.getElementById("number");
                total.innerHTML = "Number of times accessed with a Merger : "+rowCount;
            }
          }
        },
    });
}
  return false;
}
    $('#table_id').DataTable();
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


    }

function feed_url_list(){
     $user_id = get_current_user_id();


      global $wpdb;
     
      if($_REQUEST['delete_id'] != ""){
        $kk = $wpdb->delete( 'rss_feed_url', array( 'id' => $_REQUEST['delete_id'] ) );
        if($kk){
            do_action('wp_ajax_feed_generator_cron');
        }
      }



      
      if($_REQUEST['feed_url'] != ""){

        $addfeednames = $wpdb->get_results("SELECT * FROM rss_feed_url WHERE merge_name='".urldecode($_REQUEST['merge_name'])."' AND merge_url= '".$_REQUEST['merge_url']."' group by merge_name");
       global $wpdb;
           $get_stats = "SELECT * FROM wp_pmpro_memberships_users WHERE user_id = '".$user_id."' AND status = 'active'";
           $sql2 = $wpdb->get_results($get_stats);
    if (count($sql2) < 1) {
        echo "<div class='error_msg'>Please select any plan first</div>";
    }
    else{  
        $membership =  "SELECT * FROM rss_subscribe_settings WHERE subs_id = '".$sql2[0]->membership_id."'";
            $plan = $wpdb->get_results($membership);
        $merge_count =  "SELECT * FROM rss_feed_url WHERE merge_name = '".$addfeednames[0]->merge_name."'";
            $count_merge = $wpdb->get_results($merge_count);

        if (count($count_merge) > $plan[0]->source_limit) {
            echo "<div class='error_msg'>Please Check your source limit</div>";
        }
        else{
        $query = 'INSERT INTO rss_feed_url (feed_url,user_id,merge_name,merge_url,chanel_title,chanel_link,chanel_language,chanel_copyright,chanel_description,chanel_image,chanel_explicit,chanel_type,chanel_subtitle,chanel_author,chanel_summary,chanel_name,chanel_email,chanel_i_image,chanel_categories) VALUES ("'.$_REQUEST["feed_url"].'","'.$user_id.'","'.urldecode($_REQUEST["merge_name"]).'","'.$_REQUEST["merge_url"].'","'.$addfeednames[0]->chanel_title.'","'.$addfeednames[0]->chanel_link.'","'.$addfeednames[0]->chanel_language.'","'.$addfeednames[0]->chanel_copyright.'","'.$addfeednames[0]->chanel_description.'","'.$addfeednames[0]->chanel_image.'","'.$addfeednames[0]->chanel_explicit.'","'.$addfeednames[0]->chanel_type.'","'.$addfeednames[0]->chanel_subtitle.'","'.$addfeednames[0]->chanel_author.'","'.$addfeednames[0]->chanel_summary.'","'.$addfeednames[0]->chanel_name.'","'.$addfeednames[0]->chanel_email.'","'.$addfeednames[0]->chanel_i_image.'","'.$addfeednames[0]->chanel_categories.'")';

       // echo $query;exit();
        
    $sql = $wpdb->query($query);
  }
}
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
<?php 

if(is_admin())
    $new_feed_url = get_site_url().'/wp-admin/admin.php?page=feed_url_list';
else
    $new_feed_url = get_site_url().'/url-list';;


 ?>
<form style="display: none;" method="get" action="<?php echo $new_feed_url; ?>" name="add_feed" class="add_feed">
<div class="feed-section">
<label class="feed_label">Feed URL</label>
<input type="hidden" name="page" value="feed_url_list">
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
    <tr id="row_<?php echo $key->id;?>">
        <td>
            <?php echo $i; ?>
        </td>
        <td>
            <a target="_blank" href="<?php echo $key->feed_url; ?>"><?php echo $key->feed_url; ?></a>
        </td>
        <td>
            <?php echo $key->merge_name; ?>
        </td>
        <td>
            <!-- <a href="javascript:void(0);" data-id="<?php echo $key->id; ?>" class="btn btn-danger delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a> -->
            <a href="javascript:void(0)" onclick="delete_single_feed('<?php echo $key->id;?>')" class="del_btn"><i class="fa fa-trash"></i></a>
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

function delete_single_feed(id){
    
    var base_url="<?php echo get_site_url();?>";
      if (confirm("Are you sure to delete?")) {  
        $.ajax({
        type: "POST",
        url: base_url +  "/wp-admin/admin-ajax.php",
        dataType: 'JSON',
        data: {
            action: "delete_feed_url", 
            id : id
        },
        success: function(result) {
         if(result ==0){
              $("#row_"+id).remove(); 
                          var table = document.getElementById("table_id");
            const rowCount1 = table.querySelectorAll("tr").length;
            const rowCount = table.rows.length - 1;
            if(rowCount1 - 1 == 0){
                var row = table.insertRow(); 
                var cell1 = row.insertCell(0);
                cell1.setAttribute("colspan", "6");
                cell1.innerHTML =  '<p style="text-align:center">No data available in table</p>';
                $("#table_id_info").html("Showing 0 to 0 of 0 entries"); 
            }else{
                const myDiv = document.getElementById("table_id_info");
                myDiv.innerHTML = "Showing 1 to "+ rowCount +" of "+ rowCount +" entries";  
            }
          }
        },
    });
}
  return false;
}
    $('#table_id').DataTable();
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
}

function get_encloser($enclosure=[]){
            foreach ($enclosure as $key) {
                 $url_encloser=$key['url'];
            }
           return $url_encloser;
        }

function update_channel(){

      $user_id = get_current_user_id();


      global $wpdb;

      if(isset($_POST['generator_channel'])){
        // echo "<pre>";print_r($_POST);exit;


$query = "UPDATE  rss_feed_url SET 
            chanel_title = '".$_POST['chanel_title']."',
            chanel_link = '".$_POST['chanel_link']."',
            chanel_language = '".$_POST['chanel_language']."',
            chanel_copyright = '".$_POST['chanel_copyright']."',
            chanel_description = '".$_POST['chanel_description']."',
            chanel_image = '".$_POST['chanel_image']."',
            chanel_explicit = '".$_POST['chanel_explicit']."',
            chanel_type = '".$_POST['chanel_type']."',
            chanel_subtitle = '".$_POST['chanel_subtitle']."',
            chanel_author = '".$_POST['chanel_author']."',
            chanel_summary = '".$_POST['chanel_summary']."',
            chanel_name = '".$_POST['chanel_name']."',
            chanel_email = '".$_POST['chanel_email']."',
            chanel_i_image = '".$_POST['chanel_i_image']."',
            chanel_categories = '".$_POST['chanel_categories']."'
            WHERE merge_name = '".urldecode($_REQUEST['merge_name'])."' AND user_id = ".$user_id;


    $sql = $wpdb->query($query);

    // wp_redirect(get_site_url());

    do_action('wp_ajax_feed_generator_cron');

      }

      if($_REQUEST['merge_name'] != "")
        $feed_urls = $wpdb->get_results("SELECT * FROM rss_feed_url WHERE user_id = '".$user_id."' AND merge_name = '".urldecode($_REQUEST['merge_name'])."' group by merge_name;");

    // echo "<pre>";print_r($feed_urls);exit;

  
if(is_admin())
    $up_form_url =   get_site_url().'/wp-admin/admin.php?page=update_channel&merge_name='.urlencode($_REQUEST['merge_name']);
else
    $up_form_url = get_site_url().'/update-channel/?merge_name='.urlencode($_REQUEST['merge_name']);
?>

<form name="chanel_update" method="post" action="<?php echo $up_form_url; ?>">

<div class="chanl_info">
  <?php 
   if(is_admin())
    $add_form_url = get_site_url().'/wp-admin/admin.php?page=rss_feed_generator';
   else
    $add_form_url = get_site_url();
  ?>
    <div class="add_btn"><a href="<?php echo $add_form_url; ?>"><input type="button" name="Add_feed" value="Generate New Merge"></a></div>
                    <h3>Channel Info</h3>
    <div class="form-group">
        <label for="chanel_title">Title</label>
        <input type="text" name="chanel_title" class="form-control" id="chanel_title" value="<?php echo $feed_urls[0]->chanel_title; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_link">Link</label>
        <input type="text" name="chanel_link" class="form-control" id="chanel_link" value="<?php echo $feed_urls[0]->chanel_link; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_language">Language</label>
        <input type="text" name="chanel_language" class="form-control" id="chanel_language" value="<?php echo $feed_urls[0]->chanel_language; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_copyright">Copyright</label>
        <input type="text" name="chanel_copyright" class="form-control" id="chanel_copyright" value="<?php echo $feed_urls[0]->chanel_copyright; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_description">Description</label>
        <input type="text" name="chanel_description" class="form-control" id="chanel_description" value="<?php echo $feed_urls[0]->chanel_description; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_image">Image</label>
        <input type="text" name="chanel_image" class="form-control" id="chanel_image" value="<?php echo $feed_urls[0]->chanel_image; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_explicit">iTunes: explicit</label>
        <input type="text" name="chanel_explicit" class="form-control" id="chanel_explicit" value="<?php echo $feed_urls[0]->chanel_explicit; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_type">iTunes:type</label>
        <input type="text" name="chanel_type" class="form-control" id="chanel_type" value="<?php echo $feed_urls[0]->chanel_type; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_subtitle">iTunes: subtitle</label>
        <input type="text" name="chanel_subtitle" class="form-control" id="chanel_subtitle" value="<?php echo $feed_urls[0]->chanel_subtitle; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_author">iTunes author</label>
        <input type="text" name="chanel_author" class="form-control" id="chanel_author" value="<?php echo $feed_urls[0]->chanel_author; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_summary">iTunes summary</label>
        <input type="text" name="chanel_summary" class="form-control" id="chanel_summary" value="<?php echo $feed_urls[0]->chanel_summary; ?>" placeholder="">
    </div>
    <!-- <div class="form-group">
        <label for="chanel_owner">iTunes owner</label>
        <input type="text" name="chanel_owner" class="form-control" id="chanel_owner" placeholder="">
    </div> -->
    <div class="form-group">
        <label for="chanel_name">iTunes name</label>
        <input type="text" name="chanel_name" class="form-control" id="chanel_name" value="<?php echo $feed_urls[0]->chanel_name; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_email">iTunes email</label>
        <input type="text" name="chanel_email" class="form-control" id="chanel_email" value="<?php echo $feed_urls[0]->chanel_email; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_i_image">iTunes image</label>
        <input type="text" name="chanel_i_image" class="form-control" id="chanel_i_image" value="<?php echo $feed_urls[0]->chanel_i_image; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_categories">iTunes categories</label>
        <input type="text" name="chanel_categories" class="form-control" id="chanel_categories" value="<?php echo $feed_urls[0]->chanel_categories; ?>" placeholder="">
    </div>
    <input type="hidden" name="merge_name" value="<?php echo $feed_urls[0]->merge_name; ?>">
    <input class="generator_submit" type="submit" name="generator_channel" value="Generate">
                </div>

     </form>  
     <style type="text/css">
         .chanl_info .form-group label, .chanl_info .form-group input {
    width: 40% !important;
    display: inline-block;
    position: relative;
}

.chanl_info .form-group label {
    width: 20% !important;
    font-size: 18px;
    font-weight: 500;
}

.chanl_info {
    border: 1px solid #0000002b;
    border-radius: 4px;
    padding: 20px;
    box-shadow: 0 0 5px 3px #0000002b;
    margin: 20px 0;
}

.chanl_info .form-group {
    margin: 6px 0;
}

.chanl_info h3 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 30px;
}

.generator_submit input[type="submit"] {
    display: block;
    padding: 6px;
    margin-top: 10px;
    background-color: #174de7;
    color: #fff;
    border: 1px solid;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
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




}


add_action('init', 'trade_add_rewrite_rule');
function trade_add_rewrite_rule(){

    $urltd = $_SERVER['REQUEST_URI']; 

    $keywords = explode('/',$urltd);

    if($keywords[1] == 'feed'){
    add_rewrite_rule( '/^feed/[a-z0-9]/?$/', 'index.php?pagename=feed&id=$matches[1]', 'top' );    
}
    
}
add_action('query_vars','trade_set_query_var');
function trade_set_query_var($vars) {

    $urltd = $_SERVER['REQUEST_URI']; 

    $keywords = explode('/',$urltd);

    if($keywords[1] == 'feed'){

    array_push($vars, 'feed');

}
    return $vars;
    }

    add_action( 'parse_request', 'trade_include_template');
function trade_include_template(&$wp){ 

//echo $wp->request.'<pre>'; print_r($wp); exit;


    $urltd = $_SERVER['REQUEST_URI']; 

    $keywords = explode('/',$urltd);

    if($keywords[1] == 'feed'){

    if (strpos($wp->request,'feed') !== false)
    {
        order_status($wp->request);
        
        exit();
    }

}
}

function order_status($feed_slug){

    $exdata = explode('/',$feed_slug);
     $replaced = str_replace('-', ' ', $exdata[1]); 
global $wpdb;
$details = $wpdb->get_row("SELECT merge_url FROM rss_feed_url WHERE merge_name = '".$replaced."' ;");


$merg_url = str_replace(get_site_url().'/',"",trim($details->merge_url));
//  echo ABSPATH.$merg_url;exit;
header('Content-type: text/xml');
    readfile(ABSPATH.$merg_url);

    
exit;
}

function feed_settings(){

      global $wpdb;

        $get_sub = $wpdb->get_results("SELECT * FROM rss_subscribe_settings;");        
   
        
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container my_feeds">

  <h2>Subscription plan details:</h2>
<table id="table_id" class="display table_trade">
    <thead style="text-transform: uppercase;">
        <tr>
            <th>
                id
            </th>
            <th>
                Plan Name
            </th>
            <th>
                Price
            </th>
            <th>
                Feed limit
            </th>
            <th>
                URL limit
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        for ($j=0; $j < count($get_sub); $j++) {
            ?>
    <tr>
        <td>
            <?php echo $i; ?>
        </td>
        <td>
            <?php echo $get_sub[$j]->subs_planname; ?>
        </td>
        <td>
            $<?php echo number_format($get_sub[$j]->subs_payment, 2, '.', ''); ?>
        </td>
        <td style="text-align: center;">
            <?php echo $get_sub[$j]->feed_limit; ?>
        </td>
        <td style="text-align: center;">
            <?php echo $get_sub[$j]->source_limit; ?>
        </td>
        <!-- <td style="text-align: center;">
            <a href="javascript:void(0);" data-id="<?php echo $key->id; ?>" class="btn btn-danger delete" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
          
        </td> -->
      <td><a target="_blank" href="<?php echo get_site_url().'/wp-admin/admin.php?page=update_feed&subs_id='.$get_sub[$j]->subs_id; ?>">Update Feed Limit</a></td>
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
  console.log('<?php echo get_site_url();?>/wp-admin/admin.php?page=feed_settings&subs_id='+$(this).attr('data-id'));
         window.location.replace('<?php echo get_site_url();?>/wp-admin/admin.php?page=feed_settings&subs_id='+$(this).attr('data-id'));
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
do_action("feed_settings");
}

 function update_feed(){

      $user_id = get_current_user_id();

      global $wpdb;

      if(isset($_POST['feed_count'])){
        // echo "<pre>";print_r($_POST);exit;


    $query = "UPDATE rss_subscribe_settings SET 
            feed_limit = '".$_POST['feed_limit']."',
            feed_description = '".$_POST['feed_desc']."',
            url_description = '".$_POST['url_desc']."',
            source_limit = '".$_POST['url_limit']."'
            WHERE subs_id = '".($_REQUEST['subs_id'])."'";

    $sql = $wpdb->query($query);
    
    if ($sql == 1) { ?>
       <script type="text/javascript">
         jQuery(document).ready(function($) {
                jQuery("#info").hide();
                jQuery("#msg").show();
          });
        </script> 
    <?php }
      }

//do_action('update_feed_settings', $sql);

      if($_REQUEST['subs_id'] != "")
        $feed_limit = $wpdb->get_results("SELECT * FROM rss_subscribe_settings WHERE subs_id = '".($_REQUEST['subs_id'])."' group by subs_id;");

     #echo "<pre>";print_r($feed_limit);exit;

  
?>

<form name="feed_limit" method="post" action="<?php echo get_site_url().'/wp-admin/admin.php?page=update_feed&subs_id='.($_REQUEST['subs_id']); ?>">
  
<div class="chanl_info" id="info">
   <h3>Subscription Info</h3>
    <div class="form-group">
        <label for="plan_name">Plan Name</label>
        <input type="text" name="plan_name" class="form-control" id="plan_name" value="<?php echo $feed_limit[0]->subs_planname; ?>" placeholder="" readonly>
    </div>
    <div class="form-group">
        <label for="plan_price">Price</label>
        <input type="text" name="plan_price" class="form-control" id="plan_price" value="$<?php echo number_format($feed_limit[0]->subs_payment, 2, '.', ''); ?>" placeholder="" readonly>
    </div>
    <div class="form-group">
        <label for="feed_limit">Feed limit</label>
        <input type="text" name="feed_limit" class="form-control" id="feed_limit" value="<?php echo $feed_limit[0]->feed_limit; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="feed_desc">Feed Description</label>
        <input type="text" name="feed_desc" class="form-control" id="feed_desc" value="<?php echo $feed_limit[0]->feed_description; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="url_limit">URL limit</label>
        <input type="text" name="url_limit" class="form-control" id="url_limit" value="<?php echo $feed_limit[0]->source_limit; ?>" placeholder="">
    </div>
    <div class="form-group">
        <label for="url_desc">URL Description</label>
        <input type="text" name="url_desc" class="form-control" id="url_desc" value="<?php echo $feed_limit[0]->url_description; ?>" placeholder="">
    </div>
    <input type="hidden" name="subs_id" value="<?php echo $feed_limit[0]->subs_id; ?>">
    <input class="generator_submit" type="submit" name="feed_count" value="Update">
</div>
    <div class="chanl_info" id="msg" style="display:none;">
        <p style="font-size: 18px;">Limit has been updated</p>
    </div>
     </form>  


<style type="text/css">
.chanl_info .form-group label, .chanl_info .form-group input {
    width: 40% !important;
    display: inline-block;
    position: relative;
}

.chanl_info .form-group label {
    width: 20% !important;
    font-size: 18px;
    font-weight: 500;
}

.chanl_info {
    border: 1px solid #0000002b;
    border-radius: 4px;
    padding: 20px;
    box-shadow: 0 0 5px 3px #0000002b;
    margin: 20px 0;
}

.chanl_info .form-group {
    margin: 6px 0;
}

.chanl_info h3 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 30px;
}

.generator_submit input[type="submit"] {
    display: block;
    padding: 6px;
    margin-top: 10px;
    background-color: #174de7;
    color: #fff;
    border: 1px solid;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
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


}   

//add subscription details to the new settings table - satheesh changes 07-02-2023
        

add_action('pmpro_save_membership_level', 'add_level');

function add_level($saveid='')
{
    $id = $saveid;
    global $wpdb;
    $get_level = $wpdb->get_row("SELECT * FROM wp_pmpro_membership_levels WHERE id = $saveid");
    $get_sub = $wpdb->get_results("INSERT INTO rss_subscribe_settings (subs_id,subs_planname,subs_payment) VALUES ('".$get_level->id."','".$get_level->name."','".$get_level->initial_payment."')");
}

add_action('pmpro_delete_membership_level', 'del_level');
    
 function del_level($ml_id='')
  {
    global $wpdb;
    $sqlQuery = $wpdb->query("DELETE FROM rss_subscribe_settings WHERE subs_id = $ml_id");
  }

include_once dirname(__FILE__) . '/page-template.php';