<?php $disabled = is_user_logged_in() ? 'disabled' : ''; ?>

<div id="sign_wrapper" class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-5">
            <div class="singup-form-wrapper">
                <h3 class="title">Join the Coaches Tribune community</h3>
                <form method="post" id="signup_form">
                    <div class="form-group">
                        <label class="sr-only" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group buttons">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <button id="signup_btn" class="btn btn-lg btn-turquoise btn-block <?php echo $disabled;?>">Join&nbsp;<i class="fa processing-icon hide"></i></button>
                            </div>   
                            <div class="col-xs-12 col-sm-6">
                                <button id="signin_btn" class="btn btn-lg btn-link btn-block login_modal <?php echo $disabled;?>">or <strong>Log in</strong>&nbsp;<i class="fa processing-icon hide"></i></button>
                            </div>                           
                        </div>
                    </div>
                    <div class="form-group info">
                        <p class="help-block">By joining coachestribune, you agree to our <a href="<?php echo home_url();?>/terms.pdf"><strong>TERMS OF SERVICE</strong></a> and <strong><a href="<?php echo home_url();?>/privacy.pdf">PRIVACY POLICY</strong></a></p>
                    </div>
                    <div class="form-group social-logins">
                        <?php $current_url = home_url(add_query_arg(array(),$wp->request));?>
                        
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 no-right-padding">
                                <a class="fb-login <?php echo $disabled;?>" data-provider="Facebook" title="Connect with Facebook" rel="nofollow" href="<?php echo home_url();?>/admin4214?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to=<?php echo urlencode($current_url);?>"><span class="flaticon flaticon-facebook-logo-button"></span>&nbsp;&nbsp;Join with Facebook</a>
                            </div>   
                            <div class="col-xs-12 col-sm-6">
                                <a class="tw-login <?php echo $disabled;?>" data-provider="Twitter" title="Connect with Twitter" rel="nofollow" href="<?php echo home_url();?>/admin4214?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to=<?php echo urlencode($current_url);?>"><span class="flaticon flaticon-twitter-logo-button"></span>&nbsp;&nbsp;Join with Twitter</a>
                            </div>                           
                        </div>
                    </div>
                </form>        
            </div>      
        </div>
        <div class="col-xs-12 col-sm-7">
            <div class="singup-content-wrapper">
                <ul class="list-unstyled">
                    <li><span class="flaticon flaticon-sharing-interface"></span> Share your coaching expertise</li>
                    <li><span class="flaticon flaticon-user-avatar-main-picture"></span> Connect with other coaches</li>
                    <li><span class="flaticon flaticon-hearts-outline-icon"></span> Promote your sport</li>
                    <li><span class="flaticon flaticon-map-pin-marked"></span> Find great tips to improve</li>
                </ul>                
            </div>            
        </div>
    </div>                                
</div>
