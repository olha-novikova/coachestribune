<?php if (is_user_logged_in()) { ?>
    <div class="youtube-uploader">
        <h1>Bulk YouTube Uploader</h1>
        <p><strong>Create multiple posts </strong>from your YouTube channel</p>
        <hr/>
        <div id="validation">
            <?php
            if ($errors) {
                foreach ($errors as $error) {
                    ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php
                }
            }
            ?>
        </div>
        <form id="youtubeUploader" method="post">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label>What Sport?</label>
                        <select name="sport" id="sport-jrrny" class="select form-control input-lg">
                            <?php
                            foreach ($categories as $row) {
                                $cat_id = $row['id'];
                                $cat_title = $row['title'];
                                $cat_value = $row['value'];
                                ?>                            
                                <option value="<?php echo $cat_value; ?>" <?php echo((isset($_POST['sport']) && $_POST['sport'] === $cat_value) ? ' selected="selected"' : ''); ?>><?php echo $cat_title; ?></option>                          
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label>What Tip?</label>
                        <select name="tip" id="tip-jrrny" class="select form-control input-lg">
                            <?php
                            foreach ($tags as $row) {
                                $tag_id = $row['id'];
                                $tag_title = $row['title'];
                                $tag_value = $row['value'];
                                $tag_class = implode(' ', $row['class']);
                                ?>    
                                <option class="<?php echo $tag_class; ?>" value="<?php echo $tag_value; ?>" <?php echo((isset($_POST['tip']) && $_POST['tip'] === $tag_value) ? ' selected="selected"' : ''); ?>><?php echo $tag_title; ?></option>                          

                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Insert YouTube links <br><small>You can insert up to 10 links per upload. Hit enter after each link.</small></label>

                <textarea id="yt_link" name="yt_link" class="form-control" rows="12"></textarea>
            </div>
            <div class="form-group">
                <label>About<br><small>Enter up to 100 words</small></label>
                <textarea id="about_coach" name="about_coach" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Link to channel/website<br><small>Enter a valid URL for website or channel</small></label>
                <input type = "text" id="website_coach" name="website_coach" class="form-control">
            </div>
            <input type="hidden" name="action" value="plc_upload_youtube_ajax"/>
            <button id="plc_upload_yt" type="submit" class="btn btn-turquoise btn-lg">Submit <i class="fa processing-icon hide"></i></button>
        </form>
    </div>

    <?php
}
else {
    ?>
    <div class="alert alert-info">Please login to continue</div>
    <?php
}
