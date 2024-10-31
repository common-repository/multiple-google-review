<?php
/*
Plugin Name: Multiple Google Review
Plugin URI: 
Description: Add Multiple Google place Review under each post.You can you muliple place reivew in one post.
Version: 1.4.1
Author: WAP Nishantha <wapnishantha@gmail.com>
Author URI: https://googlereviewwp.enuyanu.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Repo: https://bitbucket.org/wapnishantha/multiplegooglereview/src/master/
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
define( 'MPGR_VERSION', '1.0.0' );
define( 'MPGR_PLUGIN_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );

// Add menu
function multipleGoogleReview_menu() {

	add_menu_page( "Multiple Place Google Review", "Multiple Place Google Review", "manage_options", "multipleGoogleReview", "mpgr_setting",'' );

	add_submenu_page( "multipleGoogleReview", "All Entries", "All post google review", "manage_options", "mpgr_allentries", "mpgr_display_list" );


	add_submenu_page( "multipleGoogleReview", "Add new Entry", "Add google place review to post", "manage_options", "mpgr_addnewentry", "mpgr_add_entry" );	

}

add_action( "admin_menu", "multipleGoogleReview_menu" );
function mpgr_plugin_action_links( $links, $file ) {
	$plugin_file = basename( __FILE__ );
	if ( basename( $file ) == $plugin_file ) {
		$settings_link = '<a href="' . admin_url( '?page=multipleGoogleReview' ) . '">Settings</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'mpgr_plugin_action_links', 10, 2 );
function mpgr_display_list() {
	include "displaylist.php";
}

function mpgr_setting() {
	multipleGoogleReview_page();
	include "setting.php";
}

function mpgr_add_entry() {
	include "addentry.php";
}


add_filter( 'the_content', 'mpgr_review_content' );
function mpgr_review_content( $content ) {
	global $wp_query;
	global $wpdb;
	$post_id = get_the_ID();

	$tablename   = $wpdb->prefix . "google_review";
	$entriesList = $wpdb->get_results( "SELECT * FROM " . $tablename . " WHERE post_id=" . $post_id . "  order by id desc" );
	if ( count( $entriesList ) > 0 ) {
		$count      = 0;
		$rataingval = 0;

		foreach ( $entriesList as $entry ) {
			$id                = $entry->id;
			$r_post_id         = $entry->post_id;
			$review_added_name = $entry->review_added_name;
			$review_text       = $entry->review_text;
			$rating            = $entry->rating;
			$review_added_time = $entry->review_added_time;
			$count ++;
			$rataingval += $rating;
			$greview    .= '<div id="" class="wpmgr_review" itemprop="review" itemscope="" itemtype="http://schema.org/Review">
            <meta itemprop="author" content="' . $review_added_name . '">
            <div class="wpmgr_hide" itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating">
               <meta itemprop="bestRating" content="5">
               <meta itemprop="worstRating" content="1">
               <meta itemprop="ratingValue" content="' . $rating . '">
            </div>
            <div class="wpmgr_review_ratingValue">
               <div class="wpmgr_rating_style1">
                  <div class="wpmgr_rating_style1_base ">
                     <div class="wpmgr_rating_style1_average" style="width:100%;"></div>
                  </div>
               </div>
            </div>
            <span class="wpmgr_review_datePublished" itemprop="datePublished">' . $review_added_time . '
            </span>
            <span class="wpmgr_review_author">            
            <span class="wpmgr_caps">' . $review_added_name . '</span>
            <span class="wpmgr_item_name">' . $postTitle . '</span>
            </span>
            <div class="wpmgr_clear"></div>
            <div class="wpmgr_review_title wpmgr_caps"></div>
            <div class="wpmgr_clear"></div>
            <div class="wpmgr_content" itemprop="reviewBody">
               <p>' . $review_text . '</p>
            </div>
         </div>';


		}
		$ratingAvg = $rataingval / $count;
		$args      = array(
			'post_parent'    => $post_id,
			'post_type'      => 'attachment',
			'numberposts'    => - 1, // show all
			'post_status'    => 'any',
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order',
			'order'          => 'ASC'
		);

		$images = get_posts( $args );

		$postImg = wp_get_attachment_url( $images[0]->ID );
		$rhead   = '<div class="wpmgr_item wpmgr_business" itemscope="" itemtype="http://schema.org/LocalBusiness">
         <div class="wpmgr_item_name">' . get_the_title( $post_id ) . '</div>
         <meta itemprop="name" content="' . get_the_title( $post_id ) . '">
         <meta itemprop="url" content="' . get_permalink( $post_id ) . '">
         <meta itemprop="image" content="' . $postImg . '">
         <div class="wpmgr_aggregateRating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
            <meta itemprop="bestRating" content="5">
            <meta itemprop="worstRating" content="1">
            <meta itemprop="ratingValue" content="' . $ratingAvg . '">
            <meta itemprop="reviewCount" content="' . $count . '">
            <span class="wpmgr_aggregateRating_overallText">Average rating: ' . $ratingAvg . '</span>
            <div class="wpmgr_aggregateRating_ratingValue">
               <div class="wpmgr_rating_style1">
                  <div class="wpmgr_rating_style1_base ">
                     <div class="wpmgr_rating_style1_average" style=""></div>
                  </div>
               </div>
            </div>
           <span class="wpmgr_aggregateRating_reviewCount">' . $reviwCount . ' reviews</span>
         </div>' . $greview . '</div>';
	}
	if ( is_single() && ! is_home() ) {
		$content = $content . $rhead;
	}

	return $content;
}
 

add_action( 'admin_init', function () {

	register_setting( 'multipleGoogleReview-settings', 'map_option_1' );


} );


// Fire off hooks in the admin

function multipleGoogleReview_admin_settings() {
	if ( is_admin() ) { // admin actions and filters
		// Hook for adding admin menu
		add_action( 'admin_menu', 'multipleGoogleReview_admin_menu' );
		
		add_action( 'admin_init', 'multipleGoogleReview_settings_api_init' );

		// Display the 'Settings' link in the plugin row on the installed plugins list page
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'multipleGoogleReview_admin_plugin_actions', - 10 );

	}
}

function multipleGoogleReview_page() {
	if ( current_user_can( 'manage_options' ) ) {
		?>

        <div class="wrap">

            <form action="options.php" method="post">


				<?php

				settings_fields( 'multipleGoogleReview-settings' );

				do_settings_sections( 'multipleGoogleReview-settings' );

				?>

                <table>


                    <tr>

                        <th>Google Api key</th>

                        <td><input type="text" placeholder="Google api key" name="map_option_1"
                                   value="<?php echo esc_attr( get_option( 'map_option_1' ) ); ?>" size="50"/></td>

                    </tr>


                    <tr>

                        <td><?php submit_button(); ?></td>

                    </tr>


                </table>


            </form>

        </div>

		<?php
	}
}


function mpgr_activation( $network_wide = false ) {
	$now = time();
	mpgr_activate();
}

register_activation_hook( __FILE__, 'mpgr_activation' );

function mpgr_activate() {
	mpgr_first_install();
}

function mpgr_first_install() {
	mpgr_install_db();
}

function mpgr_install_db() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "google_review (" .
	       "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT," .
	       "place_id VARCHAR(80) NOT NULL," .
	       "post_id BIGINT(20) NOT NULL," .
	       "review_text VARCHAR(10000)," .
	       "review_added_name VARCHAR(255)," .
	       "review_added_time datetime  ," .
	       "rating DOUBLE PRECISION," .
	       "review_added_timestamp INTEGER NOT NULL," .
	       "PRIMARY KEY (`id`)" .
	       ") " . $charset_collate . ";";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );
}

add_action( 'wp_enqueue_scripts', 'mpgr_enqueued_assets' );
function mpgr_enqueued_assets() {
	wp_enqueue_style( 'mpgr-css-file', plugins_url( 'css/mpgr_style.css', __FILE__ ), '', time() );
}

add_action( 'admin_post_mpgr_delete_google_place_review', 'mpgr_place_review_delete' );
add_action( 'admin_post_mpgr_find_place_review_form', 'mpgr_find_place_review_form_submit' );


function mpgr_place_review_delete() {
	global $wpdb;
	// Delete record
	if ( current_user_can( 'administrator' ) ) {
		$tablename = $wpdb->prefix . "google_review";

		if ( isset( $_GET['review_id'] ) && isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'mpgr_delete_place_review' ) ) {
			$review_id = ( isset( $_GET['review_id'] ) ) ? ( $_GET['review_id'] ) : ( 0 );
			$review_id = sanitize_text_field( $review_id );
			if ( $review_id > 0 ) {

				$wpdb->query( "DELETE FROM " . $tablename . " WHERE id=" . $review_id );
			}

		}

	}
	wp_redirect( admin_url( 'admin.php?page=mpgr_allentries' ) );
	die();
}

function mpgr_find_place_review_form_submit() {
	if ( current_user_can( 'administrator' ) ) {
		global $wpdb;
// Add record
		if ( isset( $_POST['but_submit'] ) && isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'mpgr_find_place_review' ) ) {

			$place_id = sanitize_text_field( ( isset( $_POST['place_id'] ) ) ? ( $_POST['place_id'] ) : ( 0 ) );
			$post_id = sanitize_text_field( ( isset( $_POST['post_id'] ) ) ? ( $_POST['post_id'] ) : ( 0 ) ) ;

			$googleApiKey = esc_attr( get_option( 'map_option_1' ) );
			$url          = "https://maps.googleapis.com/maps/api/place/details/json?fields=&place_id=" . $place_id . "&key=" . $googleApiKey;
			$review       = mpgr_urlopen( $url );

			$tablename = $wpdb->prefix . "google_review";
			foreach ( $review as $list ) {
				$review_added_name      = mpgr_str_replaceTxt( $list->author_name );
				$review_text            = mpgr_str_replaceTxt( $list->text );
				$rating                 = $list->rating;
				$review_added_time      = $list->time;
				$review_added_timestamp = $list->time;
				$review_added_time      = date( 'Y-m-d H:i:s', $review_added_time );

				$check_data = $wpdb->get_results( "SELECT * FROM " . $tablename . " WHERE post_id=" . $post_id . " AND review_added_timestamp='" . $review_added_timestamp . "' " );
				if ( count( $check_data ) == 0 ) {
					$insert_sql = "INSERT INTO " . $tablename . "(post_id,place_id,review_text,review_added_time,rating,review_added_name,review_added_timestamp) values('" . $post_id . "','" . $place_id . "','" . $review_text . "','" . $review_added_time . "','" . $rating . "','" . $review_added_name . "','" . $review_added_timestamp . "') ";
					$insert_sql = $wpdb->prepare( $insert_sql );
					$wpdb->query( $insert_sql );
					 
				} else {
					 
				}
			}


		}
	}

// redirect after insert alert
	wp_redirect( admin_url( 'admin.php?page=mpgr_allentries' ) );
	die();
}


function mpgr_removeEmoji( $text ) {
	$clean_text = "";

	// Match Emoticons
	$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	$clean_text     = preg_replace( $regexEmoticons, '', $text );

	// Match Miscellaneous Symbols and Pictographs
	$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	$clean_text   = preg_replace( $regexSymbols, '', $clean_text );

	// Match Transport And Map Symbols
	$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	$clean_text     = preg_replace( $regexTransport, '', $clean_text );

	// Match Miscellaneous Symbols
	$regexMisc  = '/[\x{2600}-\x{26FF}]/u';
	$clean_text = preg_replace( $regexMisc, '', $clean_text );

	// Match Dingbats
	$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
	$clean_text    = preg_replace( $regexDingbats, '', $clean_text );

	// Match Flags
	$regexDingbats = '/[\x{1F1E6}-\x{1F1FF}]/u';
	$clean_text    = preg_replace( $regexDingbats, '', $clean_text );

	// Others
	$regexDingbats = '/[\x{1F910}-\x{1F95E}]/u';
	$clean_text    = preg_replace( $regexDingbats, '', $clean_text );

	$regexDingbats = '/[\x{1F980}-\x{1F991}]/u';
	$clean_text    = preg_replace( $regexDingbats, '', $clean_text );

	$regexDingbats = '/[\x{1F9C0}]/u';
	$clean_text    = preg_replace( $regexDingbats, '', $clean_text );

	$regexDingbats = '/[\x{1F9F9}]/u';
	$clean_text    = preg_replace( $regexDingbats, '', $clean_text );

	return $clean_text;
}

function mpgr_str_replaceTxt( $text ) {
	$text = mpgr_removeEmoji( $text );
	$text = str_replace( '"', "", $text );

	return strip_tags( str_replace( "'", "", $text ) );
}

function mpgr_urlopen( $url ) {
	$response           = wp_remote_get( $url );
	$response_body      = $response['body'];
	$response_body_json = json_decode( $response_body );
	$reviews            = $response_body_json->result->reviews;

	return $reviews;
}

?>
