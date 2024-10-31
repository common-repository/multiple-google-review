<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
global $wpdb;

$tablename = $wpdb->prefix . "google_review";

?>

<h1>All post google review</h1>

<table width='100%' border='1' style='border-collapse: collapse;'>
    <tr>
        <th>Review id</th>
        <th>Post id</th>
        <th>Name</th>
        <th>Text</th>
        <th>Rating</th>
        <th>&nbsp;</th>
    </tr>
	<?php
	// Select records
	$entriesList = $wpdb->get_results( "SELECT * FROM " . $tablename . " order by id desc" );
	if ( count( $entriesList ) > 0 ) {
		$count = 1;
		foreach ( $entriesList as $entry ) {
			$id                = $entry->id;
			$post_id           = $entry->post_id;
			$review_added_name = $entry->review_added_name;
			$review_text       = $entry->review_text;
			$rating            = $entry->rating;
			$review_added_time = $entry->review_added_time;

			$url             = add_query_arg(
				[
					'action'    => 'mpgr_delete_google_place_review',
					'review_id' => $id,
					'page'      => 'mpgr_allentries',
					'nonce'     => wp_create_nonce( 'mpgr_delete_place_review' ),
				], get_admin_url() . "admin-post.php"
			);
			$reviewDeleteUrl = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Delete', 'mpgr' ) . '</a>';
			echo "<tr>
      <td>" . $id . "</td>
      <td>" . $post_id . "</td>
      <td>" . $review_added_name . "</td>
      <td width='50%'>" . $review_text . "</td>
      <td>" . $rating . "</td>
      <td>" . $reviewDeleteUrl . "</td>
      </tr>
      ";
			$count ++;
		}
	} else {
		echo "<tr><td colspan='5'>No record found</td></tr>";
	}
	?>
</table>
 