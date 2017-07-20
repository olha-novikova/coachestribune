<?php
global $smof_data, $ts_options;
?>
            <?php
            echo ts_get_ticker('above-footer');
            ?>
            <div id="footer-copyright-wrap">
                <?php
                echo ts_get_bottom_ad();
                
                echo ts_bottom_ad_widgets_sep(false);
                
                ts_footer_widgets();
                ?>
                
                <?php
                if(ts_option_vs_default('show_copyright', 1) == 1) :
                ?>
                <div id="copyright-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <p><?php echo do_shortcode(ts_option_vs_default('copyright_text'));?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                endif;
                ?>
                
                <?php
                if(ts_option_vs_default('show_back_to_top', 0) == 1) :
                ?>
                <div id="ts-back-to-top-wrap">
                    <a href="#wrap" id="ts-back-to-top" class="smoothscroll-up"><i class="fa fa-arrow-up"></i></a>
                </div>
                <?php
                endif;
                ?>
                
            </div>
        </div>
    </div>
    
            <?php if(show_beta_popup() && !is_singular( array( 'sponsored_post', 'featured_destination') ) ): ?>
            <div class="modal fade plc-modal" tabindex="-1" role="dialog" id="beta-now">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">

                            <h2>Hey there! Thanks for visiting us!</h2>
                            <p>Jrrny's content is built by contributors just like you. Why not make your first post today?</p>
                            <p><a href="<?= home_url().'/register'?>">Start Here</a></p>

                        </div>
                        <div class="modal-footer hidden">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <?php endif ;?>
            
            
            
            
            <script>
                var tour = '<div class="tour_container"> <div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_0"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>Welcome to Coaches Tribune</h2> <p>We\'re building the world\'s largest collection of coaching tips and tricks built by real coaches, like you. This means that people just like you are encouraged to share their coaching tips so you might connect with others!</p><div class="row modal-btn"> <div class="col-xs-12 col-sm-6"> <button type="button" class="btn btn-turquoise btn-block nextTour" data-dismiss="modal" data-terget="tour_1"><strong>take a tour</strong></button> </div><div class="col-xs-12 col-sm-6"> <a href="<?php echo home_url();?>/register" class="btn btn-red btn-block"><strong>nah, im good</strong><span>(sign me up)</span></a> </div></div></div><div class="modal-footer hidden"> <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_1"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>SHARE YOUR TIP</h2> <p>Coaches Tribune allows anyone to post about their coaching tips using a simple, drag and drop posting tool. It can be your favorite business that helped achieve your successful workouts, plays, or even how to motivate players.</p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/take_a_tour/slide2.png" width="500" height="500" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_2">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_2"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>MY ACCOUNT</h2> <p>Your My Account page allows you to keep track (or edit) the coaching tips you post as well as the ones you like or follow. You can also tell other coaches a little about your expertise!</p></div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_3">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_3"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>MORE INFO</h2> <p>Coaches Tribune posts will have a link associated with them. This can be used to accredit more information, when you find a resource and want to share it.</p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/take_a_tour/slide4.png" width="500" height="500" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_4">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_4"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>FEATURED</h2> <p>Coaches Tribune curates collections of different sports, work outs, and purposes to help you better find what you are looking for!</p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/take_a_tour/slide5.png" width="500" height="500" class="img img-responsive"/> </div><div class="modal-footer"> <button type="button" class="btn btn-link nextTour" data-dismiss="modal" data-terget="tour_5">NEXT ></button> </div></div></div></div><div class="modal fade plc-modal" tabindex="-1" role="dialog" id="tour_5"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <h2>JOIN NOW!</h2> <p>Share your coaches tips with the rest of Coaches Tribune community!</p><div class="row modal-btn"> <div class="col-xs-12 col-sm-push-3 col-sm-6"> <a href="/register" class="btn btn-turquoise btn-block"><strong>sign up now</strong></a> </div></div></div></div></div></div></div>';
            </script>
<?php 
$ts_disqus_shortname = ts_option_vs_default('disqus_shortname', '');
if((ts_option_vs_default('use_disqus', 1) == 1) && trim($ts_disqus_shortname)) :
?>
<script type="text/javascript">
/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
var disqus_shortname = '<?php echo esc_js($ts_disqus_shortname);?>'; // required: replace example with your forum shortname
(function($) {
    $(window).load(function() {
        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = '//<?php echo esc_js($ts_disqus_shortname);?>.disqus.com/count.js';
            (document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    });
})(jQuery);
</script>
<?php
endif;
if(ts_enable_style_selector()) :
    get_template_part('style_selector');
endif;
wp_footer();
?>
</body>
</html>