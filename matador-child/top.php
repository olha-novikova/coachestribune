<?php
global $smof_data, $ts_top_ad, $woocommerce, $ts_page_id, $current_user;

$logo = ts_option_vs_default('logo_upload', '');
$site_name = esc_attr(get_bloginfo('description')) . ' - ' . ts_option_vs_default('logo_text', get_bloginfo('name'));
?>
<nav class="navbar navbar-jrrny" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo home_url(); ?>" title="<?php echo $site_name; ?>">
                <?php if ($logo) { ?>
                    <img src="<?php echo $logo; ?>" alt="<?php echo $site_name; ?>" class="img img-responsive" <?php echo $logo_xs ? 'srcset="' . $logo_xs . ' 640w"' : ''; ?>>                    
                    <?php
                }
                else {
                    echo $site_name;
                }
                ?>
            </a>
        </div>
        <div id="main_menu">
            <ul id="top-main-menu" class="nav navbar-nav navbar-right">
                <?php echo newNav(); ?>
            </ul>  
            <select onchange="showSportPage(this)"> 
                <option value="" selected="selected">Browse by Sport</option>       
                <option value="<?php echo home_url('/collection/in-football-you-want-to-make-sure-that'); ?>">Football</option>   
                <option value="<?php echo home_url('/collection/soccer-can-be-brutal-when-coaching'); ?>">Soccer</option> 
                <option value="<?php echo home_url('/collection/the-baseball-collection-is-everything-you-need'); ?>">Baseball</option> 
                <option value="<?php echo home_url('/collection/heres-the-best-of-the-best-for-tips-routines'); ?>">Basketball</option>                 
                <option value="<?php echo home_url('/collection/the-field-hockey-collection'); ?>">Field Hockey</option>   
                <option value="<?php echo home_url('/collection/the-ice-hockey-collection'); ?>">Ice Hockey</option>   
                <option value="<?php echo home_url('/collection/the-general-collection'); ?>">General</option>   
                <option value="<?php echo home_url('/collection/the-lacrosse-collection'); ?>">Lacrosse</option>   
                <option value="<?php echo home_url('/collection/the-rugby-collection'); ?>">Rugby</option>   
                <option value="<?php echo home_url('/collection/the-snow-sports-collection'); ?>">Snow Sports</option>   
                <option value="<?php echo home_url('/collection/the-track-and-field-collection'); ?>">Track & Field</option>   
                <option value="<?php echo home_url('/collection/the-volleyball-collection'); ?>">Volleyball</option>   
                <option value="<?php echo home_url('/collection/the-water-sports-collection'); ?>">Water Sports</option>   
                <option value="<?php echo home_url('/collection/the-wrestling-collection'); ?>">Wrestling </option>   
                <option value="<?php echo home_url('/collection/the-esport-collection'); ?>">eSports </option>  
<option value="<?php echo home_url('/collection/the-golf-collection'); ?>">Golf </option>  
            </select> 
        </div>
    </div>
</nav>
<?php if (!is_page(533) && !is_page('map') && !is_post_type_archive('featured_destination')): ?>
    <div id="header-search-bar">
        <div class="container">
            <form action="<?php echo esc_url(home_url('/')); ?>" class="col-xs-12 col-sm-8 col-sm-push-2" method="get" role="search">
                <div class="input-group">
                    <input class="form-control" type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search Tip..."> 
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div> 
            </form>
        </div>
    </div>
<?php endif; ?>
<?php if (is_page(533)): ?> 
    <div id="top-wrap" class="<?php echo ts_top_class(); ?>">   
        <div class="header-table">
            <div class="header-cell">
                <div class="hompage-header-wrapper">    
                    <div class="container">
                        <div class="row">                                    
                            <div class="col-xs-12 text-center title">
                                <img src="/wp-content/uploads/2016/06/Banner-Logo.png" class="img img-responsive img-center">
                                Join The Community!
                            </div>
                            <div class="col-xs-12 text-center">
                                <a href="/upload" class="btn btn-lg btn-gray">Create Coaches Tip</a>
                            </div>
                        </div>
                    </div>                                            
                </div>
            </div>
        </div>  
    </div>  
<?php endif; ?>
<?php if (is_page(6)): ?>
    <div id="top-wrap" class="<?php echo ts_top_class(); ?>">   
        <div class="search-jrrny-holder">
            <div class="container no-padding">
                <div class="row">
                    <div id="singup-form-wrapper">
                        <?php get_template_part('inc/frontend/login/form', 'newsignup'); ?>
                    </div>
                </div>
            </div>           
        </div>    
    </div>                 
<?php endif; ?>

<script>
function showSportPage(element)
{
window.location.href = $(element).val();
}

</script>