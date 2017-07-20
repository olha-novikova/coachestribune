<?php 
$home_img = get_option('plc_header_images');
$home_img = explode(',', $home_img);
$headImgId = isset($home_img[0]) ? $home_img[0] : '';

$style = '';
$class = " no-image";
if ($headImgId) {
    $style = ' style="background-image: url(' . $headImgId . ')"';
    $class = " image";
}
?>
<div class="plc-access <?php echo $class;?>"<?php echo $style;?>>
    <div class="container">
        <img src="/wp-content/uploads/2016/06/Banner-Logo.png" class="img img-responsive"/>
        <h1>Welcome to <br/>Coaches Tribune</h1>
        <p>Please enter your password below for Beta access</p>
        <form method="POST">
            <?php if(isset($_POST['password'])){ ?>
                <div class="alert alert-danger">Wrong access code</div>
            <?php } ?>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" autofocus="autofocus">
            </div>
            <button class="btn btn-gray">ACCESS</button>
        </form>
    </div> 
</div> 
