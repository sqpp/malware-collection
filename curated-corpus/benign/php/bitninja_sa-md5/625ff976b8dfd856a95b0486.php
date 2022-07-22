<?php
/**
 * All 404 Redirect  to Homepage Uninstall
 *
 * Uninstalling All 404 Redirect  to Homepage .
 *
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
p404_Uninstall_clear_htaccess();
function p404_Uninstall_clear_htaccess()
{
    $htaccess_file = wp_upload_dir(null, true, true).'/.htaccess';

    p404_Uninstall_insert_with_markers_htaccess($htaccess_file, 'All_404_marker_comment_image', "");
}
function p404_Uninstall_insert_with_markers_htaccess( $filename, $marker, $insertion ) {
    if (!file_exists( $filename ) || is_writeable( $filename ) ) {
        if (!file_exists( $filename ) ) {
            $markerdata = '';
        } else {
            $markerdata = explode( "\n", implode( '', file( $filename ) ) );
        }

        if ( !$f = @fopen( $filename, 'w' ) )
            return false;

        $foundit = false;
        if ( $markerdata ) {
            $state = true;
            foreach ( $markerdata as $n => $markerline ) {
                if (strpos($markerline, '# BEGIN ' . $marker) !== false)
                    $state = false;
                if ( $state ) {
                    if ( $n + 1 < count( $markerdata ) )
                        fwrite( $f, "{$markerline}\n" );
                    else
                        fwrite( $f, "{$markerline}" );
                }
                if (strpos($markerline, '# END ' . $marker) !== false) {
                    fwrite( $f, "# BEGIN {$marker}\n" );
                    if ( is_array( $insertion ))
                        foreach ( $insertion as $insertline )
                            fwrite( $f, "{$insertline}\n" );
                    fwrite( $f, "# END {$marker}\n" );
                    $state = true;
                    $foundit = true;
                }
            }
        }
        if (!$foundit) {
            fwrite( $f, "\n# BEGIN {$marker}\n" );
            if ( is_array( $insertion ))
                foreach ( $insertion as $insertline )
                    fwrite( $f, "{$insertline}\n" );
            fwrite( $f, "# END {$marker}\n" );
        }
        fclose( $f );
        return true;
    } else {
        return false;
    }
}

wp_cache_flush();

