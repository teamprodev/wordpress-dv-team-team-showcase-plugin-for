<?php
defined( 'ABSPATH' ) || exit;

$removeinfo = dvteam_get_option('dvteam_options_2','removeinfo', 'enable');
if (empty($categoryid)) {
    $dvteamcarargs = array(
        'post_type' => 'dvourteam',
        'post_status' => 'publish',
        'posts_per_page' => $max
    );
}
else {
    $dvteamcarcatid_array = explode(',', $categoryid);
    $dvteamcarargs = array(
        'post_type' => 'dvourteam',
        'post_status' => 'publish',
        'posts_per_page' => $max,
        'tax_query' => array(
            array(
                'taxonomy' => 'dvteamtaxonomy',
                'terms'    => $dvteamcarcatid_array,
            ),
        )
    );
}
$dvteamcar_query = new WP_Query( $dvteamcarargs );
$random = rand();

if ($autoplay == 'true') {
    $autoplay = 1;
} else {
    $autoplay = 0;
}
?>

<div id="dvteamcarousel<?php echo esc_attr($random); ?>" class="dv-carousel dvteamgrid owl-carousel <?php if(!empty($rounded)) { echo esc_attr($rounded); } ?>" data-dvspacing="<?php echo esc_attr($spacing); ?>" data-dvautoplay="<?php echo esc_attr($autoplay); ?>" data-dvcolumns="<?php echo esc_attr($columns); ?>" data-dvduration="<?php echo esc_attr($duration); ?>000">
<?php 
while($dvteamcar_query->have_posts()) : $dvteamcar_query->the_post();
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
    <div>
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
    </div>
    <?php 
    }
    endwhile; ?>
</div>
<div class="dv-clear"></div>
<?php
self::$dv_team_args[] = $dvteamcarargs;
self::$dv_team_id[] = $random;
self::$dv_team_panel_side[] = $side;

wp_reset_postdata(); 
?>