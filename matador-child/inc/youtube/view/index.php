<div class="wrap">
    <h2>
        Youtube <br>
        <small>
            Create post from youtube links. Each link = one new post
        </small>
    </h2>

    <form method="post">
        <div class="form-group">                    
            <label>What Sport?</label>
            <select name="sport" id="sport-jrrny" class="select form-control">               
                <?php
                foreach ($categories as $row) {
                    $cat_id = $row['id'];
                    $cat_title = $row['title'];
                    $cat_value = $row['value'];
                    ?>                            
                    <option value="<?php echo $cat_value; ?>" <?php echo((isset($_GET['sport']) && $_GET['sport'] === $cat_value) ? ' selected="selected"' : ''); ?>><?php echo $cat_title; ?></option>                          
                <?php }
                ?>
            </select>
        </div>
        <div class="form-group">            
            <label>What tip?</label>
            <select name="tip" id="tip-jrrny" class="select form-control">
                <?php
                foreach ($tags as $row) {
                    $tag_id = $row['id'];
                    $tag_title = $row['title'];
                    $tag_value = $row['value'];
                    $tag_class = implode(' ', $row['class']);
                    ?>    
                    <option class="<?php echo $tag_class; ?>" value="<?php echo $tag_value; ?>" <?php echo((isset($_GET['tip']) && $_GET['tip'] === $tag_value) ? ' selected="selected"' : ''); ?>><?php echo $tag_title; ?></option>                          

                <?php }
                ?>
            </select>
        </div>
</div>
<div class="form-group">
    <label>Youtube links <br><small>You can insert up to 10 links per one time, after each link hit enter</small></label>
    <textarea name="yt_link" class="form-control" rows="12"></textarea>
</div>
<div class="form-group">
    <label>About</label>
    <textarea id="about_coach" name="about_coach" class="form-control" rows="3"></textarea>
</div>
<div class="form-group">
    <label>Link to channel/website</label>
    <input type = "text" id="website_coach" name="website_coach" class="form-control">
</div>
<?php submit_button(); ?>
</form>
</div>
<script>
    jQuery(document).ready(function ($) {
        $('.select').select2();
        $("#tip-jrrny").chained("#sport-jrrny");
    });
</script>
