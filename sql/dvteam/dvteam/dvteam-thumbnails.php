<?php
global $paged;

if ( get_query_var('paged') ) { 
    $paged = get_query_var('paged'); 
}
elseif ( get_query_var('page') ) { 
    $paged = get_query_var('page'); 
}
else { 
    $paged = 1; 
}
if (empty($categoryid)) {
    $dvteamthumbargs = array(
        'post_type' => 'dvourteam',
        'post_status' => 'publish',
        'posts_per_page' => $max,
        'paged' => $paged
    );
}
else {
    $dvteamtcatid_array = explode(',', $categoryid);
    $dvteamthumbargs = array(
        'post_type' => 'dvourteam',
        'post_status' => 'publish',
        'posts_per_page' => $max,
        'tax_query' => array(
            array(
                'taxonomy' => 'dvteamtaxonomy',
                'terms'    => $dvteamtcatid_array,
            ),
        ),
        'paged' => $paged
    );
}
$dvteamthumbs_query = new WP_Query( $dvteamthumbargs );
$random = rand();
$pagination = dvteam_get_option('dvteam_options_2','pagination', 'disable');
$align = dvteam_get_option('dvteam_options_2','thumbnailalign', 'left');
?>

<ul id="dvteam-thumbnails<?php echo esc_js($random); ?>" class="dvwookmark dvteam-thumbnails <?php if(!empty($rounded)) { echo esc_attr($rounded); } ?>" data-dvalign="<?php echo esc_attr($align); ?>" data-dvoffset="<?php echo esc_attr($offset); ?>" data-dvwidth="100" style="visibility:hidden;">
<?php 
while($dvteamthumbs_query->have_posts()) : $dvteamthumbs_query->the_post();
    if ( has_post_thumbnail() ) {
        $thumb_id = get_post_thumbnail_id();    
        $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail', true);
        $thumb_url = $thumb_url_array[0]; 
        $dvptlink = get_post_meta( get_the_id(), 'dvptlink', true ); 
    ?>
    <li id="dvteamid-<?php the_ID(); ?>">
        <a id="dvgridboxlink<?php echo esc_attr($random . get_the_ID()); ?>"<?php if ( has_post_format( 'link' )) { ?> href="<?php echo esc_url($dvptlink); ?>" target="_blank"<?php } else { ?> href="#dvteambox<?php echo esc_attr($random . get_the_ID()); ?>"<?php } ?> <?php if (($side == 'center') && (!has_post_format( 'link' ))) { ?> class="popup-with-zoom-anim" <?php } ?>>
            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" />
        </a>
    </li>
    <?php }
endwhile; ?>
</ul>
<div class="dv-clear"></div>

<?php if ($pagination == 'enable') { ?>
<div class="dvteam-blogpager thumb-pager">
    <div class="dvteam-previous">
        <?php next_posts_link( esc_attr__( '&#8249; Previous', 'dvteam' ), $dvteamthumbs_query->max_num_pages ); ?>
    </div>
    <div class="dvteam-next">
        <?php previous_posts_link( esc_attr__( 'Next &#8250;', 'dvteam' ), $dvteamthumbs_query->max_num_pages ); ?>
    </div>
</div>
<div class="dv-clear"></div>
<?php 
}
self::$dv_team_args[] = $dvteamthumbargs;
self::$dv_team_id[] = $random;
self::$dv_team_panel_side[] = $side;

wp_reset_postdata(); 
?>