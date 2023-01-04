<?php
defined( 'ABSPATH' ) || exit;

class DVteamShortcodes {
    /* The single instance of the class */
	protected static $_instance = null;

    /* Static vars */
    static $add_scripts;
    static $dv_team_id;
    static $dv_team_args;
    static $dv_team_panel_side;

    /* Main Instance */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /* Constructor */
    public function __construct() {
        add_shortcode('dvteam', array($this, 'dvteam'));
        add_shortcode('dvteamfilter', array($this, 'dvteamfilter'));
        add_shortcode('dvteamcarousel', array($this, 'dvteamcarousel'));
        add_shortcode('dvthumbnails', array($this, 'dvthumbnails'));
        add_shortcode('dvmember', array($this, 'dvmember'));
        add_shortcode('dvskills', array($this, 'dvskills'));
        add_shortcode('dvskill', array($this, 'dvskill'));
        add_shortcode('dvcv', array($this, 'dvcv'));

        add_filter("the_content", array($this, 'content_filter'));
        add_filter("widget_text", array($this, 'content_filter'), 9);

        /* We used "wp_footer" because we need to check if shortcode is used on the page before enqueue the scripts. */
        add_action( 'wp_footer', array($this, 'panel') );
        add_action( 'wp_footer', array($this, 'shortcode_enqueue_scripts') );
    }

    /* Content Filter */
    public function content_filter($content) {
        // array of custom shortcodes requiring the fix 
        $block = join("|",array("dvteam","dvteamfilter","dvteamcarousel","dvmember","dvthumbnails","dvskills","dvskill","dvcv"));
        // opening tag
        $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);       
        // closing tag
        $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
        return $rep;
    }

    /* dvteam Shortcode */
    public function dvteam($atts) {
        self::$add_scripts = true;
        // Load the scripts
        extract(shortcode_atts(array(
            "max" => 'max',
            "categoryid" => 'categoryid',
            "gridstyle" => 'gridstyle',
            "offset" => 'offset',
            "itemwidth" => 'itemwidth',
            "side" => 'side',
            "rounded" => 'rounded'
        ), $atts));
        ob_start();
        include('ourteam.php');
        $content = ob_get_clean();
        return $content;
    }

    /* dvteamfilter Shortcode */
    public function dvteamfilter($atts) {
        self::$add_scripts = true;
		extract(shortcode_atts(array(
            "max" => 'max',
            "gridstyle" => 'gridstyle',
            "offset" => 'offset',
            "itemwidth" => 'itemwidth',
            "side" => 'side',
            "exclude" => 'exclude',
            "rounded" => 'rounded'
        ), $atts));
        ob_start();
        include('ourteam-filter.php');
        $content = ob_get_clean();
        return $content;
    }

    /* dvteamcarousel Shortcode */
    public function dvteamcarousel($atts) {
        self::$add_scripts = true;
		extract(shortcode_atts(array(
            "max" => 'max',
            "categoryid" => 'categoryid',
            "columns" => 'columns',
            "gridstyle" => 'gridstyle',
            "autoplay" => 'autoplay',
            "duration" => 'duration',
            "spacing" => 'spacing',
            "side" => 'side',
            "rounded" => 'rounded'
	   ), $atts));
        ob_start();
        include('dvteam-carousel.php');
        $content = ob_get_clean();
        return $content;
    }

    /* dvthumbnails Shortcode */
    public function dvthumbnails($atts) {
        self::$add_scripts = true;
		extract(shortcode_atts(array(
            "max" => 'max',
            "categoryid" => 'categoryid',
            "offset" => 'offset',
            "side" => 'side',
            "rounded" => 'rounded'
        ), $atts));
        ob_start();
        include('dvteam-thumbnails.php');
        $content = ob_get_clean();
        return $content;
    }

    /* dvmember Shortcode */
    public function dvmember($atts) {
        self::$add_scripts = true;
		extract(shortcode_atts(array(
            "id" => 'id',
            "gridstyle" => 'gridstyle',
            "offset" => 'offset',
            "itemwidth" => 'itemwidth',
            "side" => 'side',
            "rounded" => 'rounded'
        ), $atts));
        ob_start();
        include('member.php');
        $content = ob_get_clean();
        return $content;
    }

    // DV Skills Shortcode
    public function dvskills($atts, $content = null) {
        return '<div class="dvskills">' . do_shortcode(stripslashes($content)) . '</div>';
    }

    // DV Skill Shortcode
    public function dvskill($atts, $content = null) {
        extract(shortcode_atts(array(
            "title" => 'title',
            "percent" => 'percent'
        ), $atts));
        return '<div class="dvskillbar" data-percent="' . $percent . '%"><div class="dvskillbar-title"><span>' . $title . '</span></div><div class="dvskillbar-bar"></div><div class="dvskill-bar-percent">' . $percent . '%</div></div>';
    }

    // DV CV Shortcode
    public function dvcv($atts, $content = null) {
        extract(shortcode_atts(array(
            "title" => 'title',
            "subtitle" => 'subtitle'
        ), $atts));
        return '<div class="dvcv-title">' . $title . '</div><div class="dvcv-subtitle">' . $subtitle . '</div><div class="dvcv-content">' . do_shortcode( $content ) . '</div>';
    }

    // Panel
    public function panel() {
        
    if(self::$dv_team_args) { 
    
        for($i=0, $count = count(self::$dv_team_args);$i<$count;$i++) {  
            
        $dvteam_get_panel_query = self::$dv_team_args[$i];
        $dvteam_panel_query = new WP_Query( $dvteam_get_panel_query );
        $side = self::$dv_team_panel_side[$i];
        $random = self::$dv_team_id[$i];  ?>
        <div id="dv-overlay"></div>
        <?php
        while($dvteam_panel_query->have_posts()) : $dvteam_panel_query->the_post();

        $dvexcerpt = get_post_meta( get_the_id(), 'dvexcerpt', true );
        $dvptfimage = get_post_meta( get_the_id(), 'dvptfimage', true );
        $dvptoembed = esc_url(get_post_meta( get_the_id(), 'dvptoembed', true ));
        $dvptteamimages = get_post_meta( get_the_id(), 'dvptteamimages', true );
        $dvptquote = get_post_meta( get_the_id(), 'dvptquote', true );
        $dvactivateicons = get_post_meta( get_the_id(), 'dvactivateicons', true );
        $dvrepeatdvicons = get_post_meta( get_the_id(), 'dvrepeatdvicons', true );
        $container_classes = '';

        if ($side != 'center') {
            $container_classes = 'dv-panel';
        } else {
            $container_classes = 'teamlist-popup zoom-anim-dialog mfp-hide';
        }

        if($dvactivateicons == "on") {
            $container_classes .= ' dv-with-socialbar';
        }

        if (( has_post_thumbnail() ) && ( !has_post_format( 'link' ))) {    
        ?>
        <div id="dvteambox<?php echo esc_attr($random . get_the_ID()); ?>" class="<?php echo esc_attr($container_classes); ?>" data-trigger="dvgridboxlink<?php echo esc_attr($random . get_the_ID()); ?>" data-side="<?php if (!empty($side)) { echo esc_attr($side); } else { echo 'right'; } ?>">
        <?php if($dvactivateicons == "on") { ?>
            <div class="dv-panel-left">
                <ul class="dvteam-icons">
                <?php
                foreach ( (array) $dvrepeatdvicons as $key => $entry ) {
                $dviconimg = $dviconurl = '';  
                if ( isset( $entry['dviconimg'] ) ) {
                    $dviconimg = $entry['dviconimg'];
                }                     
                if ( isset( $entry['dviconurl'] ) ) {
                    $dviconurl = $entry['dviconurl'];
                } ?>      
                <li class="<?php echo esc_attr($dviconimg); ?>">
                    <a href="<?php echo esc_url($dviconurl); ?>" target="_blank">
                        <i class="dvteamicon-<?php echo esc_attr($dviconimg); ?>"></i>       
                    </a>
                </li>
            <?php } ?>
            </ul>
        </div>
        <div class="dv-panel-right">
        <?php } ?>    
            <div class="dv-panel-title">
                <div><?php the_title(); ?></div>
                <?php if ($side != 'center') { ?>
                <div class="close-dv-panel-bt"><i class="dvteamicon-cancel"></i></div>
                <?php } ?>
            </div>
            <?php 
            if (!empty($dvexcerpt)) {
                echo '<div class="dv-panel-info">' . esc_html($dvexcerpt) . '</div>';
            }
            if ( has_post_format( 'image' )) {
                echo '<div class="dv-panel-image"><img src="' . esc_url($dvptfimage) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
            }
            if ( has_post_format( 'video' )) {
                echo '<div class="dv-panel-video">' . wp_oembed_get($dvptoembed) . '</div>';
            } ?>  
            <?php if ( has_post_format( 'gallery' )) { ?>
            <div class="dvteam-slider" data-slidizle data-slidizle-loop="true">
                <ul class="slider-content" data-slidizle-content>
                    <?php 
                    foreach ($dvptteamimages as $image => $link) {
                    $dvfullimage = wp_get_attachment_image_src( $image, 'full' ); 
                    ?>
                    <li class="slider-item" style="background-image:url('<?php echo esc_js($dvfullimage['0']); ?>')"></li>
                    <?php } ?>
                </ul>      
                <div class="slider-next" data-slidizle-next></div>
                <div class="slider-previous" data-slidizle-previous></div>
                <ul class="slider-navigation" data-slidizle-navigation></ul>
            </div> 
            <?php } ?>
            <div class="dv-panel-inner">
                <?php if ( has_post_format( 'quote' )) { ?>
                <div class="dvteam-blockquote"><p><?php echo esc_html($dvptquote); ?></p></div>
                <hr/>
                <?php } ?>
                <?php the_content(); ?>
            </div>
            <?php 
            if($dvactivateicons == "on") {
                echo '</div><div class="dv-clear"></div>'; 
            } ?>
        </div>
        <?php }
            endwhile;
            wp_reset_postdata();
            }
        }
    }

    /* Enqueue Scripts */
    public function shortcode_enqueue_scripts() {
        if ( ! self::$add_scripts ) {
			return;
        }
        // Get Custom Style Settings
        $custom_css = dvteam_get_option('dvteam_options','custom_css', '');
        $boldtitles = dvteam_get_option('dvteam_options','boldtitles', 'disable');
        $cararrowbg = dvteam_get_option('dvteam_options','cararrowbg', '#313131');
        $overlayopacity = dvteam_get_option('dvteam_options_1','overlayopacity', '0.7');
        $overlaycolor = dvteam_get_option('dvteam_options_1','overlay_color', '#212121');
        $panelwidth = dvteam_get_option('dvteam_options_1','panelwidth', 640);
        $ptitlesize = dvteam_get_option('dvteam_options_1','ptitlesize', 28);
        $ptitlecolor = dvteam_get_option('dvteam_options_1','ptitlecolor', '#ffffff');
        $ptitlebgcolor = dvteam_get_option('dvteam_options_1','ptitlebgcolor', '#eb5656');
        $psubtitlesize = dvteam_get_option('dvteam_options_1','psubtitlesize', 18);
        $psubtitlecolor = dvteam_get_option('dvteam_options_1','psubtitlecolor', '#ffffff');
        $psubtitlebgcolor = dvteam_get_option('dvteam_options_1','psubtitlebgcolor', '#414141');
        $phtitlescolor = dvteam_get_option('dvteam_options','phtitlescolor', '#ffffff');
        $ptextcolor = dvteam_get_option('dvteam_options','ptextcolor', '#c7c7c7');
        $pdividercolor = dvteam_get_option('dvteam_options','pdividercolor', '#414141');
        $pdividersize = dvteam_get_option('dvteam_options','pdividersize', 1);
        $spaceinner = dvteam_get_option('dvteam_options_1','spaceinner', 30);
        $panelbgcolor = dvteam_get_option('dvteam_options_1','panelbgcolor', '#313131');
        $socialbgcolor = dvteam_get_option('dvteam_options_1','socialbgcolor', '#212121');
        $thumbnailopacity = dvteam_get_option('dvteam_options_2','thumbnailopacity', '0.1');
        $thumbnailoverlay = dvteam_get_option('dvteam_options_2','thumbnailoverlay', '#212121');
        $infobgcolor = dvteam_get_option('dvteam_options_2','infobgcolor', '#eb5656');
        $imgtitlesize = dvteam_get_option('dvteam_options_2','imgtitlesize', 18);
        $imgtitlecolor = dvteam_get_option('dvteam_options_2','imgtitlecolor', '#ffffff');
        $imgtitlebgcolor = dvteam_get_option('dvteam_options_2','imgtitlebgcolor', '#eb5656');
        $imgsubtitlesize = dvteam_get_option('dvteam_options_2','imgsubtitlesize', 14);
        $imgsubtitlecolor = dvteam_get_option('dvteam_options_2','imgsubtitlecolor', '#ffffff');
        $imgsubtitlebgcolor = dvteam_get_option('dvteam_options_2','imgsubtitlebgcolor', '#313131');
        $removezoom = dvteam_get_option('dvteam_options_2','removezoom', 'enable');
        $removetextanim = dvteam_get_option('dvteam_options_2','removetextanim', 'enable');
        $skillsize = dvteam_get_option('dvteam_options_3','skillsize', 14);
        $percentsize = dvteam_get_option('dvteam_options_3','percentsize', 14);
        $skillcolor = dvteam_get_option('dvteam_options_3','skillcolor', '#ffffff');
        $percentcolor = dvteam_get_option('dvteam_options_3','percentcolor', '#ffffff');
        $bordersize = dvteam_get_option('dvteam_options_3','bordersize', 1);
        $bordercolor = dvteam_get_option('dvteam_options_3','bordercolor', '#414141');
        $skillbg = dvteam_get_option('dvteam_options_3','skillbg', '#212121');
        $skillbarbg = dvteam_get_option('dvteam_options_3','skillbarbg', '#212121');
        $scrollbarcolor = dvteam_get_option('dvteam_options_1','scrollbarcolor', '#ffffff');
        $titlemargin = dvteam_get_option('dvteam_options','titlemargin', 20);
        $h1size = dvteam_get_option('dvteam_options','h1', 34);
        $h2size = dvteam_get_option('dvteam_options','h2', 28);
        $h3size = dvteam_get_option('dvteam_options','h3', 24);
        $h4size = dvteam_get_option('dvteam_options','h4', 20);
        $h5size = dvteam_get_option('dvteam_options','h5', 18);
        $h6size = dvteam_get_option('dvteam_options','h6', 16);
        $textsize = dvteam_get_option('dvteam_options','p', 14);
        $quotesize = dvteam_get_option('dvteam_options','quote', 28);
        $quotemarks = dvteam_get_option('dvteam_options','quotemarks', 6);
        $quotescolor = dvteam_get_option('dvteam_options','quotescolor', '#414141');
        $cfcolorone = dvteam_get_option('dvteam_options_1','cfcolorone', '#414141');
        $cfcolortwo = dvteam_get_option('dvteam_options_1','cfcolortwo', '#eb5656');
        $cfcolorthree = dvteam_get_option('dvteam_options_1','cfcolorthree', '#ffffff');
        $activatescroll = dvteam_get_option('dvteam_options_1','activatescroll', 'enable');
        $filterfont = dvteam_get_option('dvteam_options_2','filterfont', 18);
        $filterbottom = dvteam_get_option('dvteam_options_2','filterbottom', 20);
        $filterhorizontal = dvteam_get_option('dvteam_options_2','filterhorizontal', 15);
        $filtervertical = dvteam_get_option('dvteam_options_2','filtervertical', 5);
        $filtercolor = dvteam_get_option('dvteam_options_2','filtercolor', '#414141');
        $filterhovercolor = dvteam_get_option('dvteam_options_2','filterhovercolor', '#ffffff');
        $filteractivecolor = dvteam_get_option('dvteam_options_2','filteractivecolor', '#ffffff');
        $filterbgcolor = dvteam_get_option('dvteam_options_2','filterbgcolor', '#f5f1f0');
        $filterbghovercolor = dvteam_get_option('dvteam_options_2','filterbghovercolor', '#414141');
        $filterbgactivecolor = dvteam_get_option('dvteam_options_2','filterbgactivecolor', '#eb5656');
        $paginationfont = dvteam_get_option('dvteam_options_2','paginationfont', '#ffffff');
        $paginationbg = dvteam_get_option('dvteam_options_2','paginationbg', '#212121');
        $paginationbghover = dvteam_get_option('dvteam_options_2','paginationbghover', '#eb5656');
        $rtl = 0;
        if (is_rtl()) {
            $rtl = 1;
        }

        // Styles
        wp_enqueue_style('dvteam_fix', plugin_dir_url( __FILE__ ) . 'css/fix.css', true, '2.0');
        wp_enqueue_style('dvteam_styles', plugin_dir_url( __FILE__ ) . 'css/style.css', true, '2.0');
        wp_enqueue_style('dvteam_scrollbar_styles', plugin_dir_url( __FILE__ ) . 'css/scrollbar.css', true, '2.0');
        wp_enqueue_style('dv_owl_style', plugin_dir_url( __FILE__ ) . 'css/owl.css', true, '2.0');
        wp_enqueue_style('dv_popup_style', plugin_dir_url( __FILE__ ) . 'css/popup.css', true, '2.0');
        if (is_rtl()) {
            wp_enqueue_style('dvteam_styles_rtl', plugin_dir_url( __FILE__ ) . 'css/rtl.css', true, '2.0');
        }

        // Custom Styles
        $inline_style = '';

        if ($activatescroll == 'disable') {
            $inline_style .= 'html,body {margin:0px !important;}';
        }

        if ($boldtitles == 'enable') {
            $inline_style .= '.dv-panel-title,.dv-panel-info,.dvteamgrid .dv-member-name,.dvcv-title,.dvcv-subtitle,.dv-panel h1,.dv-panel h2,.dv-panel h3,.dv-panel h4,.dv-panel h5,.dv-panel h6,.teamlist-popup h1,.teamlist-popup h2,.teamlist-popup h3,.teamlist-popup h4,.teamlist-popup h5,.teamlist-popup h6 {font-weight: 700 !important;}';
        }

        $inline_style .= '.dvfilters li {font-size:' . $filterfont . 'px}';
        $inline_style .= '.dvfilters li {padding-top:' . $filtervertical . 'px;padding-bottom:' . $filtervertical . 'px}';
        $inline_style .= '.dvfilters li {padding-left:' . $filterhorizontal . 'px;padding-right:' . $filterhorizontal . 'px}';
        $inline_style .= '.dvfilters-clear {height:' . $filterbottom . 'px}';
        $inline_style .= '.dvfilters li {color:' . $filtercolor . '}';
        $inline_style .= '.dvfilters li {background-color:' . $filterbgcolor . '}';
        $inline_style .= '.dvfilters li:hover {color:' . $filterhovercolor . '}';
        $inline_style .= '.dvfilters li:hover {background:' . $filterbghovercolor . '}';
        $inline_style .= '.dvfilters li.gridactive {background:' . $filterbgactivecolor . '}';
        $inline_style .= '.dvfilters li.gridactive {color:' . $filteractivecolor . '}';
        $inline_style .= '.dv-panel h1,.dv-panel h2,.dv-panel h3,.dv-panel h4,.dv-panel h5,.dv-panel h6,.teamlist-popup h1,.teamlist-popup h2,.teamlist-popup h3,.teamlist-popup h4,.teamlist-popup h5,.teamlist-popup h6 {margin-bottom:' . $titlemargin . 'px}';
        $inline_style .= '.dv-panel h1,.teamlist-popup h1 {font-size:' . $h1size . 'px}';
        $inline_style .= '.dv-panel h2,.teamlist-popup h2 {font-size:' . $h2size . 'px}';
        $inline_style .= '.dv-panel h3,.dvcv-title,.teamlist-popup h3 {font-size:' . $h3size . 'px}';
        $inline_style .= '.dv-panel h4,.teamlist-popup h4 {font-size:' . $h4size . 'px}';
        $inline_style .= '.dv-panel h5,.teamlist-popup h5 {font-size:' . $h5size . 'px}';
        $inline_style .= '.dv-panel h6,.dvcv-subtitle,.teamlist-popup h6 {font-size:' . $h6size . 'px}';
        $inline_style .= '.dvteam-blockquote p {font-size:' . $quotesize . 'px !important}';
        $inline_style .= '.dvteam-blockquote:before,.dvteam-blockquote:after {font-size:' . $quotemarks . 'em}';
        $inline_style .= '.dvteam-blockquote:before,.dvteam-blockquote:after {color:' . $quotescolor . '}';
        $inline_style .= '#dv-overlay {background-color:' . $overlaycolor . '}';
        $inline_style .= '#dv-overlay {opacity:' . $overlayopacity . '}';
        $inline_style .= '.dv-panel,.teamlist-popup {width:' . $panelwidth . 'px}';
        $inline_style .= '.dv-panel,.teamlist-popup {background-color:' . $panelbgcolor . '}';
        $inline_style .= '.dv-with-socialbar {box-shadow: inset 50px 0 0 0 ' . $socialbgcolor . '}';
        $inline_style .= '.dv-panel,.dv-panel p, .dvcv-subtitle, .teamlist-popup, .teamlist-popup p,.dvcv-content,.dvcv-content p {color:' . $ptextcolor . '}';
        $inline_style .= '.dv-panel,.dv-panel p, .dv-panel input.wpcf7-form-control.wpcf7-submit,.teamlist-popup,.teamlist-popup p,.teamlist-popup input.wpcf7-form-control.wpcf7-submit {font-size:' . $textsize . 'px}';
        $inline_style .= '.dv-panel h1,.dv-panel h2,.dv-panel h3,.dv-panel h4,.dv-panel h5,.dv-panel h6, .dvcv-title, .dvteam-blockquote p,.teamlist-popup h1,.teamlist-popup h2,.teamlist-popup h3,.teamlist-popup h4,.teamlist-popup h5,.teamlist-popup h6,.mfp-close,.mfp-close-btn-in .mfp-close,.mfp-image-holder .mfp-close, .mfp-iframe-holder .mfp-close {color:' . $phtitlescolor . '}';
        $inline_style .= '.dv-panel hr,.teamlist-popup hr {margin:' . $spaceinner . 'px 0 !important}';
        $inline_style .= '.dv-panel-inner {padding:' . $spaceinner . 'px}';
        $inline_style .= '.dv-panel-title {padding:15px ' . $spaceinner . 'px}';
        $inline_style .= '.dv-panel-info {padding:15px ' . $spaceinner . 'px}';
        $inline_style .= '.slidizle-next {right:' . $spaceinner . 'px}';
        $inline_style .= '.slidizle-previous {left:' . $spaceinner . 'px}';
        $inline_style .= '.dvcv-content {margin:' . $spaceinner . 'px 0px 0px 0px !important}';
        $inline_style .= '.dv-panel hr,.teamlist-popup hr {background-color:' . $pdividercolor . '}';
        $inline_style .= '.dv-panel hr,.teamlist-popup hr {height:' . $pdividersize . 'px !important}';
        $inline_style .= '.dv-panel-left {background-color:' . $socialbgcolor . '}';
        $inline_style .= '.dv-panel-inner {background-color:' . $panelbgcolor . '}';
        $inline_style .= '.dv-panel-title {font-size:' . $ptitlesize . 'px}';
        $inline_style .= '.dv-panel-title {background-color:' . $ptitlebgcolor . '}';
        $inline_style .= '.dv-panel-title {color:' . $ptitlecolor . '}';
        $inline_style .= '.dv-panel-info {font-size:' . $psubtitlesize . 'px}';
        $inline_style .= '.dv-panel-info {background-color:' . $psubtitlebgcolor . '}';
        $inline_style .= '.dv-panel-info {color:' . $psubtitlecolor . '}';
        $inline_style .= '.dvteamgrid figure a,.dvteam-thumbnails li a {background-color:' . $thumbnailoverlay . '}';
        $inline_style .= '.dvteamgrid figure:hover img,.dvteam-thumbnails li a img:hover {opacity:' . $thumbnailopacity . '}';
     
        if ($removezoom == 'enable') {
            $inline_style .= '.dvteamgrid figure:hover img,.dvteam-thumbnails li a img:hover {transform: scale(1.2);}';
        }

        if ($removetextanim == 'enable') {
            if (is_rtl()) {
                $inline_style .= '.dvteamgrid figure:hover .dv-member-name,.dvteamgrid figure:hover .dv-member-info,.dvteamgrid figure:hover .dv-member-desc{transform: translateX(100%);
                }';
            } else {
                $inline_style .= '.dvteamgrid figure:hover .dv-member-name,.dvteamgrid figure:hover .dv-member-info,.dvteamgrid figure:hover .dv-member-desc{transform: translateX(-100%);
                }';
            }
        }

        $inline_style .= '.dv-member-zoom,.dvteam-icons li:hover {background-color:' . $infobgcolor . '}';
        $inline_style .= '.dv-member-name {color:' . $imgtitlecolor . '}';
        $inline_style .= '.dv-member-info {color:' . $imgsubtitlecolor . '}';
        $inline_style .= '.dv-member-name {background-color:' . $imgtitlebgcolor . '}';
        $inline_style .= '.dv-member-name {font-size:' . $imgtitlesize . 'px}';
        $inline_style .= '.dv-member-info {background-color:' . $imgsubtitlebgcolor . '}';
        $inline_style .= '.dv-member-info {font-size:' . $imgsubtitlesize . 'px}';
        $inline_style .= '.owl-carousel .owl-nav {background-color:' . $cararrowbg . '}';
        $inline_style .= '.dvskillbar-title {font-size:' . $skillsize . 'px}';
        $inline_style .= '.dvskillbar-title {color:' . $skillcolor . '}';
        $inline_style .= '.dvskill-bar-percent {font-size:' . $percentsize . 'px}';
        $inline_style .= '.dvskill-bar-percent {color:' . $percentcolor . '}';
        $inline_style .= '.dvskillbar {border-width:' . $bordersize . 'px}';
        $inline_style .= '.dvskillbar {border-color:' . $bordercolor . '}';
        $inline_style .= '.dvskillbar-bar {background-color:' . $skillbarbg . '}.dvskillbar-bar{ background-image: linear-gradient(135deg, rgba(255, 255, 255, .03) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .03) 50%, rgba(255, 255, 255, .03) 75%, transparent 75%, transparent)}';
        $inline_style .= '.dvskillbar-title span {background:' . $skillbg . '}';
        $inline_style .= '.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar,.mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar,.mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar,.mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar {background-color:' . $scrollbarcolor . '}';
        $inline_style .= '.dv-panel input, .dv-panel textarea,.teamlist-popup input, .teamlist-popup textarea {background:' . $cfcolorone . '}';
        $inline_style .= '.dv-panel input:focus, .dv-panel textarea:focus,.teamlist-popup input:focus, .teamlist-popup textarea:focus {border-color:' . $cfcolorone . '}';
        $inline_style .= '.dv-panel input.wpcf7-form-control.wpcf7-submit:hover,.teamlist-popup input.wpcf7-form-control.wpcf7-submit:hover {color:' . $cfcolorone . ' !important}';
        $inline_style .= '.dv-panel div.wpcf7-mail-sent-ok,.dv-panel div.wpcf7-mail-sent-ng,.dv-panel div.wpcf7-spam-blocked,.dv-panel div.wpcf7-validation-errors,.teamlist-popup div.wpcf7-mail-sent-ok,.teamlist-popup div.wpcf7-mail-sent-ng,.teamlist-popup div.wpcf7-spam-blocked,.teamlist-popup div.wpcf7-validation-errors {background-color:' . $cfcolorone . '}';
        $inline_style .= '.dv-panel input.wpcf7-form-control.wpcf7-submit,.teamlist-popup input.wpcf7-form-control.wpcf7-submit {background-color:' . $cfcolortwo . ' !important}';
        $inline_style .= '.dv-panel input, .dv-panel textarea,.teamlist-popup input, .teamlist-popup textarea,.dv-panel input.wpcf7-form-control.wpcf7-submit,.teamlist-popup input.wpcf7-form-control.wpcf7-submit {color:' . $cfcolorthree . '}';
        $inline_style .= '.dv-panel input.wpcf7-form-control.wpcf7-submit:hover,.teamlist-popup input.wpcf7-form-control.wpcf7-submit:hover {background-color:' . $cfcolorthree . ' !important}';
        $inline_style .= '.teamlist-popup {background:' . $panelbgcolor . '}';
        $inline_style .= '.mfp-bg {background:' . $overlaycolor . '}';
        $inline_style .= '.mfp-bg {opacity:' . $overlayopacity . '}';
        $inline_style .= '.dvteam-previous a,.dvteam-next a {color:' . $paginationfont . '}';
        $inline_style .= '.dvteam-previous a,.dvteam-next a {background-color:' . $paginationbg . '}';
        $inline_style .= '.dvteam-next a:hover,.dvteam-previous a:hover {color:' . $paginationfont . ' !important}';
        $inline_style .= '.dvteam-next a:hover,.dvteam-previous a:hover {background-color:' . $paginationbghover . '}';

        if (!empty($custom_css)) {
            $inline_style .= $custom_css;
        }

        wp_add_inline_style( 'dvteam_styles', $inline_style );

        // Scripts
        wp_enqueue_script('dv_imagesloaded',plugin_dir_url( __FILE__ ).'js/imagesloaded.js', array( 'jquery' ),'4.1.4',true);
        wp_enqueue_script('dv_wookmark',plugin_dir_url( __FILE__ ).'js/wookmark.js', array( 'jquery' ),'2.2.0',true);
        wp_enqueue_script('dv_owl',plugin_dir_url( __FILE__ ).'js/owl.js','2.3.5','',true);
        wp_enqueue_script('dv_scrollbar',plugin_dir_url( __FILE__ ).'js/scrollbar.js', array( 'jquery' ),'2.0',true);
        wp_enqueue_script('dv_slidepanel',plugin_dir_url( __FILE__ ).'js/panelslider.js', array( 'jquery' ),'2.0',true);
        wp_enqueue_script('dv_popup',plugin_dir_url( __FILE__ ).'js/popup.js', array( 'jquery' ),'2.0',true);
        wp_enqueue_script('dv_slidizle',plugin_dir_url( __FILE__ ).'js/slidizle.js', array( 'jquery' ),'2.0',true);
        wp_enqueue_script('dvteam_custom',plugin_dir_url( __FILE__ ).'js/custom.js', array( 'jquery' ),'2.1',true);

        $dvteam_param = array(
			"rtl" => $rtl
		);
		wp_localize_script('dvteam_custom', 'dvteam_vars', $dvteam_param);
    }

}

/**
 * Returns the main instance of the class.
 */
function DVteamShortcodes() {  
	return DVteamShortcodes::instance();
}
// Global for backwards compatibility.
$GLOBALS['DVteamShortcodes'] = DVteamShortcodes();