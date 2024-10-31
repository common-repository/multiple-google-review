<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
global $wpdb;

if ( current_user_can( 'administrator' ) ) {
	$nonce = wp_create_nonce( 'mpgr_find_place_review' );
	?>
    <h1>Add place google review to your post</h1>
    <form method='post' action='<?php echo get_admin_url() . "admin-post.php"; ?>'>
        <table>
            <tr>
                <td>Google Place ID</td>
                <td><input type='text' name='place_id'> Ex:<b> ChIJ95hTH7aL4zoR4CyM2-vWSyU</b></td>
            </tr>
            <tr>
                <td>Your post id</td>
                <td>
                    <input type='text' name='post_id'>
                    <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="hidden" name="action" value="mpgr_find_place_review_form">
                    <input type='submit' name='but_submit' value='Submit'></td>
            </tr>
        </table>
    </form>
	<?php
}
?>
<hr></hr>
<h1>How to connect Google reviews</h1>
To correctly use this plugin you need to find and create two things - Google Place ID and Google Places API key respectively. These are two different values, please do not confuse them.
<h2>Google Place ID</h2>
<div>
    <h3>There are two way to find Google Place id.</h3>
    <h4>First method</h4>
    The standard way to find your Google Places ID is to go to <a href="https://developers.google.com/places/place-id"
                                                                  target="_blank">https://developers.google.com/places/place-id</a>
    and search for your company name. But sometimes it just doesn’t work.
</div>
<h4>Second method</h4>
<table cellspacing="10">
    <tr>
        <td width="25%">01. Search for your business on Google.</td>
        <td><a target="_blank" href="<?php echo MPGR_PLUGIN_URL . '/img/search.jpg'; ?>"><img width="75%"
                                                                                              src="<?php echo MPGR_PLUGIN_URL . '/img/search.jpg'; ?>"></a>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr></hr>
        </td>
    </tr>
    <tr>
        <td>02. Inspect the “Write a Review” button. To do this in Firefox, right-click and choose “Inspect Element“. In
            Chrome, right-click and choose “Inspect“. (Most browsers follow a similar process.)
        </td>
        <td><a target="_blank" href="<?php echo MPGR_PLUGIN_URL . '/img/inspect.jpg'; ?>"><img width="75%"
                                                                                               src="<?php echo MPGR_PLUGIN_URL . '/img/inspect.jpg'; ?>"></a>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr></hr>
        </td>
    </tr>
    <tr>
        <td>03. Find “data-pid” as shown above. (This part is a little tricky, but just look inside the tag until you
            find data-pid=).
        </td>
        <td><a target="_blank" href="<?php echo MPGR_PLUGIN_URL . '/img/place_id.jpg'; ?>"><img width="75%"
                                                                                                src="<?php echo MPGR_PLUGIN_URL . '/img/place_id.jpg'; ?>"></a>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr></hr>
        </td>
    </tr>
    <tr>
    <tr>
        <td>04. Copy the characters within the quotes (as shown above). You now have your google Places ID. Paste this
            somewhere you can easily find it.
        </td>
        <td></td>
    </tr>
    <tr>

</table>
<hr></hr>
<h2>Your post id</h2>
<div>
    <table>
        <tr>
            <td>
                01. You can see your post id under the left side "posts" then all post
            </td>
            <td>
                <img src="<?php echo MPGR_PLUGIN_URL . '/img/post_id.jpg'; ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr></hr>
            </td>
        </tr>
        <tr>
            <td>
                02. Then click edit
            </td>
            <td>
                <img src="<?php echo MPGR_PLUGIN_URL . '/img/post_edit.jpg'; ?>">
            </td>
        </tr>
    </table>
</div>
