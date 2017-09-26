<?php

use WP_CLI\Utils;

/**
 * Import new attachments or regenerate existing ones.
 *
 * ## EXAMPLES
 *
 *     # Re-generate all thumbnails, without confirmation.
 *     $ wp media regenerate --yes
 *     Found 3 images to regenerate.
 *     1/3 Regenerated thumbnails for "Sydney Harbor Bridge" (ID 760).
 *     2/3 Regenerated thumbnails for "Boardwalk" (ID 757).
 *     3/3 Regenerated thumbnails for "Sunburst Over River" (ID 756).
 *     Success: Regenerated 3 of 3 images.
 *
 *     # Import a local image and set it to be the featured image for a post.
 *     $ wp media import ~/Downloads/image.png --post_id=123 --title="A downloaded picture" --featured_image
 *     Success: Imported file '/home/person/Downloads/image.png' as attachment ID 1753 and attached to post 123 as featured image.
 *
 * @package wp-cli
 */
class Media_Command extends WP_CLI_Command {

	/**
	 * Regenerate thumbnails for one or more attachments.
	 *
	 * ## OPTIONS
	 *
	 * [<attachment-id>...]
	 * : One or more IDs of the attachments to regenerate.
	 *
	 * [--image_size=<image_size>]
	 * : Name of the image size to regenerate. Only thumbnails of this image size will be regenerated, thumbnails of other image sizes will not.
	 *
	 * [--skip-delete]
	 * : Skip deletion of the original thumbnails. If your thumbnails are linked from sources outside your control, it's likely best to leave them around. Defaults to false.
	 *
	 * [--only-missing]
	 * : Only generate thumbnails for images missing image sizes.
	 *
	 * [--yes]
	 * : Answer yes to the confirmation message. Confirmation only shows when no IDs passed as arguments.
	 *
	 * ## EXAMPLES
	 *
	 *     # Regenerate thumbnails for given attachment IDs.
	 *     $ wp media regenerate 123 124 125
	 *     Found 3 images to regenerate.
	 *     1/3 Regenerated thumbnails for "Vertical Image" (ID 123).
	 *     2/3 Regenerated thumbnails for "Horizontal Image" (ID 124).
	 *     3/3 Regenerated thumbnails for "Beautiful Picture" (ID 125).
	 *     Success: Regenerated 3 of 3 images.
	 *
	 *     # Regenerate all thumbnails, without confirmation.
	 *     $ wp media regenerate --yes
	 *     Found 3 images to regenerate.
	 *     1/3 Regenerated thumbnails for "Sydney Harbor Bridge" (ID 760).
	 *     2/3 Regenerated thumbnails for "Boardwalk" (ID 757).
	 *     3/3 Regenerated thumbnails for "Sunburst Over River" (ID 756).
	 *     Success: Regenerated 3 of 3 images.
	 *
	 *     # Re-generate all thumbnails that have IDs between 1000 and 2000.
	 *     $ seq 1000 2000 | xargs wp media regenerate
	 *     Found 4 images to regenerate.
	 *     1/4 Regenerated thumbnails for "Vertical Featured Image" (ID 1027).
	 *     2/4 Regenerated thumbnails for "Horizontal Featured Image" (ID 1022).
	 *     3/4 Regenerated thumbnails for "Unicorn Wallpaper" (ID 1045).
	 *     4/4 Regenerated thumbnails for "I Am Worth Loving Wallpaper" (ID 1023).
	 *     Success: Regenerated 4 of 4 images.
	 *
	 *     # Re-generate only the thumbnails of "large" image size for all images.
	 *     $ wp media regenerate --image_size=large
	 *     Do you really want to regenerate the "large" image size for all images? [y/n] y
	 *     Found 3 images to regenerate.
	 *     1/3 Regenerated "large" thumbnail for "Yoogest Image Ever, Really" (ID 9999).
	 *     2/3 No "large" thumbnail regeneration needed for "Snowflake" (ID 9998).
	 *     3/3 Regenerated "large" thumbnail for "Even Yooger than the Yoogest Image Ever, Really" (ID 9997).
	 *     Success: Regenerated 3 of 3 images.
	 */
	function regenerate( $args, $assoc_args = array() ) {
		$assoc_args = wp_parse_args( $assoc_args, array(
			'image_size' => '',
		) );

		$image_size = $assoc_args['image_size'];
		if ( $image_size && ! in_array( $image_size, get_intermediate_image_sizes(), true ) ) {
			WP_CLI::error( sprintf( 'Unknown image size "%s".', $image_size ) );
		}

		if ( empty( $args ) ) {
			if ( $image_size ) {
				WP_CLI::confirm( sprintf( 'Do you really want to regenerate the "%s" image size for all images?', $image_size ), $assoc_args );
			} else {
				WP_CLI::confirm( 'Do you really want to regenerate all images?', $assoc_args );
			}
		}

		$skip_delete = \WP_CLI\Utils\get_flag_value( $assoc_args, 'skip-delete' );
		$only_missing = \WP_CLI\Utils\get_flag_value( $assoc_args, 'only-missing' );
		if ( $only_missing ) {
			$skip_delete = true;
		}

		$mime_types = array( 'image' );
		if ( Utils\wp_version_compare( '4.7', '>=' ) ) {
			$mime_types[] = 'application/pdf';
		}
		$query_args = array(
			'post_type' => 'attachment',
			'post__in' => $args,
			'post_mime_type' => $mime_types,
			'post_status' => 'any',
			'posts_per_page' => -1,
			'fields' => 'ids'
		);

		$images = new WP_Query( $query_args );

		$count = $images->post_count;

		if ( !$count ) {
			WP_CLI::warning( 'No images found.' );
			return;
		}

		WP_CLI::log( sprintf( 'Found %1$d %2$s to regenerate.', $count,
			_n( 'image', 'images', $count ) ) );

		if ( $image_size ) {
			$image_size_filters = $this->add_image_size_filters( $image_size );
		}

		$errored = false;
		$successes = $errors = 0;
		foreach ( $images->posts as $number => $id ) {
			if ( $this->process_regeneration( $id, $skip_delete, $only_missing, $image_size, ( $number + 1 ) . '/' . $count ) ) {
				$successes++;
			} else {
				$errors++;
			}
		}

		if ( $image_size ) {
			$this->remove_image_size_filters( $image_size_filters );
		}

		Utils\report_batch_operation_results( 'image', 'regenerate', count( $images->posts ), $successes, $errors );
	}

	/**
	 * Create attachments from local files or URLs.
	 *
	 * ## OPTIONS
	 *
	 * <file>...
	 * : Path to file or files to be imported. Supports the glob(3) capabilities of the current shell.
	 *     If file is recognized as a URL (for example, with a scheme of http or ftp), the file will be
	 *     downloaded to a temp file before being sideloaded.
	 *
	 * [--post_id=<post_id>]
	 * : ID of the post to attach the imported files to.
	 *
	 * [--title=<title>]
	 * : Attachment title (post title field).
	 *
	 * [--caption=<caption>]
	 * : Caption for attachent (post excerpt field).
	 *
	 * [--alt=<alt_text>]
	 * : Alt text for image (saved as post meta).
	 *
	 * [--desc=<description>]
	 * : "Description" field (post content) of attachment post.
	 *
	 * [--skip-copy]
	 * : If set, media files (local only) are imported to the library but not moved on disk.
	 *
	 * [--featured_image]
	 * : If set, set the imported image as the Featured Image of the post its attached to.
	 *
	 * [--porcelain]
	 * : Output just the new attachment ID.
	 *
	 * ## EXAMPLES
	 *
	 *     # Import all jpgs in the current user's "Pictures" directory, not attached to any post.
	 *     $ wp media import ~/Pictures/**\/*.jpg
	 *     Imported file '/home/person/Pictures/beautiful-youg-girl-in-ivy.jpg' as attachment ID 1751.
	 *     Imported file '/home/person/Pictures/fashion-girl.jpg' as attachment ID 1752.
	 *     Success: Imported 2 of 2 items.
	 *
	 *     # Import a local image and set it to be the post thumbnail for a post.
	 *     $ wp media import ~/Downloads/image.png --post_id=123 --title="A downloaded picture" --featured_image
	 *     Imported file '/home/person/Downloads/image.png' as attachment ID 1753 and attached to post 123 as featured image.
	 *     Success: Imported 1 of 1 images.
	 *
	 *     # Import a local image, but set it as the featured image for all posts.
	 *     # 1. Import the image and get its attachment ID.
	 *     # 2. Assign the attachment ID as the featured image for all posts.
	 *     $ ATTACHMENT_ID="$(wp media import ~/Downloads/image.png --porcelain)"
	 *     $ wp post list --post_type=post --format=ids | xargs -d ' ' -I % wp post meta add % _thumbnail_id $ATTACHMENT_ID
	 *     Success: Added custom field.
	 *     Success: Added custom field.
	 *
	 *     # Import an image from the web.
	 *     $ wp media import http://s.wordpress.org/style/images/wp-header-logo.png --title='The WordPress logo' --alt="Semantic personal publishing"
	 *     Imported file 'http://s.wordpress.org/style/images/wp-header-logo.png' as attachment ID 1755.
	 *     Success: Imported 1 of 1 images.
	 */
	function import( $args, $assoc_args = array() ) {
		$assoc_args = wp_parse_args( $assoc_args, array(
			'title' => '',
			'caption' => '',
			'alt' => '',
			'desc' => '',
		) );

		// Assume the most generic term
		$noun = 'item';

		// Use the noun `image` when sure the media file is an image
		if ( Utils\get_flag_value( $assoc_args, 'featured_image' ) || $assoc_args['alt'] ) {
			$noun = 'image';
		}

		if ( isset( $assoc_args['post_id'] ) ) {
			if ( !get_post( $assoc_args['post_id'] ) ) {
				WP_CLI::warning( "Invalid --post_id" );
				$assoc_args['post_id'] = false;
			}
		} else {
			$assoc_args['post_id'] = false;
		}

		$successes = $errors = 0;
		foreach ( $args as $file ) {
			$is_file_remote = parse_url( $file, PHP_URL_HOST );
			$orig_filename = $file;

			if ( empty( $is_file_remote ) ) {
				if ( !file_exists( $file ) ) {
					WP_CLI::warning( "Unable to import file '$file'. Reason: File doesn't exist." );
					$errors++;
					continue;
				}
				if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'skip-copy' ) ) {
					$tempfile = $file;
				} else {
					$tempfile = $this->make_copy( $file );
				}
			} else {
				$tempfile = download_url( $file );
				if ( is_wp_error( $tempfile ) ) {
					WP_CLI::warning( sprintf(
						"Unable to import file '%s'. Reason: %s",
						$file, implode( ', ', $tempfile->get_error_messages() )
					) );
					$errors++;
					continue;
				}
			}

			$file_array = array(
				'tmp_name' => $tempfile,
				'name' => Utils\basename( $file )
			);

			$post_array= array(
				'post_title' => $assoc_args['title'],
				'post_excerpt' => $assoc_args['caption'],
				'post_content' => $assoc_args['desc']
			);
			$post_array = wp_slash( $post_array );

			// use image exif/iptc data for title and caption defaults if possible
			if ( empty( $post_array['post_title'] ) || empty( $post_array['post_excerpt'] ) ) {
				// @codingStandardsIgnoreStart
				$image_meta = @wp_read_image_metadata( $tempfile );
				// @codingStandardsIgnoreEnd
				if ( ! empty( $image_meta ) ) {
					if ( empty( $post_array['post_title'] ) && trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
						$post_array['post_title'] = $image_meta['title'];
					}

					if ( empty( $post_array['post_excerpt'] ) && trim( $image_meta['caption'] ) ) {
						$post_array['post_excerpt'] = $image_meta['caption'];
					}
				}
			}

			if ( empty( $post_array['post_title'] ) ) {
				$post_array['post_title'] = preg_replace( '/\.[^.]+$/', '', Utils\basename( $file ) );
			}

			if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'skip-copy' ) ) {
				$wp_filetype = wp_check_filetype( $file, null );
				$post_array['post_mime_type'] = $wp_filetype['type'];
				$post_array['post_status'] = 'inherit';

				$success = wp_insert_attachment( $post_array, $file, $assoc_args['post_id'] );
				if ( is_wp_error( $success ) ) {
					WP_CLI::warning( sprintf(
						"Unable to insert file '%s'. Reason: %s",
						$orig_filename, implode( ', ', $success->get_error_messages() )
					) );
					$errors++;
					continue;
				}
				wp_update_attachment_metadata( $success, wp_generate_attachment_metadata( $success, $file ) );
			} else {
				// Deletes the temporary file.
				$success = media_handle_sideload( $file_array, $assoc_args['post_id'], $assoc_args['title'], $post_array );
				if ( is_wp_error( $success ) ) {
					WP_CLI::warning( sprintf(
						"Unable to import file '%s'. Reason: %s",
						$orig_filename, implode( ', ', $success->get_error_messages() )
					) );
					$errors++;
					continue;
				}
			}

			// Set alt text
			if ( $assoc_args['alt'] ) {
				update_post_meta( $success, '_wp_attachment_image_alt', wp_slash( $assoc_args['alt'] ) );
			}

			// Set as featured image, if --post_id and --featured_image are set
			if ( $assoc_args['post_id'] && \WP_CLI\Utils\get_flag_value( $assoc_args, 'featured_image' ) ) {
				update_post_meta( $assoc_args['post_id'], '_thumbnail_id', $success );
			}

			$attachment_success_text = '';
			if ( $assoc_args['post_id'] ) {
				$attachment_success_text = " and attached to post {$assoc_args['post_id']}";
				if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'featured_image' ) )
					$attachment_success_text .= ' as featured image';
			}

			if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'porcelain' ) ) {
				WP_CLI::line( $success );
			} else {
				WP_CLI::log( sprintf(
					"Imported file '%s' as attachment ID %d%s.",
					$orig_filename, $success, $attachment_success_text
				) );
			}
			$successes++;
		}

		// Report the result of the operation
		if ( ! Utils\get_flag_value( $assoc_args, 'porcelain' ) ) {
			Utils\report_batch_operation_results( $noun, 'import', count( $args ), $successes, $errors );
		} elseif ( $errors ) {
			WP_CLI::halt( 1 );
		}
	}

	// wp_tempnam() inexplicably forces a .tmp extension, which spoils MIME type detection
	private function make_copy( $path ) {
		$dir = get_temp_dir();
		$filename = Utils\basename( $path );
		if ( empty( $filename ) )
			$filename = time();

		$filename = $dir . wp_unique_filename( $dir, $filename );
		if ( !copy( $path, $filename ) )
			WP_CLI::error( "Could not create temporary file for $path." );

		return $filename;
	}

	private function process_regeneration( $id, $skip_delete, $only_missing, $image_size, $progress ) {
		$fullsizepath = get_attached_file( $id );

		$att_desc = sprintf( '"%1$s" (ID %2$d)', get_the_title( $id ), $id );
		$thumbnail_desc = $image_size ? sprintf( '"%s" thumbnail', $image_size ) : 'thumbnail';

		if ( false === $fullsizepath || !file_exists( $fullsizepath ) ) {
			WP_CLI::warning( "Can't find $att_desc." );
			return false;
		}

		if ( ! $skip_delete ) {
			$this->remove_old_images( $id, $fullsizepath, $image_size );
		}

		$is_pdf = 'application/pdf' === get_post_mime_type( $id );

		if ( ! $only_missing || $this->needs_regeneration( $id, $fullsizepath, $is_pdf, $image_size ) ) {

			$metadata = wp_generate_attachment_metadata( $id, $fullsizepath );
			if ( is_wp_error( $metadata ) ) {
				WP_CLI::warning( $metadata->get_error_message() );
				return false;
			}

			// Note it's possible for no metadata to generated for PDFs if restricted to a specific image size.
			if ( empty( $metadata ) && ! ( $is_pdf && $image_size ) ) {
				WP_CLI::warning( "$progress Couldn't regenerate thumbnails for $att_desc." );
				return false;
			}

			if ( $image_size ) {
				if ( $this->update_attachment_metadata_for_image_size( $id, $metadata, $image_size ) ) {
					WP_CLI::log( "$progress Regenerated $thumbnail_desc for $att_desc." );
				} else {
					WP_CLI::log( "$progress No $thumbnail_desc regeneration needed for $att_desc." );
				}
			} else {
				wp_update_attachment_metadata( $id, $metadata );

				WP_CLI::log( "$progress Regenerated thumbnails for $att_desc." );
			}
			return true;
		} else {
			WP_CLI::log( "$progress No $thumbnail_desc regeneration needed for $att_desc." );
			return true;
		}
	}

	private function remove_old_images( $att_id, $fullsizepath, $image_size ) {
		$metadata = wp_get_attachment_metadata( $att_id );

		if ( empty( $metadata['sizes'] ) ) {
			return;
		}

		if ( $image_size ) {
			if ( empty( $metadata['sizes'][ $image_size ] ) ) {
				return;
			}
			$metadata['sizes'] = array( $image_size => $metadata['sizes'][ $image_size ] );
		}

		$dir_path = dirname( $fullsizepath ) . '/';

		foreach ( $metadata['sizes'] as $size_info ) {
			$intermediate_path = $dir_path . $size_info['file'];

			if ( $intermediate_path === $fullsizepath )
				continue;

			if ( file_exists( $intermediate_path ) )
				unlink( $intermediate_path );
		}
	}

	private function needs_regeneration( $att_id, $fullsizepath, $is_pdf, $image_size ) {

		$metadata = wp_get_attachment_metadata($att_id);

		// Note that an attachment can have no sizes if it's on or below the thumbnail threshold.

		// Check whether there's new sizes or they've changed.
		$image_sizes = $this->get_intermediate_image_sizes_for_attachment( $fullsizepath, $is_pdf, $metadata );

		if ( $image_size ) {
			if ( empty( $image_sizes[ $image_size ] ) ) {
				return false;
			}
			if ( empty( $metadata['sizes'][ $image_size ] ) ) {
				return true;
			}
			$metadata['sizes'] = array( $image_size => $metadata['sizes'][ $image_size ] );
		}

		if ( $this->image_sizes_differ( $image_sizes, $metadata['sizes'] ) ) {
			return true;
		}

		$dir_path = dirname( $fullsizepath ) . '/';

		// Check that the thumbnail files exist.
		foreach( $metadata['sizes'] as $size_info ) {
			$intermediate_path = $dir_path . $size_info['file'];

			if ( $intermediate_path === $fullsizepath )
				continue;

			if ( ! file_exists( $intermediate_path ) ) {
				return true;
			}
		}
		return false;
	}

	// Whether there's new image sizes or the width/height of existing image sizes have changed.
	private function image_sizes_differ( $image_sizes, $meta_sizes ) {
		// Check if have new image size(s).
		if ( array_diff( array_keys( $image_sizes ), array_keys( $meta_sizes ) ) ) {
			return true;
		}
		// Check if image sizes have changed.
		foreach ( $image_sizes as $name => $image_size ) {
			if ( $image_size['width'] !== $meta_sizes[ $name ]['width'] || $image_size['height'] !== $meta_sizes[ $name ]['height'] ) {
				return true;
			}
		}
		return false;
	}

	// Like WP's get_intermediate_image_sizes(), but removes sizes that won't be generated for a particular attachment due to its being on or below their thresholds,
	// and returns associative array with size name => width/height entries, resolved to crop values if applicable.
	private function get_intermediate_image_sizes_for_attachment( $fullsizepath, $is_pdf, $metadata ) {

		// Need to get width, height of attachment for image_resize_dimensions().
		$editor = wp_get_image_editor( $fullsizepath );
		if ( is_wp_error( $editor ) ) {
			WP_CLI::warning( $editor->get_error_message() );
			return array();
		}
		if ( is_wp_error( $result = $editor->load() ) ) {
			WP_CLI::warning( $result->get_error_message() );
			unset( $editor );
			return array();
		}
		list( $width, $height ) = array_values( $editor->get_size() );
		unset( $editor );

		$sizes = array();
		foreach ( $this->get_intermediate_sizes( $is_pdf, $metadata ) as $name => $size ) {
			// Need to check destination and original width or height differ before calling image_resize_dimensions(), otherwise it will return non-false.
			if ( ( $width !== $size['width'] || $height !== $size['height'] ) && ( $dims = image_resize_dimensions( $width, $height, $size['width'], $size['height'], $size['crop'] ) ) ) {
				list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;
				$sizes[ $name ] = array( 'width' => $dst_w, 'height' => $dst_h );
			}
		}
		return $sizes;
	}

	// Like WP's get_intermediate_image_sizes(), but returns associative array with name => size info entries (and caters for PDFs also).
	private function get_intermediate_sizes( $is_pdf, $metadata ) {
		if ( $is_pdf ) {
			// Copied from wp_generate_attachment_metadata() in "wp-admin/includes/image.php".
			$fallback_sizes = array(
				'thumbnail',
				'medium',
				'large',
			);
			$intermediate_image_sizes = apply_filters( 'fallback_intermediate_image_sizes', $fallback_sizes, $metadata );
		} else {
			$intermediate_image_sizes = get_intermediate_image_sizes();
		}

		// Adapted from wp_generate_attachment_metadata() in "wp-admin/includes/image.php".

		if ( function_exists( 'wp_get_additional_image_sizes' ) ) {
			$_wp_additional_image_sizes = wp_get_additional_image_sizes();
		} else {
			// For WP < 4.7.0.
			global $_wp_additional_image_sizes;
			if ( ! $_wp_additional_image_sizes ) {
				$_wp_additional_image_sizes = array();
			}
		}


		$sizes = array();
		foreach ( $intermediate_image_sizes as $s ) {
			if ( isset( $_wp_additional_image_sizes[ $s ]['width'] ) ) {
				$sizes[ $s ]['width'] = (int) $_wp_additional_image_sizes[ $s ]['width'];
			} else {
				$sizes[ $s ]['width'] = (int) get_option( "{$s}_size_w" );
			}

			if ( isset( $_wp_additional_image_sizes[ $s ]['height'] ) ) {
				$sizes[ $s ]['height'] = (int) $_wp_additional_image_sizes[ $s ]['height'];
			} else {
				$sizes[ $s ]['height'] = (int) get_option( "{$s}_size_h" );
			}

			if ( isset( $_wp_additional_image_sizes[ $s ]['crop'] ) ) {
				$sizes[ $s ]['crop'] = (bool) $_wp_additional_image_sizes[ $s ]['crop'];
			} else {
				// Force PDF thumbnails to be soft crops.
				if ( $is_pdf && 'thumbnail' === $s ) {
					$sizes[ $s ]['crop'] = false;
				} else {
					$sizes[ $s ]['crop'] = (bool) get_option( "{$s}_crop" );
				}
			}
		}

		if ( ! $is_pdf ) {
			$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes, $metadata );
		}

		return $sizes;
	}

	// Add filters to only process a particular intermediate image size in wp_generate_attachment_metadata().
	private function add_image_size_filters( $image_size ) {
		$image_size_filters = array();

		// For images.
		$image_size_filters['intermediate_image_sizes_advanced'] = function ( $sizes ) use ( $image_size ) {
			// $sizes is associative array of name => size info entries.
			if ( isset( $sizes[ $image_size ] ) ) {
				return array( $image_size => $sizes[ $image_size ] );
			}
			return array();
		};

		// For PDF previews.
		$image_size_filters['fallback_intermediate_image_sizes'] = function ( $fallback_sizes ) use ( $image_size ) {
			// $fallback_sizes is indexed array of size names.
			if ( in_array( $image_size, $fallback_sizes, true ) ) {
				return array( $image_size );
			}
			return array();
		};

		foreach ( $image_size_filters as $name => $filter ) {
			add_filter( $name, $filter, PHP_INT_MAX );
		}

		return $image_size_filters;
	}

	// Remove above intermediate image size filters.
	private function remove_image_size_filters( $image_size_filters ) {
		foreach ( $image_size_filters as $name => $filter ) {
			remove_filter( $name, $filter, PHP_INT_MAX );
		}
	}

	// Update attachment sizes metadata just for a particular intermediate image size.
	private function update_attachment_metadata_for_image_size( $id, $new_metadata, $image_size ) {
		$metadata = wp_get_attachment_metadata( $id );

		// If have metadata for image_size.
		if ( ! empty( $new_metadata['sizes'][ $image_size ] ) ) {
			$metadata['sizes'][ $image_size ] = $new_metadata['sizes'][ $image_size ];
			wp_update_attachment_metadata( $id, $metadata );
			return true;
		}

		// Else remove unused metadata if any.
		if ( ! empty( $metadata['sizes'][ $image_size ] ) ) {
			unset( $metadata['sizes'][ $image_size ] );
			wp_update_attachment_metadata( $id, $metadata );
			// Treat removing unused metadata as no change.
		}
		return false;
	}
}
