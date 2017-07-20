<?php
/*
  Template Name: Upload Jrrny
 */
global $plcCategoryTags;
$catTag = $plcCategoryTags->get_category_tag_array();
$categories = $catTag['categories'];
$tags = $catTag['tags'];

if (!is_user_logged_in()):
    wp_redirect(home_url() . '#login-form', '301');
    exit;
endif;
get_header();
?>
<script>
    jQuery(document).ready(function () {
        jQuery('html, body').animate({
            scrollTop: jQuery(".uploader-header").offset().top
        }, 2000);
    });
</script>
<div id="after-upload-modal" class="modal fade plc-modal in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div>
                    <h3 class="title">Dont forget to share!</h3>
                </div>
                <div class="modal-social">
                    <div class="social facebook">
                        <div class="share-pop" id="bf-share" data-target="">
                            <span class="flaticon flaticon-facebook-logo-button"></span>
                            Share on Facebook
                        </div>
                    </div>
                    <div class="social twitter">
                        <a href="" class="share-pop">
                            <span class="flaticon flaticon-twitter-logo-button"></span>
                            Share on Twitter
                        </a>
                    </div>
                </div>
                <div class="link-to-jrrny">
                    <a id="link-to-jrrny" href="">No Thanks!</a>
                </div>
            </div>
        </div>
    </div>
</div> <?php
global $current_user;


wp_get_current_user();
get_template_part('top');
?>
<div id="main-container-wrap"
     class="upload <?php echo esc_attr(ts_main_container_wrap_class('page')) . ' ' . esc_attr($main_container_wrap_class); ?>">

    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            ?>
            <div id="main-container" class="container clearfix">
                <div id="main" class="<?php echo esc_attr(ts_main_div_class()); ?> clearfix">
                    <div class="uploader-header">
                        <h2><i class="flaticon flaticon-checkbox-pen-outline"></i> Create your tip</h2>
                        <?php
                        the_content();
                        wp_link_pages('before=<div class="page-links">' . __('Pages: ', 'ThemeStockyard') . '<span class="wp-link-pages">&after=</span></div>&link_before=<span>&link_after=</span>');
                        ?>
                    </div>
                </div>
            </div><!-- #main-container -->
            <?php
        endwhile;
    else :
        ?>
        <div id="main-container" class="container clearfix">
            <div id="main" class="<?php echo esc_attr($ts_show_sidebar_class); ?> clearfix">
                <div class="entry single-entry clearfix">
                    <div class="post">
                        <p><?php _e('Sorry, the post you are looking for does not exist.', 'ThemeStockyard'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endif;
    ?>
    <!-- form container -->
    <div class="container">
        <form id="form-journey" class="form-horizontal" method="post" action="<?php echo home_url(); ?>/upload">
            <?php get_contest_input_uploader_tool($current_user->ID); ?>
            <div class="form-group">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon icon-soccerball"></i></span>
                        <?php /* <input type="text" name="sport" id="sport-jrrny" class="form-control"
                          placeholder="What Sport?"
                          value="<?php echo((isset($_GET['sport']) && $_GET['sport'] !== '') ? $_GET['sport'] : ''); ?>"> */ ?>
                        <select name="sport" id="sport-jrrny" class="select form-control">
                            <option value="">What Sport?</option>
                            <?php
                            $tags_opt = '';
                            foreach ($categories as $row) {
                                $cat_id = $row['id'];
                                $cat_title = $row['title'];
                                $cat_value = $row['value'];
                                ?>                            
                                <option value="<?php echo $cat_value; ?>" <?php echo((isset($_GET['sport']) && $_GET['sport'] === $cat_value) ? ' selected="selected"' : ''); ?>><?php echo $cat_title; ?></option>                          
                                <?php
                                foreach ($row['tags'] as $row) {
                                    $tag_id = $row['id'];
                                    $tag_title = $row['title'];
                                    $tag_value = $row['value'];
                                    
                                    $tags_opt .= '<option class="' . $cat_value . '" value="' . $tag_value .'" ' . ((isset($_GET['tip']) && $_GET['tip'] === $tag_value) ? ' selected="selected"' : '').'>'. $tag_title.'</option>';                         


                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="input-group flaticon-absolute">
                        <span class="input-group-addon"><i class="icon icon-whistle"></i></span>
                        <?php /* <input type="text" name="tip" id="tip-jrrny" class="form-control"
                          placeholder="What Tip?"
                          value="<?php echo((isset($_GET['tip']) && $_GET['tip'] !== '') ? $_GET['tip'] : ''); ?>"> */ ?>
                        <select name="tip" id="tip-jrrny" class="select form-control">
                            <option value="">What Tip?</option>
                            <?php
                            echo $tags_opt;/*
                            foreach ($tags as $row) {
                                $tag_id = $row['id'];
                                $tag_title = $row['title'];
                                $tag_value = $row['value'];
                                $tag_class = implode(' ', $row['class']);
                                ?>    
                                <option class="<?php echo $tag_class; ?>" value="<?php echo $tag_value; ?>" <?php echo((isset($_GET['tip']) && $_GET['tip'] === $tag_value) ? ' selected="selected"' : ''); ?>><?php echo $tag_title; ?></option>                          

                            <?php }*/
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 no-left-padding">
                    <div class="input-group flaticon-absolute">
                        <span class="input-group-addon"><i class="flaticon flaticon-clipboard-icon"></i></span>
                        <input type="text" name="tip_title" id="tip_title" class="form-control"
                               placeholder="Tip title"
                               value="<?php echo((isset($_GET['tip_title']) && $_GET['tip_title'] !== '') ? $_GET['tip_title'] : ''); ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">                    
                <div class="col-xs-12  no-left-padding">
                    <div class="input-group yt">
                        <span class="input-group-addon"><i class="fa fa-video-camera fa-3x"></i></span>
                        <input type="text" name="video-link" id="video-link" class="form-control"
                               placeholder="Add YouTube Link">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12 no-left-padding">
                    <div class="input-group yt">
                        <?php $about_coach = get_post_meta(get_the_ID(), "_about_coach", true); ?>
                        <span class="input-group-addon"><i class="fa fa-user fa-3x"></i></span>
                        <textarea name="about-coach" id="about-coach" class="form-control" placeholder="Some info about"><?php if ($about_coach) echo $about_coach;?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12 no-left-padding">
                    <div class="input-group yt">
                        <?php $website_coach = get_post_meta(get_the_ID(), "_website_coach", true); ?>
                        <span class="input-group-addon"><i class="fa fa-anchor fa-3x"></i></span>
                        <input type="text" name="website-coach" id="website-coach" class="form-control"
                               placeholder="Add Website URL"
                               value="<?php echo $website_coach; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <label for="story" class="no-padding">
                        <i class="icon icon-watch"></i> Describe your tip
                    </label>
                    <?php
                    $user_id = get_current_user_id();
                    if (is_user_in_role($user_id, 'blogger') || is_user_in_role($user_id, 'celebrity') || is_user_in_role($user_id, 'administrator')) {
                        global $wyswig_settings;
                        wp_editor('', 'story', $wyswig_settings);
                    }
                    else {
                        ?>
                        <textarea id="story" class="form-control" name="story"><?= $story ?></textarea>
<?php } ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <label class="video-dropzone-label"><i class="fa fa-video-camera fa-2x"></i> Add Video:</label>
                </div>
                <div class="jrrny-dropzones-container upload-page video-upload">
                    <div class="col-md-12">
                        <div id="jrrny-video-dropzone" class="form-group video-upload-dropzone dropzone ">
                            <div class="dz-message" data-dz-message>
                                <p><i class="flaticon flaticon-uploading-archive"></i></p>

                                <p>Video tip</p>
                                <span>You can upload MP4, WebM, Ogg in max file size 100MB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <label for="dropzone"><i class="flaticon flaticon-photo-camera-outline"></i> Add Images:
                    </label>
                </div>
                <div class="jrrny-dropzones-container upload-page">
                    <div class="col-md-12">
                        <div id="jrrny-images-dropzone" class="form-group image-upload-dropzone dropzone ">
                            <div class="dz-message" data-dz-message>
                                <p><i class="flaticon flaticon-uploading-archive"></i></p>

                                <p>Photos for explanation</p>
                                <span class="visible-xs">tap to add up to (12) photos </span>
                                <span class="hidden-xs">drag and drop up to (12) photos or click to browse</span>
                                <br class="hidden-xs"> <span>You can upload JPEG, JPG, PNG, GIF</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">                    
                <div class="col-xs-12 ">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon icon-trophy"></i></span>
                        <input type="text" name="tip-source" id="tip-source" class="form-control"
                               placeholder="Link to tip source if any">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="flaticon flaticon-lighting-button"></i></span>
                        <input type="text" name="insider-tip" id="insider-tip" class="form-control"
                               placeholder="Insider tip? - Tell us the secrets">
                    </div>
                </div>
            </div>           
            <div class="form-group">     
                <div class="checkbox rules">
                    <label for="rules">
                        <input type="checkbox" name="rules" value="1" id="rules" required="required ">
                        By submitting your content to Coaches Tribune, you acknowledge that you are not violating others copyrights or privacy rights.
                    </label>
                </div>
            </div>
            <input type="hidden" name="_token" value="<?php echo $current_user->user_login; ?>"/>
            <input type="hidden" id="jrrny-main-image-id" name="main-image-id" value=""/>

            <div class="form-group">
                <div class="col-xs-12">
                    <button id="journey-data-process" class="btn btn-turquoise btn-lg">
                        Upload
                        <i class="fa processing-icon hide"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div><!-- #main-container-wrap -->

<!-- Modal -->
<div class="modal fade" id="previewModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h1>
                    <center>Coachestribune Tip Preview</center>
                </h1>
            </div>
            <div class="modal-body">
                <div class="preview-holder preview_title">
                    <h1 class="entry-title"><span id="previewTitle">Football</span> - <span id="whereStay">Proper Tackling Techniques</span>
                    </h1>
                    <span class="preview_cat">Tip Post / </span><span
                        class="preview-date"><?php echo date("F j, Y"); ?> / </span><span
                        class="preview_author"><?php echo $current_user->user_login; ?></span>
                </div>
                <div class="preview-holder jrrny-all-images">
                    <h3>Tip Images:</h3>

                    <div id="imagesForJrrny">
                    </div>
                </div>
                <div class="preview-story">
                    <h3>Story:</h3>

                    <p id="previewStory">Proper Tackling Techniques</p>
                </div>
                <div class="preview-embed">
                    <h3>Embed:</h3>
                    <p><strong>Link: </strong><span id="previewEmbedLink">https://www.youtube.com</span></p>
                </div>
                <div class="preview-stay">
                    <h3>Tip source:</h3>

                    <p><strong>Link: </strong><span id="previewHotelLink">https://www.google.com</span></p>
                </div>
                <div class="preview-stay">
                    <h3>Insider Tip:</h3>

                    <p><strong>Link: </strong><span id="previewInsiderlLink">Insider...</span></p>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" id="jrrny-submit-preview">Submit</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!-- END Modal -->
<?php
get_footer();
