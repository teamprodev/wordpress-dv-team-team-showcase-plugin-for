<?php
if (empty($exclude)) {
$dvteamargs = array(
    'post_type' => 'dvourteam',
    'post_status' => 'publish',
    'posts_per_page' => $max
);
}
else {
    $dvteamexcludeid_array = explode(',', $exclude);
    $dvteamargs = array(
    'post_type' => 'dvourteam',
    'post_status' => 'publish',
    'tax_query' =>  array (
        array(
            'taxonomy' => 'dvteamtaxonomy',
            'terms' => $dvteamexcludeid_array,
            'field' => 'term_id',
            'operator' => 'NOT IN',
        ),
    ),
    'posts_per_page' => $max
);
}
$dvteamfilter_query = new WP_Query( $dvteamargs );
$random = rand();
$align = dvteam_get_option('dvteam_options_2','thumbnailalign', 'left');
$removeinfo = dvteam_get_option('dvteam_options_2','removeinfo', 'enable');

if (empty($exclude)) {
    $dvteamtaxarray = array();
}
else {
    $dvteamexcludetax_array = explode(',', $exclude);
    $dvteamtaxarray = array('exclude' => $dvteamexcludetax_array);
}
?>
<div class="dvteam-filterable">
<?php $terms = get_terms( 'dvteamtaxonomy', $dvteamtaxarray); ?>
<?php if ($terms && !is_wp_error($terms)) { ?>
<ul id="dvfilters<?php echo esc_attr($random); ?>" class="dvfilters">
    <li data-filter="gridall" class="gridactive"><?php esc_attr_e( 'All', 'dvteam') ?></li>
    <?php 
    foreach( $terms as $term ) { 
    $termname = $term->name;
    $termid = $term->term_id;
    ?>
    <li data-filter="<?php echo esc_attr('dvfilter' . $termid); ?>"><?php echo esc_attr($termname); ?></li>
    <?php } ?>
</ul>
<div class="dvfilters-clear"></div>
<?php } ?>

<ul id="dvteamgrid<?php echo esc_attr($random); ?>" class="dvwookmark-filterable dvteamgrid <?php if(!empty($rounded)) { echo esc_attr($rounded); } ?>" data-dvalign="<?php echo esc_attr($align); ?>" data-dvoffset="<?php echo esc_attr($offset); ?>" data-dvwidth="<?php echo esc_attr($itemwidth); ?>" style="visibility:hidden;">
<?php 
while($dvteamfilter_query->have_posts()) : $dvteamfilter_query->the_post();
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
        $gridterms = get_the_terms( get_the_id(), 'dvteamtaxonomy' );
        if ($gridterms && !is_wp_error($gridterms)) {
            $filter_links = array();
            foreach ( $gridterms as $gridterm ) {
                $filter_links[] = '"dvfilter' . esc_attr($gridterm->term_id) . '"';
            }					
            $filters = join( ", ", $filter_links );
    ?>
    <li id="dvteamid-<?php the_ID(); ?>" data-filter-class='["gridall",<?php echo esc_attr($filters); ?>]'>
    <?php } else { ?>
    <li id="dvteamid-<?php the_ID(); ?>">    
    <?php } ?>
        <figure>
            <a id="dvgridboxlink<?php echo esc_attr($random . get_the_ID()); ?>"<?php if ( has_post_format( 'link' )) { ?> href="<?php echo esc_url($dvptlink); ?>" target="_blank"<?php } else { ?> href="#dvteambox<?php echo esc_attr($random . get_the_ID()); ?>"<?php } ?> <?php if (($side == 'center') && (!has_post_format( 'link' ))) { ?> class="popup-with-zoom-anim" <?php } ?>>
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" />
                <?php if ($removeinfo != 'true') { ?>
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
    <?php }
endwhile; ?>
</ul>
</div>
<div class="dv-clear"></div>
<?php
self::$dv_team_args[] = $dvteamargs;
self::$dv_team_id[] = $random;
self::$dv_team_panel_side[] = $side;
wp_reset_postdata(); 
?>