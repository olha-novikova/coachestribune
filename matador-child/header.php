<?php
global $page, $paged, $smof_data, $ts_custom_css;

if((ts_option_vs_default('catalog_mode', 0) == 1) && (ts_is_woo_cart() || ts_is_woo_checkout())) :
    $shop_page = get_post(woocommerce_get_page_id('shop'));
    $shop_page = get_permalink($shop_page->ID);
    wp_redirect($shop_page);
    exit;
endif;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr(ts_html_class());?>">
<head>

<meta name="p:domain_verify" content="34dd48bf0aac148e571d37344ab507bb"/>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89616502-1', 'auto');
  ga('send', 'pageview');

</script>

<title><?php wp_title(' &#8212; ');?></title>
<?php
if(ts_option_vs_default('responsive', 1) == 1) :
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<?php
endif;
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="all, index, follow" />
<meta name="p:domain_verify" content="2650376dab48c33e8dfe6ffdbe40b443"/>

<meta property="og:title" content="Check out this awesome tip on Coaches Tribune."/>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if(trim(ts_option_vs_default('custom_favicon', ''))) : ?>
<link rel="shortcut icon" href="<?php echo esc_url($smof_data['custom_favicon']);?>" />
<?php endif; ?>
<?php if(trim(ts_option_vs_default('iphone_icon', ''))) : ?>
<!-- For iPhone -->
<link rel="apple-touch-icon-precomposed" href="<?php echo esc_url($smof_data['iphone_icon']); ?>">
<?php endif; ?>
<?php if(trim(ts_option_vs_default('iphone_icon_retina', ''))) :?>
<!-- For iPhone 4 Retina display -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url($smof_data['iphone_icon_retina']); ?>">
<?php endif; ?>
<?php if(trim(ts_option_vs_default('ipad_icon', ''))) : ?>
<!-- For iPad -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url($smof_data['ipad_icon']); ?>">
<?php endif; ?>
<?php
ts_grab_google_fonts(true);
?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript">
theme_directory_uri = '<?php echo esc_js(get_template_directory_uri());?>';
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71617101-1', 'auto');
  ga('send', 'pageview');
</script>
<script>
var trackOutboundLink = function(url) {
   ga('send', 'event', 'outbound', 'click', url, {
     'transport': 'beacon',
     'hitCallback': function(){document.location = url;}
   });
}
</script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '1601303066863940');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1601303066863940&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<?php
wp_head();
?>
</head>

<body <?php ts_body_class(); ?>>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '145886185939751',
            xfbml      : true,
            version    : 'v2.8'
        });
        FB.AppEvents.logPageView();
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
    <div id="wrap">
        <div class="wrap-inner">
