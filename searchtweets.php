<?php
/*
Plugin Name: Automatic Search & Insert tweets
Plugin URI: http://www.bestiaweb.com/updatewithtweets/
Description: Automatic add tweets of a search on your pages and/or posts.
Version: 1.1
Author: BestiaWeb S.C.P.
Author URI: http://www.bestiaweb.com

Copyright 2016  BestiaWeb S.C.P.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
*/


 
function searchtweets_install() {
 
global $wpdb; 
	$table_name = $wpdb->prefix . "searchtweets_configuration";	
	
		$re = $wpdb->query("select * from $table_name");
		
		
//autos  no existe
if(empty($re))
{	
	
		$sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		emailsend mediumint( 9 ) NOT NULL,
		email longtext NOT NULL ,
		op1 longtext NOT NULL ,
		op2 longtext NOT NULL ,
		op3 longtext NOT NULL ,
		op4 longtext NOT NULL ,
		op5 longtext NOT NULL ,
		op6 longtext NOT NULL ,
		op7 longtext NOT NULL ,
		op8 longtext NOT NULL ,
		op9 longtext NOT NULL ,
		op10 longtext NOT NULL ,

			PRIMARY KEY ( `id` )	
		) ;";

		$wpdb->query($sql);

		   $blogusers = get_users('role=Administrator');
    //print_r($blogusers);

		   $email="";
    foreach ($blogusers as $user) {
        if($email=="") $email=$user->user_email;
      }

			
		$wpdb->insert(
   $table_name,
   array(
      'emailsend' => 24,
      'email' => $email,
      'op2' => 'c',
      'op3' => '0',
      'op5' => '0',
      'op7'=> '20'
      ),
   array(
   	  '%d',
      '%s',
      '%s',
      '%s',
      '%s',
      '%s'

   )
  );


	}
 
}

register_activation_hook( __FILE__, 'searchtweets_install' );

// styles

function searchtweets_add_style() {

	wp_enqueue_style('searchtweets-style', plugin_dir_url( __FILE__ ).'style.css');
}

add_action('wp_enqueue_scripts', 'searchtweets_add_style');

// head function

function searchtweets_head() {

	global $wpdb; 
	$table_name = $wpdb->prefix . "searchtweets_configuration";

	$myrows = $wpdb->get_results( "SELECT * FROM $table_name WHERE op2 = 'b'" );




}

add_action( 'wp_head', 'searchtweets_head', 0 );


function searchtweets_panel(){
	

global $wpdb; 

global $messagesearchtweets;

	$table_name = $wpdb->prefix . "searchtweets_configuration";





	if(isset($_POST["searchtweetssave"])) {

		$nonce = $_REQUEST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'searchtweets' ) ) {

     die( 'Security check' ); 

} else {

	if($_POST["op7"]<1 || $_POST["op7"]>20 || $_POST["op7"]=="") $_POST["op7"]=20;

		$wpdb->update(
  $table_name,
  array( 'emailsend' => sanitize_text_field($_POST["emailsend"]), 'op3' => sanitize_text_field($_POST["op3"]), 'op4' => sanitize_text_field($_POST["op4"]), 'op5' => sanitize_text_field($_POST["op5"]), 'op7' => sanitize_text_field($_POST["op7"])),
  array( 'op2' => 'c' )
);

	}
			
			
	}



	$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );


$nonce=wp_create_nonce( 'searchtweets' );

	?>
	<style>

	.searchtweets h1, .searchtweets span {
		    height: 64px;
    vertical-align: text-top;
	}
	.searchtweets h1, h2 {
		border-radius:5px;
		padding:10px;
		background-color: #2ecc71;
		color: #ecf0f1;

	}
		.searchtweets h3 {
		border-radius:5px;
		padding:10px;
		background-color: #e74c3c;
		color: #ecf0f1;

	}
	.searchtweets strong {
		color: #e74c3c;

	}
	.orange {
		background-color: #e67e22;
	}
	</style>
<div class="searchtweets">
	<h1><img src="<?php echo plugins_url( 'searchtweets.png', __FILE__ ); ?>" width="64px" height="64px"><span><?php _e("Update with Tweets", "searchtweets"); ?> by Bestiaweb.com</span><a href="http://www.bestiaweb.com" target="_blank" title="Design web"><img src="<?php echo plugins_url( 'bestiaweb.png', __FILE__ ); ?>"></a></h1>
	<p><?php _e("Insert tweets in your pages and/or posts. Write tweets search on each page or post. Configure every few hours a post or page is automatically updated. Dates of post and pages will be updated with the current date of update.", "searchtweets"); ?></p>
<strong><?php echo __("Last update: ", "searchtweets").' '.$myrows[0]->op1; ?></strong><br/>
	<h2><?php _e("Settings", "searchtweets"); ?></h2>


		<form method="post" action="">
				<label><?php echo _e("Updated tweets every", "searchtweets"); ?>
	<input type="text" value="<?php echo esc_attr($myrows[0]->emailsend); ?>" id="emailsend" name="emailsend">  <?php echo _e("hours", "searchtweets"); ?>.</label>
			<br/><label><?php echo _e("Updating if:", "searchtweets"); ?></label>
	<select id="op5" name="op5" onchange="jQuery('#searchtweetsopo').toggle();">
		<option value="0" <?php if($myrows[0]->op5=='0') echo 'selected'; ?>><?php echo _e("It has completed the search tweets parameter ", "searchtweets"); ?></option>
		<option value="1" <?php if($myrows[0]->op5=='1') echo 'selected'; ?>><?php echo _e("Although not search parameter(the title is used)", "searchtweets"); ?></option>
	</select>
				
<div id="searchtweetsopo" name="searchtweetsopo" style="<?php  if($myrows[0]->op5=='0') echo 'display:none;' ?>">
				<br/><label><?php echo _e("Content to update:", "searchtweets"); ?></label>
	<select id="op3" name="op3">
		<option value="0" <?php if($myrows[0]->op3=='0') echo 'selected'; ?>><?php echo _e("Pages and Posts", "searchtweets"); ?></option>
		<option value="1" <?php if($myrows[0]->op3=='1') echo 'selected'; ?>><?php echo _e("Only Pages", "searchtweets"); ?></option>
		<option value="2" <?php if($myrows[0]->op3=='2') echo 'selected'; ?>><?php echo _e("Only Posts", "searchtweets"); ?></option>
	</select>
				<br/><label><?php echo _e("Write ids pages or posts you want to exclude separated by commas:", "searchtweets"); ?></label>

	

	<input type="text" value="<?php echo esc_attr($myrows[0]->op4); ?>" id="op4" name="op4"></div>
	<br/><label><?php echo _e("Number of tweets to insert(Max 20)", "searchtweets"); ?></label>
	<input type="text" value="<?php echo esc_attr($myrows[0]->op7); ?>" id="op7" name="op7">
	<input type="hidden" name="nonce" id="nonce" value="<?php echo $nonce; ?>">
	 <br/><br/><input type='submit' class="button-primary" name='searchtweetssave' id='searchtweetssave' value='<?php echo _e("Save settings", "searchtweets"); ?>' />
	</form>

<h2><?php _e("Update tweets", "searchtweets"); ?></h2>

<p><?php _e("Unable to update many posts at once. Each time you click 'Update' 10 posts be updated. When all post are updated will see a message.", "searchtweets"); ?></p>
<strong><?php echo $messagesearchtweets; ?></strong>
 <a href="options-general.php?page=<?php echo $_GET["page"]; ?>&type=1&nonce=<?php echo $nonce; ?>" class="button-primary"><?php _e("Update", "searchtweets"); ?></a>
	

	<?php


	echo '</div>';
}




function searchtweets_add_menu(){	
	if (function_exists('add_options_page')) {
		//add_menu_page
		add_options_page('searchtweets', 'Search tweets', 'manage_options', basename(__FILE__), 'searchtweets_panel');
	}
}


if (function_exists('add_action')) {
	add_action('admin_menu', 'searchtweets_add_menu'); 
}



if(!is_admin()) {

  //Style 

  add_action('init', 'searchtweets_scan');
}

if(isset($_GET["type"]) && $_GET["type"]==1 && !isset($_POST["cont"]) && !isset($_POST["searchtweetssave"])) {

ini_set('memory_limit', '-1'); ## Avoid memory errors (i.e in foreachloop)


	global $wpdb; 
	global $messagesearchtweets;

	$table_name = $wpdb->prefix . "searchtweets_configuration";
	
	

$wpdb->update($table_name, array('op1' => date("Y-m-d H:i:s")), array('op2' => 'c'));
$query="";
$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );

if($myrows[0]->op5=='1') {

	if($myrows[0]->op3=='1') $query.="WHERE post_type = 'page'";
	if($myrows[0]->op3=='2') $query.="WHERE post_type = 'post'";


	if($myrows[0]->op4!='') {

		$arrid=explode(",", $myrows[0]->op4);

		foreach($arrid as $id) {

			if($query=="") $query.="WHERE ID <> '".$id."'";
			else $query.=" AND ID <> '".$id."'";
		}

	}

	if($myrows[0]->op6!='') {

		if($query=="") $query.="WHERE ID > '".$myrows[0]->op6."'";
		else $query.=" AND ID > '".$myrows[0]->op6."'";
	}
	
	if($query=="") $query.="WHERE post_status ='publish' ";
	else $query.=" AND post_status ='publish' ";

	$table_name2 = $wpdb->prefix . "posts"; 

	$myrows2 = $wpdb->get_results("SELECT * FROM $table_name2 ".$query." ORDER BY ID ASC");

	$cont=0;

	while(isset($myrows2[$cont]->ID) AND $cont < 10) {

		search_tweets_update_post($myrows2[$cont]->ID, $myrows[0]->op7);
		$wpdb->update($table_name, array('op6' => $myrows2[$cont]->ID), array('op2' => 'c'));
		$cont++;
	}

	if(count($myrows2)-10>0)  $messagesearchtweets= (count($myrows2)-10).__(" Post to update", "searchtweets");
	else {
		$wpdb->update($table_name, array('op6' => ''), array('op2' => 'c'));
		$messagesearchtweets= __("ALL CONTENT UPDATED", "searchtweets");
	}

}
else {

	$cond="";

	if($myrows[0]->op6!='') {

		$cond.=" AND post_id > '".$myrows[0]->op6."'";
	}

	$myrows2 = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = '_searchtweets_eng' ".$cond." ORDER BY post_id ASC");

	$cont=0;

	while(isset($myrows2[$cont]->post_id) AND $cont<10) {


		search_tweets_update_post($myrows2[$cont]->post_id, $myrows[0]->op7);
		$wpdb->update($table_name, array('op6' => $myrows2[$cont]->post_id), array('op2' => 'c'));
		$cont++;
	}

	if(count($myrows2)-10>0)  $messagesearchtweets= (count($myrows2)-10).__(" Post to update", "searchtweets");
	else {
		$wpdb->update($table_name, array('op6' => ''), array('op2' => 'c'));
		$messagesearchtweets= __("ALL CONTENT UPDATED", "searchtweets");
	}

}


}


function searchtweets_scan() {
	global $wpdb; 

	$table_name = $wpdb->prefix . "searchtweets_configuration";
	
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );


############################################ INITIATE CLASS


$diferencia = date("H", strtotime($myrows[0]->op1))-date("H");	

$horas=$myrows[0]->emailsend;

																			

	if($diferencia > $horas || $diferencia < -$horas || $myrows[0]->op1=='') {

		$wpdb->update($table_name, array('op1' => date("Y-m-d H:i:s")), array('op2' => 'c'));

		$idpostsel="";

	ini_set('memory_limit', '-1'); ## Avoid memory errors (i.e in foreachloop)



	$query="";
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );

	if($myrows[0]->op5=='1') {

		if($myrows[0]->op3=='1') $query.="WHERE post_type = 'page'";
		if($myrows[0]->op3=='2') $query.="WHERE post_type = 'post'";


		if($myrows[0]->op4!='') {

			$arrid=explode(",", $myrows[0]->op4);

			foreach($arrid as $id) {

				if($query=="") $query.="WHERE ID <> '".$id."'";
				else $query.=" AND ID <> '".$id."'";
			}

		}
		
		if($query=="") $query.="WHERE post_status ='publish' ";
		else $query.=" AND post_status ='publish' ";

		$table_name2 = $wpdb->prefix . "posts"; 

		$myrows2 = $wpdb->get_results("SELECT * FROM $table_name2 ".$query." ORDER BY RAND()");

		$idpostsel=$myrows2[0]->ID;

	}

	else {

		$cond="";

		$myrows2 = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = '_searchtweets_eng' ".$cond." ORDER BY RAND()");

		if(isset($myrows2[0]->post_id)) $idpostsel=$myrows2[0]->post_id;


	}

	if(isset($idpostsel) && $idpostsel>0) search_tweets_update_post($idpostsel, $myrows[0]->op7);

}

}


//search tweets and update post

function search_tweets_update_post($idpost, $contw) {

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  global $wpdb; 
  $table_name = $wpdb->prefix . "posts"; 
  $texto="";


  //$myrows = $wpdb->get_results("SELECT * FROM $table_name WHERE ID = '4515' OR ID = '4493' OR ID = '4478' OR ID = '4691' OR ID = '4667' OR ID = '4676' OR ID = '4476' OR ID = '4343' OR ID = '4473' OR ID = '4470' OR ID = '4483' OR ID = '38' OR ID = '4489' OR ID = '4345' OR ID = '4487' OR ID = '40' OR ID = '4535' OR ID = '4485' OR ID = '4491' OR ID = '4521' OR ID = '4337' OR ID = '4519' OR ID = '5258' ORDER BY rand()");
  $myrows = $wpdb->get_results("SELECT * FROM $table_name WHERE ID = '".$idpost."'");
 
  $conta=0;

    $id=(int)$myrows[$conta]->ID;
    $post_title=$myrows[$conta]->post_title;
    $post_content=$myrows[$conta]->post_content;
    $post_modified=$myrows[$conta]->post_modified;
    $post_modified_gmt=$myrows[$conta]->post_modified_gmt;


$search=searchtweets_get_custom_field( '_searchtweets_eng', $id );
$searchop=searchtweets_get_custom_field( '_searchtweets_img', $id );
$searchtitle=searchtweets_get_custom_field( '_searchtweets_title', $id );


if($search=="") $search=$post_title;


// buscamos los tweets de la empresa

              $textotw="";



              $query=searchtweets_sanear_string(str_replace(" ", "%20", $search));


              $url = "https://twitter.com/search?f=realtime&q=".$query;
              if($searchop==1) $url = "https://twitter.com/search?q=from%3A".$query;
              if($searchop==2) $url = "https://twitter.com/search?q=to%3A".$query;


              $ch = curl_init($url);
              $fp = fopen("tempstweets.xml", "w");
              curl_setopt($ch, CURLOPT_FILE, $fp);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // makes our request look like it was made by Firefox
              curl_exec($ch);
              curl_close($ch);
              fclose($fp);

              //Load the local XML that was created in the above CURL call
              $body = file_get_contents("tempstweets.xml");

              if($body=="") $body=file_get_contents($url);

              $a1=explode('<div class="content">', $body);

              $prime=0;
              $twitterid="";

              foreach($a1 as $a) {


              if($prime>0 && $prime<=$contw) {


              	$a2=explode('data-item-id="', $a);

                if(isset($a2[1])) {

                    $twitteridaux=explode('"', $a2[1]);

                    $twitterid=$twitteridaux[0];


                }

                $a2=explode('<span class="username js-action-profile-name" data-aria-label-part><s>@</s><b>', $a);

                $username="";

                if(isset($a2[1])) {

                  $ausername=explode('</b>', $a2[1]);
                  $username=strip_tags($ausername[0]);

                }

                $a2=explode('<img class="avatar js-action-profile-avatar" src="', $a);

                $image="";

                if(isset($a2[1])) {

                  $aimage=explode('"', $a2[1]);
                  $image=($aimage[0]);

                }

                $a2=explode('data-long-form="true">', $a);

                $data=date("d m");

                if(isset($a2[1])) {

                  $adata=explode('<', $a2[1]);
                  $data=strip_tags($adata[0]);

                }


                $a2=explode('<p class="TweetTextSize', $a);


                $textot="";

                if(isset($a2[1])) {

                  $atexto=explode('</p>', $a2[1]);
                  $textot=strip_tags('<p class="TweetTextSize'.$atexto[0]);

                }

                 if($username!="" && $twitterid!="") $textotw.='<a href="https://twitter.com/'.$username.'/status/'.$twitterid.'"></a>';

              }

                $prime++;

                

              }


              //////////////////////


              if($searchtitle!="") $texto.='<div class="searchtweets"><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script><h2>'.$searchtitle.'</h2>'.str_replace("”", "", $textotw).'</div>';
              else $texto.='<div class="searchtweets">
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>

              	<h2>Tweets '.$post_title.'</h2>'.str_replace("”", "", $textotw).'</div>';



////////////////7

$aux=explode('<div class="searchtweets">', $post_content);

if(isset($aux[1])) $post_content=$aux[0];

if(strpos($texto, "Ã")) $post_content.=utf8_decode($texto);
else $post_content.=$texto;


    // update

    $post_modified = date('Y-m-d H:i:s');

   if($textotw!="") $wpdb->update( 
  $table_name, 
  array( 
    'post_content' => $post_content,  // string
    'post_modified' => $post_modified,
    'post_modified_gmt' => $post_modified
  ), 
  array( 'ID' => $id ), 
  array( 
    '%s',
    '%s',
    '%s'
  ), 
  array( '%d' ) 
);



  }



//////
// meta tags

function searchtweets_get_custom_field( $field, $post_id = null ) {

  //* Use get_the_ID() if no $post_id is specified
  $post_id = ( null !== $post_id ? $post_id : get_the_ID() );

  if ( null === $post_id ) {
    return '';
  }

  $custom_field = get_post_meta( $post_id, $field, true );

  if ( ! $custom_field ) {
    return '';
  }

  //* Return custom field, slashes stripped, sanitized if string
  return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

}

add_action( 'admin_menu', 'searchtweets_add_inpost_seo_box' );
/**
 * Register a new meta box to the post or page edit screen, so that the user can set SEO options on a per-post or
 * per-page basis.
 *
 * If the post type does not support searchtweets-seo, then the SEO meta box will not be added.
 *
 * @since 0.1.3
 *
 * @see searchtweets_inpost_seo_box() Generates the content in the meta box.
 */
function searchtweets_add_inpost_seo_box() {

	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			add_meta_box( 'searchtweets_inpost_seo_box', __( 'Search Tweets Settings', 'searchtweets' ), 'searchtweets_inpost_seo_box', $type, 'normal', 'high' );
	}

}

/**
 * Callback for in-post SEO meta box.
 *
 * @since 0.1.3
 *
 * @uses searchtweets_get_custom_field() Get custom field value.
 */
function searchtweets_inpost_seo_box() {

	wp_nonce_field( 'searchtweets_inpost_seo_save', 'searchtweets_inpost_seo_nonce' );
	?>


<p><label for="searchtweets_eng"><strong><?php _e( 'Text search', 'searchtweets' ); ?></strong></label></p>
  <p><input class="large-text" type="text" name="searchtweets_seo[_searchtweets_eng]" id="searchtweets_eng" value="<?php echo esc_attr( searchtweets_get_custom_field( '_searchtweets_eng' ) ); ?>" /></p>

<p><label for="searchtweets_title"><strong><?php _e( 'Title', 'searchtweets' ); ?></strong></label></p>
  <p><input class="large-text" type="text" name="searchtweets_seo[_searchtweets_title]" id="searchtweets_title" value="<?php echo esc_attr( searchtweets_get_custom_field( '_searchtweets_title' ) ); ?>" /></p>
  
<p><label for="searchtweets_img"><strong><?php _e( 'Search type', 'searchtweets' ); ?></strong></label></p>
  <p>
<select name="searchtweets_seo[_searchtweets_img]" id="searchtweets_img">
	<option value="0" <?php if(esc_attr( searchtweets_get_custom_field( '_searchtweets_img' ) )==0) echo 'selected' ?>><?php _e( 'Normal search', 'searchtweets' ); ?></option>
	<option value="1" <?php if(esc_attr( searchtweets_get_custom_field( '_searchtweets_img' ) )==1) echo 'selected' ?>><?php _e( 'From these accounts', 'searchtweets' ); ?></option>
	<option value="2" <?php if(esc_attr( searchtweets_get_custom_field( '_searchtweets_img' ) )==2) echo 'selected' ?>><?php _e( 'For these accounts', 'searchtweets' ); ?></option>
</select>
</p>

	<?php

}

add_action( 'save_post', 'searchtweets_inpost_seo_save', 1, 2 );
/**
 * Save the SEO settings when we save a post or page.
 *
 * Some values get sanitized, the rest are pulled from identically named subkeys in the $_POST['searchtweets_seo'] array.
 *
 * @since 0.1.3
 *
 * @uses searchtweets_save_custom_fields() Perform checks and saves post meta / custom field data to a post or page.
 *
 * @param integer  $post_id Post ID.
 * @param stdClass $post    Post object.
 *
 * @return mixed Returns post id if permissions incorrect, null if doing autosave, ajax or future post, false if update
 *               or delete failed, and true on success.
 */
function searchtweets_inpost_seo_save( $post_id, $post ) {

	if ( ! isset( $_POST['searchtweets_seo'] ) )
		return;

	//* Merge user submitted options with fallback defaults
	$data = wp_parse_args( $_POST['searchtweets_seo'], array(
    '_searchtweets_eng'      => '',
    '_searchtweets_title'      => '',
    '_searchtweets_img'      => 0,
	) );

	//* Sanitize the title, description, and tags
	foreach ( (array) $data as $key => $value ) {
		if ( in_array( $key, array( '_searchtweets_title', '_searchtweets_eng', '_searchtweets_img' ) ) )
			$data[ $key ] = strip_tags( $value );
	}

	searchtweets_save_custom_fields( $data, 'searchtweets_inpost_seo_save', 'searchtweets_inpost_seo_nonce', $post );

}

function searchtweets_save_custom_fields( array $data, $nonce_action, $nonce_name, $post, $deprecated = null ) {

	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '2.0.0' );
	}

	//* Verify the nonce
	if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action ) )
		return;

	//* Don't try to save the data under autosave, ajax, or future post.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;

	//* Grab the post object
	if ( ! is_null( $deprecated ) )
		$post = get_post( $deprecated );
	else
		$post = get_post( $post );

	//* Don't save if WP is creating a revision (same as DOING_AUTOSAVE?)
	if ( 'revision' === get_post_type( $post ) )
		return;

	//* Check that the user is allowed to edit the post
	if ( ! current_user_can( 'edit_post', $post->ID ) )
		return;

	//* Cycle through $data, insert value or delete field
	foreach ( (array) $data as $field => $value ) {
		//* Save $value, or delete if the $value is empty
		if ( $value )
			update_post_meta( $post->ID, $field, $value );
		else
			delete_post_meta( $post->ID, $field );
	}

}

function searchtweets_sanear_string($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );


    return $string;
}

?>
