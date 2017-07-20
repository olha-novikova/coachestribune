<div class="wrap">
    <h2>
        Category Tags <br>
        <small>
            Select Category and add related tag's
        </small>
    </h2>
    <?php
    if ($validation) {
        foreach ($validation as $msg) {
            echo $msg;
        }
    }
    ?>

    <form method="post">
        <div class="form-group">
            <label>Category</label>
            <select name="category" class="selectpicker form-control" data-placeholder="Select category" required="required">
                <option value="">Select category</option>
                <?php
                foreach ($categories as $category) {
                    $id = $category->term_id;
                    $title = $category->name;
                    ?>
                    <option value="<?php echo $id; ?>,<?php echo $title; ?>"><?php echo $title; ?></option>
                <?php }
                ?>                
            </select>
        </div>
        <div class="form-group">
            <label>Tags for category</label>
            <select name="tags[]" class="selectpicker-tags form-control" data-placeholder="Select tags" multiple="multiple">
                <?php
                foreach ($tags as $tag) {
                    $id = $tag->term_id;
                    $title = $tag->name;
                    ?>
                    <option value="<?php echo $id; ?>,<?php echo $title; ?>"><?php echo $title; ?></option>
                <?php }
                ?>           
            </select>
        </div>
        <?php submit_button(); ?>
    </form>
    <?php if ($categories_tags) { ?>
        <table class="wp-list-table widefat fixed striped tags">
            <thead>
                <tr>
                    <td>Category</td>
                    <td>Tags</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $categories_array = array();
                foreach ($categories_tags as $row) {
                    $category = unserialize($row->category);
                    $tags = unserialize($row->tags);
                    $cat_id = $category[0];
                    $cat_title = $category[1];
                    $cat_value = $cat_id . ',' . $cat_title;
                    $categories_array[$cat_id] = array(
                        'id' => $cat_id,
                        'title' => $cat_title,
                        'value' => $cat_value,
                        'tags' => $tags
                    );
                }
                $categoryTags = array_sort($categories_array, 'title', SORT_ASC);
                foreach ($categoryTags as $row) {
                    $tags = $row['tags'];
                    $cat_id = $row['id'];
                    $cat_title = $row['title'];
                    ?>
                    <tr>
                        <td><?php echo $cat_title; ?></td>
                        <td>
                            <?php
                            foreach ($tags as $tag) {
                                $tag_id = $tag[0];
                                $tag_title = $tag[1];
                                ?>
                                <span class="label"><?php echo $tag_title; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
<script>
    jQuery(function ($) {
        $('.selectpicker').select2();
        $('.selectpicker-tags').select2({
            tags: true
        });
        $(".selectpicker-tags").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
    });
</script>
