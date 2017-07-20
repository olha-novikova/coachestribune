<?php
global $plcCategoryTags;
$catTag = $plcCategoryTags->get_category_tag_array();
$categories = $catTag['categories'];
$tags = $catTag['tags'];

get_header();
get_template_part('top');
get_template_part('title-page');
the_post();
?>
<?php
$post_all_images = get_attached_media("image", get_the_id());
//$hotelImageId = get_post_meta(get_the_ID(), "_hotel_image_id", true);

$hotelImagesId = [];
for ($i = 1; $i <= 2; ++$i) {
    $id = get_post_meta(get_the_ID(), '_hotel_image_' . $i . '_id', true);
    if (!empty($id)) {
        $hotelImagesId[$i] = $id;
    }
}

$post_images = [];
for ($i = 1; $i <= 10; ++$i) {
    $id = get_post_meta(get_the_ID(), '_p_image_' . $i . '_id', true);
    if (!empty($id)) {
        $post_images[$i] = $id;
    }
}


$post_video = [];
$id = get_post_meta(get_the_ID(), '_p_video_1_id', true);
if (!empty($id)) {
    $post_video[1] = $id;
}
/*
  for ($i = 1; $i <= 10 ; ++$i){
  $id = get_post_meta(get_the_ID(), '_p_video_' . $i . '_id', true);
  if(!empty($id)){
  $post_video[$i] = $id;
  }
  } */
?>



<?php
if ($http_tip_source = get_post_meta(get_the_ID(), "_tip_source", true)) {
    if (empty(parse_url($http_tip_source)["scheme"])) {
        $http_tip_source = "http://" . $http_tip_source;
    }
}
?>
<div class="container">
    <form id="form-journey" class="form-horizontal" enctype="multipart/formdata" method="post">
        <input type="hidden" name="_token" value="<?php echo $current_user->user_login; ?>" />
        <input type="hidden" name="post_id" value="<?= get_the_ID() ?>" />
        <?php $thumbId = get_post_thumbnail_id(get_the_id()); ?>
        <input type="hidden" id="jrrny-main-image-id" name="main-image-id" value="<?= $thumbId ?>" data-url="<?= wp_get_attachment_url($thumbId) ?>"/>
        <?php
        if ($hotelImagesId) :
            foreach ($hotelImagesId as $hotelImageId) :
                $imageInfo = wp_get_attachment_image_src($hotelImageId, 'thumbnail');
                if (is_array($imageInfo)) :
                    $url = $imageInfo[0];
                    ?>
                    <input id="jrrny-himage-<?= $hotelImageId ?>" data-url="<?= $url ?>" type="hidden" value="<?= $hotelImageId ?>" name="imagesh[]">
                    <?php
                endif;
            endforeach;

        endif;

        foreach ($post_video as $videoId) :
            $url = wp_get_attachment_url($videoId);
            if ($url):
                ?>
                <input id="jrrny-video-<?= $videoId ?>" data-url="<?= $url ?>" type="hidden" value="<?= $videoId ?>" name="video[]">
                <?php
            endif;
        endforeach;

        foreach ($post_images as $imageId) :
            $imageInfo = wp_get_attachment_image_src($imageId, 'thumbnail');
            if (is_array($imageInfo)) :
                $url = $imageInfo[0];
                ?>
                <input id="jrrny-image-<?= $imageId ?>" data-url="<?= $url ?>" type="hidden" value="<?= $imageId ?>" name="images[]">
            <?php
            endif;
        endforeach;
        ?>

        <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-soccerball"></i></span>
                    <?php /* <input type="text" name="sport" id="sport-jrrny" class="form-control"
                      placeholder="What Sport?"
                      value="<?php echo((isset($_GET['sport']) && $_GET['sport'] !== '') ? $_GET['sport'] : ''); ?>"> */ ?>
                    <?php $sport = get_post_meta(get_the_ID(), "_sport", true); ?>
                    <?php $tip = get_post_meta(get_the_ID(), "_tip", true); ?>
                    <select name="sport" id="sport-jrrny" class="select form-control">
                        <option value="">What Sport?</option>
                        <?php
                        $tags_opt = '';
                        foreach ($categories as $row) {
                            $cat_id = $row['id'];
                            $cat_title = $row['title'];
                            $cat_value = $row['value'];
                            ?>                            
                            <option value="<?php echo $cat_value; ?>" <?php echo(($sport === $cat_value) || ((isset($_GET['sport']) && $_GET['sport'] === $cat_value)) ? ' selected="selected"' : ''); ?>><?php echo $cat_title; ?></option>                          
                            <?php
                            foreach ($row['tags'] as $row) {
                                $tag_id = $row['id'];
                                $tag_title = $row['title'];
                                $tag_value = $row['value'];

                                $tags_opt .= '<option class="' . $cat_value . '" value="' . $tag_value . '" ' . (($tip === $tag_value) ? ' selected="selected"' : '') . '>' . $tag_title . '</option>';
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
                        echo  $tags_opt;
                        /*
                        foreach ($tags as $row) {
                            $tag_id = $row['id'];
                            $tag_title = $row['title'];
                            $tag_value = $row['value'];
                            $tag_class = implode(' ', $row['class']);
                            ?>    
                            <option class="<?php echo $tag_class; ?>" value="<?php echo $tag_value; ?>" <?php echo(($tip === $tag_value) || ((isset($_GET['tip']) && $_GET['tip'] === $tag_value)) ? ' selected="selected"' : ''); ?>><?php echo $tag_title; ?></option>                          

                        <?php } */
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 no-left-padding">
                <div class="input-group flaticon-absolute">
                    <span class="input-group-addon"><i class="flaticon flaticon-clipboard-icon"></i></span>
                    <?php $tip_title = get_post_meta(get_the_ID(), "_tip_title", true); ?>
                    <input type="text" name="tip_title" id="tip_title" class="form-control"
                           placeholder="Tip title"
                           value="<?php echo((isset($_GET['tip_title']) && $_GET['tip_title'] !== '') ? $_GET['tip_title'] : $tip_title); ?>">
                </div>
            </div>
        </div>
        <div class="form-group">                    
            <div class="col-xs-12 no-left-padding">
                <div class="input-group yt">
                    <?php $videoLink = get_post_meta(get_the_ID(), "_video_link", true); ?>
                    <span class="input-group-addon"><i class="fa fa-video-camera fa-3x"></i></span>
                    <input type="text" name="video-link" id="video-link" class="form-control"
                           placeholder="Add YouTube Link"
                           value="<?php echo $videoLink; ?>">
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
                           placeholder="Add Coach's site or channel"
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
                $story = get_the_content();
                $user_id = get_current_user_id();
                if (is_user_in_role($user_id, 'blogger') || is_user_in_role($user_id, 'celebrity') || is_user_in_role($user_id, 'administrator')) {
                    global $wyswig_settings;
                    wp_editor($story, 'story', $wyswig_settings);
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
                <label for="dropzone"><i class="flaticon flaticon-photo-camera-outline"></i> Add Images: </label>     
            </div>
            <div class="jrrny-dropzones-container upload-page">

                <div class="col-md-12">
                    <div id="jrrny-images-dropzone" class="form-group image-upload-dropzone dropzone ">
                        <div class="dz-message" data-dz-message>
                            <p><i class="flaticon flaticon-uploading-archive"></i></p>
                            <p>Photos of explanation</p>
                            <span class="visible-xs">tap to add up to (12) photos </span>
                            <span class="hidden-xs">drag and drop up to (12) photos or click to browse</span>
                            <br class="hidden-xs"><span>You can upload JPEG, JPG, PNG, GIF</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon icon-trophy"></i></span>
                    <input type="text" name="tip-source" id="tip-source" class="form-control" placeholder="Link to tip source if any"  value="<?php echo $http_tip_source; ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <?php $insiderTip = get_post_meta(get_the_ID(), "_insider_tip", true); ?>
                    <span class="input-group-addon"><i class="flaticon flaticon-lighting-button"></i></span>
                    <input type="text" name="insider-tip" id="insider-tip" class="form-control" placeholder="Insider tip? - Tell us the secrets" value="<?php echo $insiderTip; ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <button id="journey-data-process" class="btn btn-turquoise btn-lg">
                    Save
                    <i class="fa processing-icon hide"></i>
                </button>
            </div>
        </div>
    </form>                
</div>