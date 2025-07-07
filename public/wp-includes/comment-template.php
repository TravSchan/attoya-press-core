<?php
/**
 * Comment template functions
 *
 * These functions are meant to live inside of the WordPress loop.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * Retrieves the author of the current comment.
 *
 * If the comment has an empty comment_author field, then 'Anonymous' person is
 * assumed.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to retrieve the author.
 *                                   Default current comment.
 * @return string The comment author
 */
function get_comment_author( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	if ( ! empty( $comment->comment_ID ) ) {
		$comment_id = $comment->comment_ID;
	} elseif ( is_scalar( $comment_id ) ) {
		$comment_id = (string) $comment_id;
	} else {
		$comment_id = '';
	}

	if ( empty( $comment->comment_author ) ) {
		$user = ! empty( $comment->user_id ) ? get_userdata( $comment->user_id ) : false;
		if ( $user ) {
			$comment_author = $user->display_name;
		} else {
			$comment_author = __( 'Anonymous' );
		}
	} else {
		$comment_author = $comment->comment_author;
	}

	/**
	 * Filters the returned comment author name.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_id` and `$comment` parameters were added.
	 *
	 * @param string     $comment_author The comment author's username.
	 * @param string     $comment_id     The comment ID as a numeric string.
	 * @param WP_Comment $comment        The comment object.
	 */
	return apply_filters( 'get_comment_author', $comment_author, $comment_id, $comment );
}

/**
 * Displays the author of the current comment.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to print the author.
 *                                   Default current comment.
 */
function comment_author( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$comment_author = get_comment_author( $comment );

	/**
	 * Filters the comment author's name for display.
	 *
	 * @since 1.2.0
	 * @since 4.1.0 The `$comment_id` parameter was added.
	 *
	 * @param string $comment_author The comment author's username.
	 * @param string $comment_id     The comment ID as a numeric string.
	 */
	echo apply_filters( 'comment_author', $comment_author, $comment->comment_ID );
}

/**
 * Retrieves the email of the author of the current comment.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to get the author's email.
 *                                   Default current comment.
 * @return string The current comment author's email
 */
function get_comment_author_email( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	/**
	 * Filters the comment author's returned email address.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_id` and `$comment` parameters were added.
	 *
	 * @param string     $comment_author_email The comment author's email address.
	 * @param string     $comment_id           The comment ID as a numeric string.
	 * @param WP_Comment $comment              The comment object.
	 */
	return apply_filters( 'get_comment_author_email', $comment->comment_author_email, $comment->comment_ID, $comment );
}

/**
 * Displays the email of the author of the current global $comment.
 *
 * Care should be taken to protect the email address and assure that email
 * harvesters do not capture your commenter's email address. Most assume that
 * their email address will not appear in raw form on the site. Doing so will
 * enable anyone, including those that people don't want to get the email
 * address and use it for their own means good and bad.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to print the author's email.
 *                                   Default current comment.
 */
function comment_author_email( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$comment_author_email = get_comment_author_email( $comment );

	/**
	 * Filters the comment author's email for display.
	 *
	 * @since 1.2.0
	 * @since 4.1.0 The `$comment_id` parameter was added.
	 *
	 * @param string $comment_author_email The comment author's email address.
	 * @param string $comment_id           The comment ID as a numeric string.
	 */
	echo apply_filters( 'author_email', $comment_author_email, $comment->comment_ID );
}

/**
 * Displays the HTML email link to the author of the current comment.
 *
 * Care should be taken to protect the email address and assure that email
 * harvesters do not capture your commenter's email address. Most assume that
 * their email address will not appear in raw form on the site. Doing so will
 * enable anyone, including those that people don't want to get the email
 * address and use it for their own means good and bad.
 *
 * @since 0.71
 * @since 4.6.0 Added the `$comment` parameter.
 *
 * @param string         $link_text Optional. Text to display instead of the comment author's email address.
 *                                  Default empty.
 * @param string         $before    Optional. Text or HTML to display before the email link. Default empty.
 * @param string         $after     Optional. Text or HTML to display after the email link. Default empty.
 * @param int|WP_Comment $comment   Optional. Comment ID or WP_Comment object. Default is the current comment.
 */
function comment_author_email_link( $link_text = '', $before = '', $after = '', $comment = null ) {
	$link = get_comment_author_email_link( $link_text, $before, $after, $comment );
	if ( $link ) {
		echo $link;
	}
}

/**
 * Returns the HTML email link to the author of the current comment.
 *
 * Care should be taken to protect the email address and assure that email
 * harvesters do not capture your commenter's email address. Most assume that
 * their email address will not appear in raw form on the site. Doing so will
 * enable anyone, including those that people don't want to get the email
 * address and use it for their own means good and bad.
 *
 * @since 2.7.0
 * @since 4.6.0 Added the `$comment` parameter.
 *
 * @param string         $link_text Optional. Text to display instead of the comment author's email address.
 *                                  Default empty.
 * @param string         $before    Optional. Text or HTML to display before the email link. Default empty.
 * @param string         $after     Optional. Text or HTML to display after the email link. Default empty.
 * @param int|WP_Comment $comment   Optional. Comment ID or WP_Comment object. Default is the current comment.
 * @return string HTML markup for the comment author email link. By default, the email address is obfuscated
 *                via the {@see 'comment_email'} filter with antispambot().
 */
function get_comment_author_email_link( $link_text = '', $before = '', $after = '', $comment = null ) {
	$comment = get_comment( $comment );

	/**
	 * Filters the comment author's email for display.
	 *
	 * Care should be taken to protect the email address and assure that email
	 * harvesters do not capture your commenter's email address.
	 *
	 * @since 1.2.0
	 * @since 4.1.0 The `$comment` parameter was added.
	 *
	 * @param string     $comment_author_email The comment author's email address.
	 * @param WP_Comment $comment              The comment object.
	 */
	$comment_author_email = apply_filters( 'comment_email', $comment->comment_author_email, $comment );

	if ( ( ! empty( $comment_author_email ) ) && ( '@' !== $comment_author_email ) ) {
		$display = ( '' !== $link_text ) ? $link_text : $comment_author_email;

		$comment_author_email_link = $before . sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( 'mailto:' . $comment_author_email ),
			esc_html( $display )
		) . $after;

		return $comment_author_email_link;
	} else {
		return '';
	}
}

/**
 * Retrieves the HTML link to the URL of the author of the current comment.
 *
 * Both get_comment_author_url() and get_comment_author() rely on get_comment(),
 * which falls back to the global comment variable if the $comment_id argument is empty.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to get the author's link.
 *                                   Default current comment.
 * @return string The comment author name or HTML link for author's URL.
 */
function get_comment_author_link( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$comment_id = ! empty( $comment->comment_ID ) ? $comment->comment_ID : (string) $comment_id;

	$comment_author_url = get_comment_author_url( $comment );
	$comment_author     = get_comment_author( $comment );

	if ( empty( $comment_author_url ) || 'http://' === $comment_author_url ) {
		$comment_author_link = $comment_author;
	} else {
		$rel_parts = array( 'ugc' );
		if ( ! wp_is_internal_link( $comment_author_url ) ) {
			$rel_parts = array_merge(
				$rel_parts,
				array( 'external', 'nofollow' )
			);
		}

		/**
		 * Filters the rel attributes of the comment author's link.
		 *
		 * @since 6.2.0
		 *
		 * @param string[]   $rel_parts An array of strings representing the rel tags
		 *                              which will be joined into the anchor's rel attribute.
		 * @param WP_Comment $comment   The comment object.
		 */
		$rel_parts = apply_filters( 'comment_author_link_rel', $rel_parts, $comment );

		$rel = implode( ' ', $rel_parts );
		$rel = esc_attr( $rel );
		// Empty space before 'rel' is necessary for later sprintf().
		$rel = ! empty( $rel ) ? sprintf( ' rel="%s"', $rel ) : '';

		$comment_author_link = sprintf(
			'<a href="%1$s" class="url"%2$s>%3$s</a>',
			$comment_author_url,
			$rel,
			$comment_author
		);
	}

	/**
	 * Filters the comment author's link for display.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_author` and `$comment_id` parameters were added.
	 *
	 * @param string $comment_author_link The HTML-formatted comment author link.
	 *                                    Empty for an invalid URL.
	 * @param string $comment_author      The comment author's username.
	 * @param string $comment_id          The comment ID as a numeric string.
	 */
	return apply_filters( 'get_comment_author_link', $comment_author_link, $comment_author, $comment_id );
}

/**
 * Displays the HTML link to the URL of the author of the current comment.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to print the author's link.
 *                                   Default current comment.
 */
function comment_author_link( $comment_id = 0 ) {
	echo get_comment_author_link( $comment_id );
}

/**
 * Retrieves the IP address of the author of the current comment.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to get the author's IP address.
 *                                   Default current comment.
 * @return string Comment author's IP address, or an empty string if it's not available.
 */
function get_comment_author_IP( $comment_id = 0 ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$comment = get_comment( $comment_id );

	/**
	 * Filters the comment author's returned IP address.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_id` and `$comment` parameters were added.
	 *
	 * @param string     $comment_author_ip The comment author's IP address, or an empty string if it's not available.
	 * @param string     $comment_id        The comment ID as a numeric string.
	 * @param WP_Comment $comment           The comment object.
	 */
	return apply_filters( 'get_comment_author_IP', $comment->comment_author_IP, $comment->comment_ID, $comment );  // phpcs:ignore WordPress.NamingConventions.ValidHookName.NotLowercase
}

/**
 * Displays the IP address of the author of the current comment.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to print the author's IP address.
 *                                   Default current comment.
 */
function comment_author_IP( $comment_id = 0 ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	echo esc_html( get_comment_author_IP( $comment_id ) );
}

/**
 * Retrieves the URL of the author of the current comment, not linked.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to get the author's URL.
 *                                   Default current comment.
 * @return string Comment author URL, if provided, an empty string otherwise.
 */
function get_comment_author_url( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$comment_author_url = '';
	$comment_id         = 0;

	if ( ! empty( $comment ) ) {
		$comment_author_url = ( 'http://' === $comment->comment_author_url ) ? '' : $comment->comment_author_url;
		$comment_author_url = esc_url( $comment_author_url, array( 'http', 'https' ) );

		$comment_id = $comment->comment_ID;
	}

	/**
	 * Filters the comment author's URL.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_id` and `$comment` parameters were added.
	 *
	 * @param string          $comment_author_url The comment author's URL, or an empty string.
	 * @param string|int      $comment_id         The comment ID as a numeric string, or 0 if not found.
	 * @param WP_Comment|null $comment            The comment object, or null if not found.
	 */
	return apply_filters( 'get_comment_author_url', $comment_author_url, $comment_id, $comment );
}

/**
 * Displays the URL of the author of the current comment, not linked.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or the ID of the comment for which to print the author's URL.
 *                                   Default current comment.
 */
function comment_author_url( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$comment_author_url = get_comment_author_url( $comment );

	/**
	 * Filters the comment author's URL for display.
	 *
	 * @since 1.2.0
	 * @since 4.1.0 The `$comment_id` parameter was added.
	 *
	 * @param string $comment_author_url The comment author's URL.
	 * @param string $comment_id         The comment ID as a numeric string.
	 */
	echo apply_filters( 'comment_url', $comment_author_url, $comment->comment_ID );
}

/**
 * Retrieves the HTML link of the URL of the author of the current comment.
 *
 * $link_text parameter is only used if the URL does not exist for the comment
 * author. If the URL does exist then the URL will be used and the $link_text
 * will be ignored.
 *
 * Encapsulate the HTML link between the $before and $after. So it will appear
 * in the order of $before, link, and finally $after.
 *
 * @since 1.5.0
 * @since 4.6.0 Added the `$comment` parameter.
 *
 * @param string         $link_text Optional. The text to display instead of the comment
 *                                  author's email address. Default empty.
 * @param string         $before    Optional. The text or HTML to display before the email link.
 *                                  Default empty.
 * @param string         $after     Optional. The text or HTML to display after the email link.
 *                                  Default empty.
 * @param int|WP_Comment $comment   Optional. Comment ID or WP_Comment object.
 *                                  Default is the current comment.
 * @return string The HTML link between the $before and $after parameters.
 */
function get_comment_author_url_link( $link_text = '', $before = '', $after = '', $comment = 0 ) {
	$comment_author_url = get_comment_author_url( $comment );

	$display = ( '' !== $link_text ) ? $link_text : $comment_author_url;
	$display = str_replace( 'http://www.', '', $display );
	$display = str_replace( 'http://', '', $display );

	if ( str_ends_with( $display, '/' ) ) {
		$display = substr( $display, 0, -1 );
	}

	$comment_author_url_link = $before . sprintf(
		'<a href="%1$s" rel="external">%2$s</a>',
		$comment_author_url,
		$display
	) . $after;

	/**
	 * Filters the comment author's returned URL link.
	 *
	 * @since 1.5.0
	 *
	 * @param string $comment_author_url_link The HTML-formatted comment author URL link.
	 */
	return apply_filters( 'get_comment_author_url_link', $comment_author_url_link );
}

/**
 * Displays the HTML link of the URL of the author of the current comment.
 *
 * @since 0.71
 * @since 4.6.0 Added the `$comment` parameter.
 *
 * @param string         $link_text Optional. Text to display instead of the comment author's
 *                                  email address. Default empty.
 * @param string         $before    Optional. Text or HTML to display before the email link.
 *                                  Default empty.
 * @param string         $after     Optional. Text or HTML to display after the email link.
 *                                  Default empty.
 * @param int|WP_Comment $comment   Optional. Comment ID or WP_Comment object.
 *                                  Default is the current comment.
 */
function comment_author_url_link( $link_text = '', $before = '', $after = '', $comment = 0 ) {
	echo get_comment_author_url_link( $link_text, $before, $after, $comment );
}

/**
 * Generates semantic classes for each comment element.
 *
 * @since 2.7.0
 * @since 4.4.0 Added the ability for `$comment` to also accept a WP_Comment object.
 *
 * @param string|string[] $css_class Optional. One or more classes to add to the class list.
 *                                   Default empty.
 * @param int|WP_Comment  $comment   Optional. Comment ID or WP_Comment object. Default current comment.
 * @param int|WP_Post     $post      Optional. Post ID or WP_Post object. Default current post.
 * @param bool            $display   Optional. Whether to print or return the output.
 *                                   Default true.
 * @return void|string Void if `$display` argument is true, comment classes if `$display` is false.
 */
function comment_class( $css_class = '', $comment = null, $post = null, $display = true ) {
	// Separates classes with a single space, collates classes for comment DIV.
	$css_class = 'class="' . implode( ' ', get_comment_class( $css_class, $comment, $post ) ) . '"';

	if ( $display ) {
		echo $css_class;
	} else {
		return $css_class;
	}
}

/**
 * Returns the classes for the comment div as an array.
 *
 * @since 2.7.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @global int $comment_alt
 * @global int $comment_depth
 * @global int $comment_thread_alt
 *
 * @param string|string[] $css_class  Optional. One or more classes to add to the class list.
 *                                    Default empty.
 * @param int|WP_Comment  $comment_id Optional. Comment ID or WP_Comment object. Default current comment.
 * @param int|WP_Post     $post       Optional. Post ID or WP_Post object. Default current post.
 * @return string[] An array of classes.
 */
function get_comment_class( $css_class = '', $comment_id = null, $post = null ) {
	global $comment_alt, $comment_depth, $comment_thread_alt;

	$classes = array();

	$comment = get_comment( $comment_id );
	if ( ! $comment ) {
		return $classes;
	}

	// Get the comment type (comment, trackback).
	$classes[] = ( empty( $comment->comment_type ) ) ? 'comment' : $comment->comment_type;

	// Add classes for comment authors that are registered users.
	$user = $comment->user_id ? get_userdata( $comment->user_id ) : false;
	if ( $user ) {
		$classes[] = 'byuser';
		$classes[] = 'comment-author-' . sanitize_html_class( $user->user_nicename, $comment->user_id );
		// For comment authors who are the author of the post.
		$_post = get_post( $post );
		if ( $_post ) {
			if ( $comment->user_id === $_post->post_author ) {
				$classes[] = 'bypostauthor';
			}
		}
	}

	if ( empty( $comment_alt ) ) {
		$comment_alt = 0;
	}
	if ( empty( $comment_depth ) ) {
		$comment_depth = 1;
	}
	if ( empty( $comment_thread_alt ) ) {
		$comment_thread_alt = 0;
	}

	if ( $comment_alt % 2 ) {
		$classes[] = 'odd';
		$classes[] = 'alt';
	} else {
		$classes[] = 'even';
	}

	++$comment_alt;

	// Alt for top-level comments.
	if ( 1 == $comment_depth ) {
		if ( $comment_thread_alt % 2 ) {
			$classes[] = 'thread-odd';
			$classes[] = 'thread-alt';
		} else {
			$classes[] = 'thread-even';
		}
		++$comment_thread_alt;
	}

	$classes[] = "depth-$comment_depth";

	if ( ! empty( $css_class ) ) {
		if ( ! is_array( $css_class ) ) {
			$css_class = preg_split( '#\s+#', $css_class );
		}
		$classes = array_merge( $classes, $css_class );
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the returned CSS classes for the current comment.
	 *
	 * @since 2.7.0
	 *
	 * @param string[]    $classes    An array of comment classes.
	 * @param string[]    $css_class  An array of additional classes added to the list.
	 * @param string      $comment_id The comment ID as a numeric string.
	 * @param WP_Comment  $comment    The comment object.
	 * @param int|WP_Post $post       The post ID or WP_Post object.
	 */
	return apply_filters( 'comment_class', $classes, $css_class, $comment->comment_ID, $comment, $post );
}

/**
 * Retrieves the comment date of the current comment.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param string         $format     Optional. PHP date format. Defaults to the 'date_format' option.
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to get the date.
 *                                   Default current comment.
 * @return string The comment's date.
 */
function get_comment_date( $format = '', $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$_format = ! empty( $format ) ? $format : get_option( 'date_format' );

	$comment_date = mysql2date( $_format, $comment->comment_date );

	/**
	 * Filters the returned comment date.
	 *
	 * @since 1.5.0
	 *
	 * @param string|int $comment_date Formatted date string or Unix timestamp.
	 * @param string     $format       PHP date format.
	 * @param WP_Comment $comment      The comment object.
	 */
	return apply_filters( 'get_comment_date', $comment_date, $format, $comment );
}

/**
 * Displays the comment date of the current comment.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param string         $format     Optional. PHP date format. Defaults to the 'date_format' option.
 * @param int|WP_Comment $comment_id WP_Comment or ID of the comment for which to print the date.
 *                                   Default current comment.
 */
function comment_date( $format = '', $comment_id = 0 ) {
	echo get_comment_date( $format, $comment_id );
}

/**
 * Retrieves the excerpt of the given comment.
 *
 * Returns a maximum of 20 words with an ellipsis appended if necessary.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to get the excerpt.
 *                                   Default current comment.
 * @return string The possibly truncated comment excerpt.
 */
function get_comment_excerpt( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	if ( ! post_password_required( $comment->comment_post_ID ) ) {
		$comment_text = strip_tags( str_replace( array( "\n", "\r" ), ' ', $comment->comment_content ) );
	} else {
		$comment_text = __( 'Password protected' );
	}

	/* translators: Maximum number of words used in a comment excerpt. */
	$comment_excerpt_length = (int) _x( '20', 'comment_excerpt_length' );

	/**
	 * Filters the maximum number of words used in the comment excerpt.
	 *
	 * @since 4.4.0
	 *
	 * @param int $comment_excerpt_length The amount of words you want to display in the comment excerpt.
	 */
	$comment_excerpt_length = apply_filters( 'comment_excerpt_length', $comment_excerpt_length );

	$comment_excerpt = wp_trim_words( $comment_text, $comment_excerpt_length, '&hellip;' );

	/**
	 * Filters the retrieved comment excerpt.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_id` and `$comment` parameters were added.
	 *
	 * @param string     $comment_excerpt The comment excerpt text.
	 * @param string     $comment_id      The comment ID as a numeric string.
	 * @param WP_Comment $comment         The comment object.
	 */
	return apply_filters( 'get_comment_excerpt', $comment_excerpt, $comment->comment_ID, $comment );
}

/**
 * Displays the excerpt of the current comment.
 *
 * @since 1.2.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to print the excerpt.
 *                                   Default current comment.
 */
function comment_excerpt( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	$comment_excerpt = get_comment_excerpt( $comment );

	/**
	 * Filters the comment excerpt for display.
	 *
	 * @since 1.2.0
	 * @since 4.1.0 The `$comment_id` parameter was added.
	 *
	 * @param string $comment_excerpt The comment excerpt text.
	 * @param string $comment_id      The comment ID as a numeric string.
	 */
	echo apply_filters( 'comment_excerpt', $comment_excerpt, $comment->comment_ID );
}

/**
 * Retrieves the comment ID of the current comment.
 *
 * @since 1.5.0
 *
 * @return string The comment ID as a numeric string.
 */
function get_comment_ID() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$comment = get_comment();

	$comment_id = ! empty( $comment->comment_ID ) ? $comment->comment_ID : '0';

	/**
	 * Filters the returned comment ID.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment` parameter was added.
	 *
	 * @param string     $comment_id The current comment ID as a numeric string.
	 * @param WP_Comment $comment    The comment object.
	 */
	return apply_filters( 'get_comment_ID', $comment_id, $comment );  // phpcs:ignore WordPress.NamingConventions.ValidHookName.NotLowercase
}

/**
 * Displays the comment ID of the current comment.
 *
 * @since 0.71
 */
function comment_ID() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	echo get_comment_ID();
}

/**
 * Retrieves the link to a given comment.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment` to also accept a WP_Comment object. Added `$cpage` argument.
 *
 * @see get_page_of_comment()
 *
 * @global WP_Rewrite $wp_rewrite      WordPress rewrite component.
 * @global bool       $in_comment_loop
 *
 * @param WP_Comment|int|null $comment Optional. Comment to retrieve. Default current comment.
 * @param array               $args {
 *     An array of optional arguments to override the defaults.
 *
 *     @type string     $type      Passed to get_page_of_comment().
 *     @type int        $page      Current page of comments, for calculating comment pagination.
 *     @type int        $per_page  Per-page value for comment pagination.
 *     @type int        $max_depth Passed to get_page_of_comment().
 *     @type int|string $cpage     Value to use for the comment's "comment-page" or "cpage" value.
 *                                 If provided, this value overrides any value calculated from `$page`
 *                                 and `$per_page`.
 * }
 * @return string The permalink to the given comment.
 */
function get_comment_link( $comment = null, $args = array() ) {
	global $wp_rewrite, $in_comment_loop;

	$comment = get_comment( $comment );

	// Back-compat.
	if ( ! is_array( $args ) ) {
		$args = array( 'page' => $args );
	}

	$defaults = array(
		'type'      => 'all',
		'page'      => '',
		'per_page'  => '',
		'max_depth' => '',
		'cpage'     => null,
	);

	$args = wp_parse_args( $args, $defaults );

	$comment_link = get_permalink( $comment->comment_post_ID );

	// The 'cpage' param takes precedence.
	if ( ! is_null( $args['cpage'] ) ) {
		$cpage = $args['cpage'];

		// No 'cpage' is provided, so we calculate one.
	} else {
		if ( '' === $args['per_page'] && get_option( 'page_comments' ) ) {
			$args['per_page'] = get_option( 'comments_per_page' );
		}

		if ( empty( $args['per_page'] ) ) {
			$args['per_page'] = 0;
			$args['page']     = 0;
		}

		$cpage = $args['page'];

		if ( '' == $cpage ) {
			if ( ! empty( $in_comment_loop ) ) {
				$cpage = get_query_var( 'cpage' );
			} else {
				// Requires a database hit, so we only do it when we can't figure out from context.
				$cpage = get_page_of_comment( $comment->comment_ID, $args );
			}
		}

		/*
		 * If the default page displays the oldest comments, the permalinks for comments on the default page
		 * do not need a 'cpage' query var.
		 */
		if ( 'oldest' === get_option( 'default_comments_page' ) && 1 === $cpage ) {
			$cpage = '';
		}
	}

	if ( $cpage && get_option( 'page_comments' ) ) {
		if ( $wp_rewrite->using_permalinks() ) {
			if ( $cpage ) {
				$comment_link = trailingslashit( $comment_link ) . $wp_rewrite->comments_pagination_base . '-' . $cpage;
			}

			$comment_link = user_trailingslashit( $comment_link, 'comment' );
		} elseif ( $cpage ) {
			$comment_link = add_query_arg( 'cpage', $cpage, $comment_link );
		}
	}

	if ( $wp_rewrite->using_permalinks() ) {
		$comment_link = user_trailingslashit( $comment_link, 'comment' );
	}

	$comment_link = $comment_link . '#comment-' . $comment->comment_ID;

	/**
	 * Filters the returned single comment permalink.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added the `$cpage` parameter.
	 *
	 * @see get_page_of_comment()
	 *
	 * @param string     $comment_link The comment permalink with '#comment-$id' appended.
	 * @param WP_Comment $comment      The current comment object.
	 * @param array      $args         An array of arguments to override the defaults.
	 * @param int        $cpage        The calculated 'cpage' value.
	 */
	return apply_filters( 'get_comment_link', $comment_link, $comment, $args, $cpage );
}

/**
 * Retrieves the link to the current post comments.
 *
 * @since 1.5.0
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @return string The link to the comments.
 */
function get_comments_link( $post = 0 ) {
	$hash          = get_comments_number( $post ) ? '#comments' : '#respond';
	$comments_link = get_permalink( $post ) . $hash;

	/**
	 * Filters the returned post comments permalink.
	 *
	 * @since 3.6.0
	 *
	 * @param string      $comments_link Post comments permalink with '#comments' appended.
	 * @param int|WP_Post $post          Post ID or WP_Post object.
	 */
	return apply_filters( 'get_comments_link', $comments_link, $post );
}

/**
 * Displays the link to the current post comments.
 *
 * @since 0.71
 *
 * @param string $deprecated   Not Used.
 * @param string $deprecated_2 Not Used.
 */
function comments_link( $deprecated = '', $deprecated_2 = '' ) {
	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '0.72' );
	}
	if ( ! empty( $deprecated_2 ) ) {
		_deprecated_argument( __FUNCTION__, '1.3.0' );
	}
	echo esc_url( get_comments_link() );
}

/**
 * Retrieves the amount of comments a post has.
 *
 * @since 1.5.0
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is the global `$post`.
 * @return string|int If the post exists, a numeric string representing the number of comments
 *                    the post has, otherwise 0.
 */
function get_comments_number( $post = 0 ) {
	$post = get_post( $post );

	$comments_number = $post ? $post->comment_count : 0;
	$post_id         = $post ? $post->ID : 0;

	/**
	 * Filters the returned comment count for a post.
	 *
	 * @since 1.5.0
	 *
	 * @param string|int $comments_number A string representing the number of comments a post has, otherwise 0.
	 * @param int        $post_id Post ID.
	 */
	return apply_filters( 'get_comments_number', $comments_number, $post_id );
}

/**
 * Displays the language string for the number of comments the current post has.
 *
 * @since 0.71
 * @since 5.4.0 The `$deprecated` parameter was changed to `$post`.
 *
 * @param string|false $zero Optional. Text for no comments. Default false.
 * @param string|false $one  Optional. Text for one comment. Default false.
 * @param string|false $more Optional. Text for more than one comment. Default false.
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is the global `$post`.
 */
function comments_number( $zero = false, $one = false, $more = false, $post = 0 ) {
	echo get_comments_number_text( $zero, $one, $more, $post );
}

/**
 * Displays the language string for the number of comments the current post has.
 *
 * @since 4.0.0
 * @since 5.4.0 Added the `$post` parameter to allow using the function outside of the loop.
 *
 * @param string      $zero Optional. Text for no comments. Default false.
 * @param string      $one  Optional. Text for one comment. Default false.
 * @param string      $more Optional. Text for more than one comment. Default false.
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is the global `$post`.
 * @return string Language string for the number of comments a post has.
 */
function get_comments_number_text( $zero = false, $one = false, $more = false, $post = 0 ) {
	$comments_number = get_comments_number( $post );

	if ( $comments_number > 1 ) {
		if ( false === $more ) {
			$comments_number_text = sprintf(
				/* translators: %s: Number of comments. */
				_n( '%s Comment', '%s Comments', $comments_number ),
				number_format_i18n( $comments_number )
			);
		} else {
			// % Comments
			/*
			 * translators: If comment number in your language requires declension,
			 * translate this to 'on'. Do not translate into your own language.
			 */
			if ( 'on' === _x( 'off', 'Comment number declension: on or off' ) ) {
				$text = preg_replace( '#<span class="screen-reader-text">.+?</span>#', '', $more );
				$text = preg_replace( '/&.+?;/', '', $text ); // Remove HTML entities.
				$text = trim( strip_tags( $text ), '% ' );

				// Replace '% Comments' with a proper plural form.
				if ( $text && ! preg_match( '/[0-9]+/', $text ) && str_contains( $more, '%' ) ) {
					/* translators: %s: Number of comments. */
					$new_text = _n( '%s Comment', '%s Comments', $comments_number );
					$new_text = trim( sprintf( $new_text, '' ) );

					$more = str_replace( $text, $new_text, $more );
					if ( ! str_contains( $more, '%' ) ) {
						$more = '% ' . $more;
					}
				}
			}

			$comments_number_text = str_replace( '%', number_format_i18n( $comments_number ), $more );
		}
	} elseif ( 0 == $comments_number ) {
		$comments_number_text = ( false === $zero ) ? __( 'No Comments' ) : $zero;
	} else { // Must be one.
		$comments_number_text = ( false === $one ) ? __( '1 Comment' ) : $one;
	}

	/**
	 * Filters the comments count for display.
	 *
	 * @since 1.5.0
	 *
	 * @see _n()
	 *
	 * @param string $comments_number_text A translatable string formatted based on whether the count
	 *                                     is equal to 0, 1, or 1+.
	 * @param int    $comments_number      The number of post comments.
	 */
	return apply_filters( 'comments_number', $comments_number_text, $comments_number );
}

/**
 * Retrieves the text of the current comment.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 * @since 5.4.0 Added 'In reply to %s.' prefix to child comments in comments feed.
 *
 * @see Walker_Comment::comment()
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to get the text.
 *                                   Default current comment.
 * @param array          $args       Optional. An array of arguments. Default empty array.
 * @return string The comment content.
 */
function get_comment_text( $comment_id = 0, $args = array() ) {
	$comment = get_comment( $comment_id );

	$comment_text = $comment->comment_content;

	if ( is_comment_feed() && $comment->comment_parent ) {
		$parent = get_comment( $comment->comment_parent );
		if ( $parent ) {
			$parent_link = esc_url( get_comment_link( $parent ) );
			$name        = get_comment_author( $parent );

			$comment_text = sprintf(
				/* translators: %s: Comment link. */
				ent2ncr( __( 'In reply to %s.' ) ),
				'<a href="' . $parent_link . '">' . $name . '</a>'
			) . "\n\n" . $comment_text;
		}
	}

	/**
	 * Filters the text of a comment.
	 *
	 * @since 1.5.0
	 *
	 * @see Walker_Comment::comment()
	 *
	 * @param string     $comment_text Text of the comment.
	 * @param WP_Comment $comment      The comment object.
	 * @param array      $args         An array of arguments.
	 */
	return apply_filters( 'get_comment_text', $comment_text, $comment, $args );
}

/**
 * Displays the text of the current comment.
 *
 * @since 0.71
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @see Walker_Comment::comment()
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to print the text.
 *                                   Default current comment.
 * @param array          $args       Optional. An array of arguments. Default empty array.
 */
function comment_text( $comment_id = 0, $args = array() ) {
	$comment = get_comment( $comment_id );

	$comment_text = get_comment_text( $comment, $args );

	/**
	 * Filters the text of a comment to be displayed.
	 *
	 * @since 1.2.0
	 *
	 * @see Walker_Comment::comment()
	 *
	 * @param string          $comment_text Text of the comment.
	 * @param WP_Comment|null $comment      The comment object. Null if not found.
	 * @param array           $args         An array of arguments.
	 */
	echo apply_filters( 'comment_text', $comment_text, $comment, $args );
}

/**
 * Retrieves the comment time of the current comment.
 *
 * @since 1.5.0
 * @since 6.2.0 Added the `$comment_id` parameter.
 *
 * @param string         $format     Optional. PHP date format. Defaults to the 'time_format' option.
 * @param bool           $gmt        Optional. Whether to use the GMT date. Default false.
 * @param bool           $translate  Optional. Whether to translate the time (for use in feeds).
 *                                   Default true.
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to get the time.
 *                                   Default current comment.
 * @return string The formatted time.
 */
function get_comment_time( $format = '', $gmt = false, $translate = true, $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	if ( null === $comment ) {
		return '';
	}

	$comment_date = $gmt ? $comment->comment_date_gmt : $comment->comment_date;

	$_format = ! empty( $format ) ? $format : get_option( 'time_format' );

	$comment_time = mysql2date( $_format, $comment_date, $translate );

	/**
	 * Filters the returned comment time.
	 *
	 * @since 1.5.0
	 *
	 * @param string|int $comment_time The comment time, formatted as a date string or Unix timestamp.
	 * @param string     $format       PHP date format.
	 * @param bool       $gmt          Whether the GMT date is in use.
	 * @param bool       $translate    Whether the time is translated.
	 * @param WP_Comment $comment      The comment object.
	 */
	return apply_filters( 'get_comment_time', $comment_time, $format, $gmt, $translate, $comment );
}

/**
 * Displays the comment time of the current comment.
 *
 * @since 0.71
 * @since 6.2.0 Added the `$comment_id` parameter.
 *
 * @param string         $format     Optional. PHP time format. Defaults to the 'time_format' option.
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to print the time.
 *                                   Default current comment.
 */
function comment_time( $format = '', $comment_id = 0 ) {
	echo get_comment_time( $format, false, true, $comment_id );
}

/**
 * Retrieves the comment type of the current comment.
 *
 * @since 1.5.0
 * @since 4.4.0 Added the ability for `$comment_id` to also accept a WP_Comment object.
 *
 * @param int|WP_Comment $comment_id Optional. WP_Comment or ID of the comment for which to get the type.
 *                                   Default current comment.
 * @return string The comment type.
 */
function get_comment_type( $comment_id = 0 ) {
	$comment = get_comment( $comment_id );

	if ( '' === $comment->comment_type ) {
		$comment->comment_type = 'comment';
	}

	/**
	 * Filters the returned comment type.
	 *
	 * @since 1.5.0
	 * @since 4.1.0 The `$comment_id` and `$comment` parameters were added.
	 *
	 * @param string     $comment_type The type of comment, such as 'comment', 'pingback', or 'trackback'.
	 * @param string     $comment_id   The comment ID as a numeric string.
	 * @param WP_Comment $comment      The comment object.
	 */
	return apply_filters( 'get_comment_type', $comment->comment_type, $comment->comment_ID, $comment );
}

/**
 * Displays the comment type of the current comment.
 *
 * @since 0.71
 *
 * @param string|false $commenttxt   Optional. String to display for comment type. Default false.
 * @param string|false $trackbacktxt Optional. String to display for trackback type. Default false.
 * @param string|false $pingbacktxt  Optional. String to display for pingback type. Default false.
 */
function comment_type( $commenttxt = false, $trackbacktxt = false, $pingbacktxt = false ) {
	if ( false === $commenttxt ) {
		$commenttxt = _x( 'Comment', 'noun' );
	}
	if ( false === $trackbacktxt ) {
		$trackbacktxt = __( 'Trackback' );
	}
	if ( false === $pingbacktxt ) {
		$pingbacktxt = __( 'Pingback' );
	}
	$type = get_comment_type();
	switch ( $type ) {
		case 'trackback':
			echo $trackbacktxt;
			break;
		case 'pingback':
			echo $pingbacktxt;
			break;
		default:
			echo $commenttxt;
	}
}

/**
 * Retrieves the current post's trackback URL.
 *
 * There is a check to see if permalink's have been enabled and if so, will
 * retrieve the pretty path. If permalinks weren't enabled, the ID of the
 * current post is used and appended to the correct page to go to.
 *
 * @since 1.5.0
 *
 * @return string The trackback URL after being filtered.
 */
function get_trackback_url() {
	if ( get_option( 'permalink_structure' ) ) {
		$trackback_url = trailingslashit( get_permalink() ) . user_trailingslashit( 'trackback', 'single_trackback' );
	} else {
		$trackback_url = get_option( 'siteurl' ) . '/wp-trackback.php?p=' . get_the_ID();
	}

	/**
	 * Filters the returned trackback URL.
	 *
	 * @since 2.2.0
	 *
	 * @param string $trackback_url The trackback URL.
	 */
	return apply_filters( 'trackback_url', $trackback_url );
}

/**
 * Displays the current post's trackback URL.
 *
 * @since 0.71
 *
 * @param bool $deprecated_echo Not used.
 * @return void|string Should only be used to echo the trackback URL, use get_trackback_url()
 *                     for the result instead.
 */
function trackback_url( $deprecated_echo = true ) {
	if ( true !== $deprecated_echo ) {
		_deprecated_argument(
			__FUNCTION__,
			'2.5.0',
			sprintf(
				/* translators: %s: get_trackback_url() */
				__( 'Use %s instead if you do not want the value echoed.' ),
				'<code>get_trackback_url()</code>'
			)
		);
	}

	if ( $deprecated_echo ) {
		echo get_trackback_url();
	} else {
		return get_trackback_url();
	}
}

/**
 * Generates and displays the RDF for the trackback information of current post.
 *
 * Deprecated in 3.0.0, and restored in 3.0.1.
 *
 * @since 0.71
 *
 * @param int|string $deprecated Not used (Was $timezone = 0).
 */
function trackback_rdf( $deprecated = '' ) {
	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '2.5.0' );
	}

	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && false !== stripos( $_SERVER['HTTP_USER_AGENT'], 'W3C_Validator' ) ) {
		return;
	}

	echo '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
			xmlns:dc="http://purl.org/dc/elements/1.1/"
			xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
		<rdf:Description rdf:about="';
	the_permalink();
	echo '"' . "\n";
	echo '    dc:identifier="';
	the_permalink();
	echo '"' . "\n";
	echo '    dc:title="' . str_replace( '--', '&#x2d;&#x2d;', wptexturize( strip_tags( get_the_title() ) ) ) . '"' . "\n";
	echo '    trackback:ping="' . get_trackback_url() . '"' . " />\n";
	echo '</rdf:RDF>';
}

/**
 * Determines whether the current post is open for comments.
 *
 * For more information on this and similar theme functions, check out
 * the {@link https://developer.wordpress.org/themes/basics/conditional-tags/
 * Conditional Tags} article in the Theme Developer Handbook.
 *
 * @since 1.5.0
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @return bool True if the comments are open.
 */
function comments_open( $post = null ) {
	$_post = get_post( $post );

	$post_id       = $_post ? $_post->ID : 0;
	$comments_open = ( $_post && ( 'open' === $_post->comment_status ) );

	/**
	 * Filters whether the current post is open for comments.
	 *
	 * @since 2.5.0
	 *
	 * @param bool $comments_open Whether the current post is open for comments.
	 * @param int  $post_id       The post ID.
	 */
	return apply_filters( 'comments_open', $comments_open, $post_id );
}

/**
 * Determines whether the current post is open for pings.
 *
 * For more information on this and similar theme functions, check out
 * the {@link https://developer.wordpress.org/themes/basics/conditional-tags/
 * Conditional Tags} article in the Theme Developer Handbook.
 *
 * @since 1.5.0
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @return bool True if pings are accepted
 */
function pings_open( $post = null ) {
	$_post = get_post( $post );

	$post_id    = $_post ? $_post->ID : 0;
	$pings_open = ( $_post && ( 'open' === $_post->ping_status ) );

	/**
	 * Filters whether the current post is open for pings.
	 *
	 * @since 2.5.0
	 *
	 * @param bool $pings_open Whether the current post is open for pings.
	 * @param int  $post_id    The post ID.
	 */
	return apply_filters( 'pings_open', $pings_open, $post_id );
}

/**
 * Displays form token for unfiltered comments.
 *
 * Will only display nonce token if the current user has permissions for
 * unfiltered html. Won't display the token for other users.
 *
 * The function was backported to 2.0.10 and was added to versions 2.1.3 and
 * above. Does not exist in versions prior to 2.0.10 in the 2.0 branch and in
 * the 2.1 branch, prior to 2.1.3. Technically added in 2.2.0.
 *
 * Backported to 2.0.10.
 *
 * @since 2.1.3
 */
function wp_comment_form_unfiltered_html_nonce() {
	$post    = get_post();
	$post_id = $post ? $post->ID : 0;

	if ( current_user_can( 'unfiltered_html' ) ) {
		wp_nonce_field( 'unfiltered-html-comment_' . $post_id, '_wp_unfiltered_html_comment_disabled', false );
		wp_print_inline_script_tag( "(function(){if(window===window.parent){document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';}})();" );
	}
}

/**
 * Loads the comment template specified in $file.
 *
 * Will not display the comments template if not on single post or page, or if
 * the post does not have comments.
 *
 * Uses the WordPress database object to query for the comments. The comments
 * are passed through the {@see 'comments_array'} filter hook with the list of comments
 * and the post ID respectively.
 *
 * The `$file` path is passed through a filter hook called {@see 'comments_template'},
 * which includes the template directory and $file combined. Tries the $filtered path
 * first and if it fails it will require the default comment template from the
 * default theme. If either does not exist, then the WordPress process will be
 * halted. It is advised for that reason, that the default theme is not deleted.
 *
 * Will not try to get the comments if the post has none.
 *
 * @since 1.5.0
 *
 * @global WP_Query   $wp_query           WordPress Query object.
 * @global WP_Post    $post               Global post object.
 * @global wpdb       $wpdb               WordPress database abstraction object.
 * @global int        $id
 * @global WP_Comment $comment            Global comment object.
 * @global string     $user_login
 * @global string     $user_identity
 * @global bool       $overridden_cpage
 * @global bool       $withcomments
 * @global string     $wp_stylesheet_path Path to current theme's stylesheet directory.
 * @global string     $wp_template_path   Path to current theme's template directory.
 *
 * @param string $file              Optional. The file to load. Default '/comments.php'.
 * @param bool   $separate_comments Optional. Whether to separate the comments by comment type.
 *                                  Default false.
 */
function comments_template( $file = '/comments.php', $separate_comments = false ) {
	global $wp_query, $withcomments, $post, $wpdb, $id, $comment, $user_login, $user_identity, $overridden_cpage, $wp_stylesheet_path, $wp_template_path;

	if ( ! ( is_single() || is_page() || $withcomments ) || empty( $post ) ) {
		return;
	}

	if ( empty( $file ) ) {
		$file = '/comments.php';
	}

	$req = get_option( 'require_name_email' );

	/*
	 * Comment author information fetched from the comment cookies.
	 */
	$commenter = wp_get_current_commenter();

	/*
	 * The name of the current comment author escaped for use in attributes.
	 * Escaped by sanitize_comment_cookies().
	 */
	$comment_author = $commenter['comment_author'];

	/*
	 * The email address of the current comment author escaped for use in attributes.
	 * Escaped by sanitize_comment_cookies().
	 */
	$comment_author_email = $commenter['comment_author_email'];

	/*
	 * The URL of the current comment author escaped for use in attributes.
	 */
	$comment_author_url = esc_url( $commenter['comment_author_url'] );

	$comment_args = array(
		'orderby'       => 'comment_date_gmt',
		'order'         => 'ASC',
		'status'        => 'approve',
		'post_id'       => $post->ID,
		'no_found_rows' => false,
	);

	if ( get_option( 'thread_comments' ) ) {
		$comment_args['hierarchical'] = 'threaded';
	} else {
		$comment_args['hierarchical'] = false;
	}

	if ( is_user_logged_in() ) {
		$comment_args['include_unapproved'] = array( get_current_user_id() );
	} else {
		$unapproved_email = wp_get_unapproved_comment_author_email();

		if ( $unapproved_email ) {
			$comment_args['include_unapproved'] = array( $unapproved_email );
		}
	}

	$per_page = 0;
	if ( get_option( 'page_comments' ) ) {
		$per_page = (int) get_query_var( 'comments_per_page' );
		if ( 0 === $per_page ) {
			$per_page = (int) get_option( 'comments_per_page' );
		}

		$comment_args['number'] = $per_page;
		$page                   = (int) get_query_var( 'cpage' );

		if ( $page ) {
			$comment_args['offset'] = ( $page - 1 ) * $per_page;
		} elseif ( 'oldest' === get_option( 'default_comments_page' ) ) {
			$comment_args['offset'] = 0;
		} else {
			// If fetching the first page of 'newest', we need a top-level comment count.
			$top_level_query = new WP_Comment_Query();
			$top_level_args  = array(
				'count'   => true,
				'orderby' => false,
				'post_id' => $post->ID,
				'status'  => 'approve',
			);

			if ( $comment_args['hierarchical'] ) {
				$top_level_args['parent'] = 0;
			}

			if ( isset( $comment_args['include_unapproved'] ) ) {
				$top_level_args['include_unapproved'] = $comment_args['include_unapproved'];
			}

			/**
			 * Filters the arguments used in the top level comments query.
			 *
			 * @since 5.6.0
			 *
			 * @see WP_Comment_Query::__construct()
			 *
			 * @param array $top_level_args {
			 *     The top level query arguments for the comments template.
			 *
			 *     @type bool         $count   Whether to return a comment count.
			 *     @type string|array $orderby The field(s) to order by.
			 *     @type int          $post_id The post ID.
			 *     @type string|array $status  The comment status to limit results by.
			 * }
			 */
			$top_level_args = apply_filters( 'comments_template_top_level_query_args', $top_level_args );

			$top_level_count = $top_level_query->query( $top_level_args );

			$comment_args['offset'] = ( (int) ceil( $top_level_count / $per_page ) - 1 ) * $per_page;
		}
	}

	/**
	 * Filters the arguments used to query comments in comments_template().
	 *
	 * @since 4.5.0
	 *
	 * @see WP_Comment_Query::__construct()
	 *
	 * @param array $comment_args {
	 *     Array of WP_Comment_Query arguments.
	 *
	 *     @type string|array $orderby                   Field(s) to order by.
	 *     @type string       $order                     Order of results. Accepts 'ASC' or 'DESC'.
	 *     @type string       $status                    Comment status.
	 *     @type array        $include_unapproved        Array of IDs or email addresses whose unapproved comments
	 *                                                   will be included in results.
	 *     @type int          $post_id                   ID of the post.
	 *     @type bool         $no_found_rows             Whether to refrain from querying for found rows.
	 *     @type bool         $update_comment_meta_cache Whether to prime cache for comment meta.
	 *     @type bool|string  $hierarchical              Whether to query for comments hierarchically.
	 *     @type int          $offset                    Comment offset.
	 *     @type int          $number                    Number of comments to fetch.
	 * }
	 */
	$comment_args = apply_filters( 'comments_template_query_args', $comment_args );

	$comment_query = new WP_Comment_Query( $comment_args );
	$_comments     = $comment_query->comments;

	// Trees must be flattened before they're passed to the walker.
	if ( $comment_args['hierarchical'] ) {
		$comments_flat = array();
		foreach ( $_comments as $_comment ) {
			$comments_flat[]  = $_comment;
			$comment_children = $_comment->get_children(
				array(
					'format'  => 'flat',
					'status'  => $comment_args['status'],
					'orderby' => $comment_args['orderby'],
				)
			);

			foreach ( $comment_children as $comment_child ) {
				$comments_flat[] = $comment_child;
			}
		}
	} else {
		$comments_flat = $_comments;
	}

	/**
	 * Filters the comments array.
	 *
	 * @since 2.1.0
	 *
	 * @param array $comments Array of comments supplied to the comments template.
	 * @param int   $post_id  Post ID.
	 */
	$wp_query->comments = apply_filters( 'comments_array', $comments_flat, $post->ID );

	$comments                        = &$wp_query->comments;
	$wp_query->comment_count         = count( $wp_query->comments );
	$wp_query->max_num_comment_pages = $comment_query->max_num_pages;

	if ( $separate_comments ) {
		$wp_query->comments_by_type = separate_comments( $comments );
		$comments_by_type           = &$wp_query->comments_by_type;
	} else {
		$wp_query->comments_by_type = array();
	}

	$overridden_cpage = false;

	if ( '' == get_query_var( 'cpage' ) && $wp_query->max_num_comment_pages > 1 ) {
		set_query_var( 'cpage', 'newest' === get_option( 'default_comments_page' ) ? get_comment_pages_count() : 1 );
		$overridden_cpage = true;
	}

	if ( ! defined( 'COMMENTS_TEMPLATE' ) ) {
		define( 'COMMENTS_TEMPLATE', true );
	}

	$theme_template = trailingslashit( $wp_stylesheet_path ) . $file;

	/**
	 * Filters the path to the theme template file used for the comments template.
	 *
	 * @since 1.5.1
	 *
	 * @param string $theme_template The path to the theme template file.
	 */
	$include = apply_filters( 'comments_template', $theme_template );

	if ( file_exists( $include ) ) {
		require $include;
	} elseif ( file_exists( trailingslashit( $wp_template_path ) . $file ) ) {
		require trailingslashit( $wp_template_path ) . $file;
	} else { // Backward compat code will be removed in a future release.
		require ABSPATH . WPINC . '/theme-compat/comments.php';
	}
}

/**
 * Displays the link to the comments for the current post ID.
 *
 * @since 0.71
 *
 * @param false|string $zero      Optional. String to display when no comments. Default false.
 * @param false|string $one       Optional. String to display when only one comment is available. Default false.
 * @param false|string $more      Optional. String to display when there are more than one comment. Default false.
 * @param string       $css_class Optional. CSS class to use for comments. Default empty.
 * @param false|string $none      Optional. String to display when comments have been turned off. Default false.
 */
function comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
	$post_id         = get_the_ID();
	$post_title      = get_the_title();
	$comments_number = get_comments_number( $post_id );

	if ( false === $zero ) {
		/* translators: %s: Post title. */
		$zero = sprintf( __( 'No Comments<span class="screen-reader-text"> on %s</span>' ), $post_title );
	}

	if ( false === $one ) {
		/* translators: %s: Post title. */
		$one = sprintf( __( '1 Comment<span class="screen-reader-text"> on %s</span>' ), $post_title );
	}

	if ( false === $more ) {
		/* translators: 1: Number of comments, 2: Post title. */
		$more = _n(
			'%1$s Comment<span class="screen-reader-text"> on %2$s</span>',
			'%1$s Comments<span class="screen-reader-text"> on %2$s</span>',
			$comments_number
		);
		$more = sprintf( $more, number_format_i18n( $comments_number ), $post_title );
	}

	if ( false === $none ) {
		/* translators: %s: Post title. */
		$none = sprintf( __( 'Comments Off<span class="screen-reader-text"> on %s</span>' ), $post_title );
	}

	if ( 0 == $comments_number && ! comments_open() && ! pings_open() ) {
		printf(
			'<span%1$s>%2$s</span>',
			! empty( $css_class ) ? ' class="' . esc_attr( $css_class ) . '"' : '',
			$none
		);
		return;
	}

	if ( post_password_required() ) {
		_e( 'Enter your password to view comments.' );
		return;
	}

	if ( 0 == $comments_number ) {
		$respond_link = get_permalink() . '#respond';
		/**
		 * Filters the respond link when a post has no comments.
		 *
		 * @since 4.4.0
		 *
		 * @param string $respond_link The default response link.
		 * @param int    $post_id      The post ID.
		 */
		$comments_link = apply_filters( 'respond_link', $respond_link, $post_id );
	} else {
		$comments_link = get_comments_link();
	}

	$link_attributes = '';

	/**
	 * Filters the comments link attributes for display.
	 *
	 * @since 2.5.0
	 *
	 * @param string $link_attributes The comments link attributes. Default empty.
	 */
	$link_attributes = apply_filters( 'comments_popup_link_attributes', $link_attributes );

	printf(
		'<a href="%1$s"%2$s%3$s>%4$s</a>',
		esc_url( $comments_link ),
		! empty( $css_class ) ? ' class="' . $css_class . '" ' : '',
		$link_attributes,
		get_comments_number_text( $zero, $one, $more )
	);
}

/**
 * Retrieves HTML content for reply to comment link.
 *
 * @since 2.7.0
 * @since 4.4.0 Added the ability for `$comment` to also accept a WP_Comment object.
 *
 * @param array          $args {
 *     Optional. Override default arguments.
 *
 *     @type string $add_below  The first part of the selector used to identify the comment to respond below.
 *                              The resulting value is passed as the first parameter to addComment.moveForm(),
 *                              concatenated as $add_below-$comment->comment_ID. Default 'comment'.
 *     @type string $respond_id The selector identifying the responding comment. Passed as the third parameter
 *                              to addComment.moveForm(), and appended to the link URL as a hash value.
 *                              Default 'respond'.
 *     @type string $reply_text The text of the Reply link. Default 'Reply'.
 *     @type string $login_text The text of the link to reply if logged out. Default 'Log in to Reply'.
 *     @type int    $max_depth  The max depth of the comment tree. Default 0.
 *     @type int    $depth      The depth of the new comment. Must be greater than 0 and less than the value
 *                              of the 'thread_comments_depth' option set in Settings > Discussion. Default 0.
 *     @type string $before     The text or HTML to add before the reply link. Default empty.
 *     @type string $after      The text or HTML to add after the reply link. Default empty.
 * }
 * @param int|WP_Comment $comment Optional. Comment being replied to. Default current comment.
 * @param int|WP_Post    $post    Optional. Post ID or WP_Post object the comment is going to be displayed on.
 *                                Default current post.
 * @return string|false|null Link to show comment form, if successful. False, if comments are closed.
 */
function get_comment_reply_link( $args = array(), $comment = null, $post = null ) {
	$defaults = array(
		'add_below'     => 'comment',
		'respond_id'    => 'respond',
		'reply_text'    => __( 'Reply' ),
		/* translators: Comment reply button text. %s: Comment author name. */
		'reply_to_text' => __( 'Reply to %s' ),
		'login_text'    => __( 'Log in to Reply' ),
		'max_depth'     => 0,
		'depth'         => 0,
		'before'        => '',
		'after'         => '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] ) {
		return;
	}

	$comment = get_comment( $comment );

	if ( empty( $comment ) ) {
		return;
	}

	if ( empty( $post ) ) {
		$post = $comment->comment_post_ID;
	}

	$post = get_post( $post );

	if ( ! comments_open( $post->ID ) ) {
		return false;
	}

	if ( get_option( 'page_comments' ) ) {
		$permalink = str_replace( '#comment-' . $comment->comment_ID, '', get_comment_link( $comment ) );
	} else {
		$permalink = get_permalink( $post->ID );
	}

	/**
	 * Filters the comment reply link arguments.
	 *
	 * @since 4.1.0
	 *
	 * @param array      $args    Comment reply link arguments. See get_comment_reply_link()
	 *                            for more information on accepted arguments.
	 * @param WP_Comment $comment The object of the comment being replied to.
	 * @param WP_Post    $post    The WP_Post object.
	 */
	$args = apply_filters( 'comment_reply_link_args', $args, $comment, $post );

	if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) {
		$link = sprintf(
			'<a rel="nofollow" class="comment-reply-login" href="%s">%s</a>',
			esc_url( wp_login_url( get_permalink() ) ),
			$args['login_text']
		);
	} else {
		$data_attributes = array(
			'commentid'      => $comment->comment_ID,
			'postid'         => $post->ID,
			'belowelement'   => $args['add_below'] . '-' . $comment->comment_ID,
			'respondelement' => $args['respond_id'],
			'replyto'        => sprintf( $args['reply_to_text'], get_comment_author( $comment ) ),
		);

		$data_attribute_string = '';

		foreach ( $data_attributes as $name => $value ) {
			$data_attribute_string .= " data-{$name}=\"" . esc_attr( $value ) . '"';
		}

		$data_attribute_string = trim( $data_attribute_string );

		$link = sprintf(
			"<a rel='nofollow' class='comment-reply-link' href='%s' %s aria-label='%s'>%s</a>",
			esc_url(
				add_query_arg(
					array(
						'replytocom'      => $comment->comment_ID,
						'unapproved'      => false,
						'moderation-hash' => false,
					),
					$permalink
				)
			) . '#' . $args['respond_id'],
			$data_attribute_string,
			esc_attr( sprintf( $args['reply_to_text'], get_comment_author( $comment ) ) ),
			$args['reply_text']
		);
	}

	$comment_reply_link = $args['before'] . $link . $args['after'];

	/**
	 * Filters the comment reply link.
	 *
	 * @since 2.7.0
	 *
	 * @param string     $comment_reply_link The HTML markup for the comment reply link.
	 * @param array      $args               An array of arguments overriding the defaults.
	 * @param WP_Comment $comment            The object of the comment being replied.
	 * @param WP_Post    $post               The WP_Post object.
	 */
	return apply_filters( 'comment_reply_link', $comment_reply_link, $args, $comment, $post );
}

function comment_reply_link( $args = array(), $comment = null, $post = null ) {

}

function get_post_reply_link( $args = array(), $post = null ) {
  return;
}


function post_reply_link( $args = array(), $post = null ) {

}

function get_cancel_comment_reply_link( $link_text = '', $post = null ) {
	return;
}

function cancel_comment_reply_link( $link_text = '' ) {

}

function get_comment_id_fields( $post = null ) {
  return '';
}

function comment_id_fields( $post = null ) {

}

function comment_form_title( $no_reply_text = false, $reply_text = false, $link_to_parent = true, $post = null ) {
  return;
}

function _get_comment_reply_id( $post = null ) {
  return 0;
}

function wp_list_comments( $args = array(), $comments = null ) {
  return;
}


function comment_form( $args = array(), $post = null ) {
  return;
}
