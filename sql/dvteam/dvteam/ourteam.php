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
    $dvteamargs = array(
        'post_type' => 'dvourteam',
        'post_status' => 'publish',
        'posts_per_page' => $max,
        'paged' => $paged
    );
}
else {
    $dvteamcatid_array = explode(',', $categoryid);
    $dvteamargs = array(
        'post_type' => 'dvourteam',
        'post_status' => 'publish',
        'posts_per_page' => $max,
        'tax_query' => array(
            array(
                'taxonomy' => 'dvteamtaxonomy',
                'terms'    => $dvteamcatid_array,
            ),
        ),
        'paged' => $paged
    );
}
$dvteam_query = new WP_Query( $dvteamargs );
$random = rand();
$pagination = dvteam_get_option('dvteam_options_2','pagination', 'disable');
$align = dvteam_get_option('dvteam_options_2','thumbnailalign', 'left');
$removeinfo = dvteam_get_option('dvteam_options_2','removeinfo', 'enable');
?>
<ul id="dvteamgrid<?php echo esc_attr($random); ?>" class="dvwookmark dvteamgrid <?php if(!empty($rounded)) { echo esc_attr($rounded); } ?>" data-dvalign="<?php echo esc_attr($align); ?>" data-dvoffset="<?php echo esc_attr($offset); ?>" data-dvwidth="<?php echo esc_attr($itemwidth); ?>" style="visibility:hidden;">
<?php 
while($dvteam_query->have_posts()) : $dvteam_query->the_post();
    if ( has_post_thumbnail() ) {
    $thumb_id = get_post_thumbnail_id();
        if ($gridstyle == 'rectangle') {                                       
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'dv-team-rectangle', true);
        }
        elseif ($gridstyle == 'square') {                                       
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'dv-team-square', true);
        }
        else {
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'large', true);
        }
    $thumb_url = $thumb_url_array[0];  
    $dvexcerpt = get_post_meta( get_the_id(), 'dvexcerpt', true );
    $dvptlink = get_post_meta( get_the_id(), 'dvptlink', true );
    ?>
    <li id="dvteamid-<?php the_ID(); ?>">
        <figure>
            <a id="dvgridboxlink<?php echo esc_attr($random . get_the_ID()); ?>"<?php if ( has_post_format( 'link' )) { ?> href="<?php echo esc_url($dvptlink); ?>" target="_blank"<?php } else { ?> href="#dvteambox<?php echo esc_attr($random . get_the_ID()); ?>"<?php } ?> <?php if (($side == 'center') && (!has_post_format( 'link' ))) { ?> class="popup-with-zoom-anim" <?php } ?>>
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" />
                <?php if ($removeinfo == 'enable') { ?>
                <div class="dv-member-zoom"><i class="dvteamicon-info"></i></div>
                <?php } ?>
            </a>
            <figcaption>
                <div class="dv-member-desc">
                    <div><span class="dv-member-name"><?php the_title(); ?></span></div>
                    <?php if (!empty($dvexcerpt)) { ?>
                    <div><span class="dv-member-info"><?php echo esc_html($dvexcerpt); ?></span></div>
                    <?php } ?>
                </div>
            </figcaption>
        </figure>
    </li>
    <?php } ?>
    <?php endwhile; ?>
</ul>
<div class="dv-clear"></div>

<?php if ($pagination == 'enable') { ?>
<div class="dvteam-blogpager">
    <div class="dvteam-previous">
        <?php next_posts_link( esc_attr__( '&#8249; Previous', 'dvteam' ), $dvteam_query->max_num_pages ); ?>
    </div>
    <div class="dvteam-next">
        <?php previous_posts_link( esc_attr__( 'Next &#8250;', 'dvteam' ), $dvteam_query->max_num_pages ); ?>
    </div>
</div>
<div class="dv-clear"></div>
<?php } ?>

<?php
self::$dv_team_args[] = $dvteamargs;
self::$dv_team_id[] = $random;
self::$dv_team_panel_side[] = $side;
wp_reset_postdata(); 
?>