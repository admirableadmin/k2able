<?php

/*
http://groups.google.com/group/k2-support/browse_thread/thread/ef964c853505d4aa
*/

define('K2_HEADERS_DIR', get_stylesheet_directory() . '/images/headers');
define('K2_HEADERS_URL', get_stylesheet_directory_uri() . '/images/headers');

/*
blog/wp-content/themes/twentyten/author.php
https://codex.wordpress.org/Function_Reference/is_single
*/

add_action('template_entry_foot','k2able_template_entry_foot');

function k2able_template_entry_foot() {
	if ( get_the_author_meta( 'description' ) && is_single() ) : ?>

          <span class="entry-meta">Published on <time class="entry-date updated" datetime="<?=get_the_time()?>"><?=get_the_date('F j, Y')?></time></span>
          <span class="entry-meta">by <span class="author vcard"><a class="url fn n" title="<?=get_the_author()?>" href="/impressum" rel="author"><?=get_the_author()?></a></span></span>

          <div id="entry-author-info">
            <div id="author-avatar">
              <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
            </div><!-- #author-avatar -->
            <div id="author-description">
              <h2><?php printf( __( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
              <?php if (in_category('de')) : ?>
              <?php the_author_meta( 'description' ); ?>
              <?php elseif (in_category('en')) : ?>
              <?php the_author_meta( 'descen' ); ?>
              <?php endif; ?>
              <br />
              <a href="<?php the_author_meta('user_url' );?>"><?php the_author_meta('user_url' );?></a>
              <a href="<?php the_author_meta('twitter' );?>" target="_blank" rel="me"><img src="/blog/wp-content/themes/k2able/images/vector-social-media-icons/PNG/16px/twitter-2.png" alt="" height="16" width="16"></a>
              <a href="<?php the_author_meta('xing' );?>" target="_blank" rel="me"><img src="/blog/wp-content/themes/k2able/images/vector-social-media-icons/PNG/16px/xing.png" alt="" height="16" width="16"></a>
              <a href="<?php the_author_meta('github' );?>" target="_blank" rel="me"><img src="/blog/wp-content/themes/k2able/images/vector-social-media-icons/PNG/16px/github.png" alt="" height="16" width="16"></a>
              <a href="<?php the_author_meta('stackoverflow' );?>" target="_blank" rel="me"><img src="/blog/wp-content/themes/k2able/images/vector-social-media-icons/PNG/16px/stackoverflow.png" alt="" height="16" width="16"></a>
            </div><!-- #author-description  -->
          </div><!-- #entry-author-info -->
	<?php endif;
}

/*
http://www.speckygeek.com/adding-author-descriptionbiography-in-wordpress-themes/
*/
//Add Twitter in Author Profile and remove Yahoo IM, Jabber, AIM
function add_twitter_contactmethod( $contactmethods ) {
	// Add Twitter
	$contactmethods['twitter'] = 'Twitter';
	// google
	$contactmethods['google'] = 'Google';
	// xing
	$contactmethods['xing'] = 'Xing';
	// github
	$contactmethods['github'] = 'github';
	// stackoverflow
	$contactmethods['stackoverflow'] = 'stackoverflow';

	//description-en
	$contactmethods['descen'] = 'English About You';

	// Remove Yahoo IM
	unset($contactmethods['yim']);

	// Remove Jabber
	unset($contactmethods['jabber']);

	// Remove AIM
	unset($contactmethods['aim']);
	return $contactmethods;
}
add_filter('user_contactmethods','add_twitter_contactmethod',10,1);

/*
http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
http://codex.wordpress.org/Function_Reference/add_action
http://codex.wordpress.org/Meta_Tags_in_WordPress
http://codex.wordpress.org/Function_Reference/get_the_author_meta
http://codex.wordpress.org/Function_Reference/esc_attr

Adding meta-tag author ID=1
*/
add_action('wp_head', 'add_meta_author', 1);
function add_meta_author() {
	$author = get_the_author_meta( 'user_firstname', 1 );
	$author .= ' ';
	$author .= get_the_author_meta( 'user_lastname', 1 );
?>
<meta name="author" content="<?php echo esc_attr( $author ); ?>" />
<?php
}

// http://wordpress.org/support/topic/remove-ltmeta-namegenerator-contentwordpress-25-gt
remove_action('wp_head', 'wp_generator');

// away
add_action('wp_head', 'overwrite_header_image_when_away_tag_is_shown');
function overwrite_header_image_when_away_tag_is_shown() {
	if ( is_tag('away') || has_tag('away') ) :
		// from k2-for-wordpress/app/classes/header.php
		$images = K2::files_scan(K2_HEADERS_DIR . "/away", array('gif','jpeg','jpg','png'), 1);
		$size = count($images);
		if ( $size > 1 )
			$header_image = $images[ rand(0, $size - 1) ];
		else
			$header_image = $images[0];
		$image_url = K2_HEADERS_URL . "/away/$header_image"
?>

		<style type="text/css">
			#header {
				background-image: url("<?php echo $image_url; ?>") !important;
			}
		</style>
	<?php endif;
}

// countdown
// https://www.w3schools.com/howto/howto_js_countdown.asp
add_action('wp_head', 'add_countdown');
function add_countdown() {
?>
<script>
var countDownDate = new Date("March 1, 2022 9:00:00").getTime();
var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
 if (distance < 0) {
    clearInterval(x);
  } else {
    document.getElementById("countdown").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
  }
}, 1000);
</script>
<?php
}
?>
