<?php
	/*
	 * This is the main template to display a blog without using skins.
	 *
	 * Same display using evoSkins: blog_all.php
	 */

	# First, select which blog you want to display here!
	# You can find these numbers in the back-office under the Blogs section.
	# You can also create new blogs over there.
	$blog = 4;   	// 4 is for the blogroll

	# Tell b2evolution you don't want to use evoSkins for this template:
	$skin = '';
	
	# This setting retricts posts to those published, thus hiding drafts.
	# You should not have to change this.
	$show_statuses = "'published'";

	# This is the blog to be used as a blogroll (set to 0 if you don't want to use this feature)
	$blogroll_blog = 4;

	# This is the list of categories to restrict the blogroll to (cats will be displayed recursively)
	# Example: $blogroll_cat = '4,6,7';
	$blogroll_cat = '';

	# This is the array if categories to restrict the blogroll to (non recursive)
	# Example: $blogroll_catsel = array( 4, 6, 7 );
	$blogroll_catsel = array( );

	# Here you can set a limit before which posts will be ignored
	# You can use a unix timestamp value or 'now' which will hide all posts in the past
	$timestamp_min = '';

	# Here you can set a limit after which posts will be ignored
	# You can use a unix timestamp value or 'now' which will hide all posts in the future
	$timestamp_max = 'now';


	# Let b2evolution handle the query string and load the blog data:
	require(dirname(__FILE__)."/b2evocore/_blog_main.php");
	
	
	# Now, below you'll find the main template...
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php locale_lang() ?>" lang="<?php locale_lang() ?>"><!-- InstanceBegin template="/Templates/Standard.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php locale_charset() ?>" />
<title><?php
	bloginfo('name', 'htmlhead');
	single_cat_title( ' - ', 'htmlhead' );
	single_month_title( ' - ', 'htmlhead' );
	single_post_title( ' - ', 'htmlhead' );
	switch( $disp )
	{
		case 'comments': echo ' - ', T_('Last comments'); break;
		case 'stats': echo ' - ', T_('Statistics'); break;
		case 'arcdir': echo ' - ', T_('Archive Directory'); break;
	}
?></title>
<!-- InstanceEndEditable --> 
<!-- InstanceBeginEditable name="head" -->
<base href="<?php skinbase(); // You're not using any skin here but this won't hurt. However it will be very helpfull to have this here when you make the switch to a skin! ?>" />
<meta name="description" content="<?php bloginfo('shortdesc', 'htmlhead'); ?>" />
<meta name="keywords" content="<?php bloginfo('keywords', 'htmlhead'); ?>" />
<link rel="alternate" type="text/xml" title="RDF" href="<?php bloginfo('rdf_url', 'raw'); ?>" />
<link rel="alternate" type="text/xml" title="RSS" href="<?php bloginfo('rss2_url', 'raw'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url', 'raw'); ?>" />
<link href="skins/fplanque2002/blog.css" rel="stylesheet" type="text/css" />
 <!-- InstanceEndEditable --> 
<link rel="stylesheet" href="skins/fplanque2002/basic.css" type="text/css" />
<link rel="stylesheet" href="skins/fplanque2002/fpnav.css" type="text/css" />
<!-- InstanceParam name="rub1" type="text" value="Blog" --> 
</head>
<body>
<div class="pageHeader">
<div class="pageHeaderContent">

<!-- InstanceBeginEditable name="NavBar2" -->
<?php // --------------------------- BLOG LIST INCLUDED HERE -----------------------------
	# this is what will start and end your blog links
	$blog_list_start = '<div class="NavBar">';				
	$blog_list_end = '</div>';				
	# this is what will separate your blog links
	$blog_item_start = '';				
	$blog_item_end = '';
	# This is the class of for the selected blog link:
	$blog_selected_link_class = 'NavButton2Curr';
	# This is the class of for the other blog links:
	$blog_other_link_class = 'NavButton2';
	# This is additionnal markup before and after the selected blog name
	$blog_selected_name_before = '<span class="small"><img src="'. $baseurl.'/img/down_small.gif" width="14" height="12" border="0" alt="['.T_('Selected').']" title="" class="top" />';				
	$blog_selected_name_after = '</span>';
	# This is additionnal markup before and after the other blog names
	$blog_other_name_before = '<span class="small">';				
	$blog_other_name_after = '</span>';
	// Include the bloglist
	require( dirname(__FILE__)."/_bloglist.php"); 
	// ---------------------------------- END OF BLOG LIST --------------------------------- ?>
<!-- InstanceEndEditable -->

<div class="NavBar">
<div id="Logo">&nbsp;</div>
<div class="pageTitle">
<h1 id="pageTitle"><!-- InstanceBeginEditable name="PageTitle" --><?php bloginfo('name', 'htmlbody') ?><!-- InstanceEndEditable --></h1>
</div>
</div>

<div class="pageHeaderEnd"></div>
	  
</div>
</div>


<div class="pageSubTitle"><!-- InstanceBeginEditable name="SubTitle" --><?php bloginfo('tagline', 'htmlbody') ?><!-- InstanceEndEditable --></div>


<div class="main"><!-- InstanceBeginEditable name="Main" -->
<div class="bPosts">
<h2><?php
	single_cat_title();
	single_month_title();
	single_post_title();
	switch( $disp )
	{
		case 'comments': echo T_('Last comments'); break;
		case 'stats': echo T_('Statistics'); break;
		case 'arcdir': echo T_('Archive Directory'); break;
	}
?></h2>

<!-- =================================== START OF MAIN AREA =================================== -->

<?php	// ------------------------------------ START OF POSTS ----------------------------------------
	if( isset($MainList) ) while( $MainList->get_item() )
{
the_date( '', '<h2>', '</h2>' );
?>
	<div class="bPost" lang="<?php the_lang() ?>">
		<?php permalink_anchor(); ?>
		<div class="bSmallHead">
		<a href="<?php permalink_link() ?>" title="Permanent link to full entry"><img src="img/icon_minipost.gif" alt="Permalink" width="12" height="9" class="middle" /></a>
		<?php the_time();  echo ', ', T_('Categories'), ': ';  the_categories() ?>
		</div>
		<h3 class="bTitle"><?php the_title(); ?></h3>
		<div class="bText">
		  <?php the_content(); ?>
		  <?php link_pages("<br />Pages: ","<br />","number") ?>
		</div>
		<div class="bSmallPrint">
		<a href="<?php permalink_link() ?>#comments" title="Display comments / Leave a comment"><?php comments_number() ?></a>
		-
		<a href="<?php permalink_link() ?>#trackbacks" title="Display trackbacks / Get trackback address for this post"><?php trackback_number('', '') ?></a>
		<?php trackback_rdf() // trackback autodiscovery information ?>
		-
		<a href="<?php permalink_link() ?>#comments" title="Display pingbacks"><?php pingback_number() ?></a>
		-
		<a href="<?php permalink_link() ?>" title="Permanent link to full entry">Permalink</a>
		<?php if( $debug==1 ) printf( T_('- %d queries so far'), $querycount); ?>
		</div>
		<?php	// ---------------- START OF INCLUDE FOR COMMENTS, TRACKBACK, PINGBACK, ETC. ----------------
		$disp_comments = 1;					// Display the comments if requested
		$disp_comment_form = 1;			// Display the comments form if comments requested
		$disp_trackbacks = 1;				// Display the trackbacks if requested

		$disp_trackback_url = 1;		// Display the trackbal URL if trackbacks requested
		$disp_pingbacks = 1;				// Display the pingbacks if requested
		require( dirname(__FILE__)."/_feedback.php");
		// ------------------- END OF INCLUDE FOR COMMENTS, TRACKBACK, PINGBACK, ETC. ------------------- ?>
	</div>
<?php } // ---------------------------------- END OF POSTS ------------------------------------ ?> 

	<p class="center"><strong><?php posts_nav_link(); ?></strong></p>

<?php // ---------------- START OF INCLUDES FOR LAST COMMENTS, STATS ETC. ----------------

	// this includes the last comments if requested:
	require( dirname(__FILE__)."/_lastcomments.php");

	// this includes the statistics if requested:
	require( dirname(__FILE__)."/_stats.php");

	// this includes the archive directory if requested
	require( dirname(__FILE__)."/_arcdir.php");

// ------------------- END OF INCLUDES FOR LAST COMMENTS, STATS ETC. ------------------- ?>
</div>

<!-- =================================== START OF SIDEBAR =================================== -->

<div class="bSideBar">

	<div class="bSideItem">
	  <h3><?php bloginfo('name', 'htmlbody') ?></h3>
	  <p><?php bloginfo('longdesc', 'htmlbody'); ?></p>
		<p class="center"><strong><?php posts_nav_link(); ?></strong></p>
		<!--?php next_post(); // activate this if you want a link to the next post in single page mode ?-->
		<!--?php previous_post(); // activate this if you want a link to the previous post in single page mode ?-->
		<ul>
	  	<li><a href="<?php bloginfo('staticurl', 'raw') ?>"><strong><?php echo T_('Recently') ?></strong></a> <span class="dimmed"><?php echo T_('(cached)') ?></span></li>
	  	<li><a href="<?php bloginfo('dynurl', 'raw') ?>"><strong><?php echo T_('Recently') ?></strong></a> <span class="dimmed"><?php echo T_('(no cache)') ?></span></li>
		</ul>
		<?php	// -------------------------- CALENDAR INCLUDED HERE -----------------------------
			require( dirname(__FILE__)."/_calendar.php"); 
			// -------------------------------- END OF CALENDAR ---------------------------------- ?>
		<ul>
	  	<li><a href="<?php bloginfo('lastcommentsurl') ?>"><strong><?php echo T_('Last comments') ?></strong></a></li>
	  	<li><a href="<?php bloginfo('blogstatsurl') ?>"><strong><?php echo T_('Some viewing statistics') ?></strong></a></li>
		</ul>
	</div>
	
	<div class="bSideItem">
    <h3 class="sideItemTitle"><?php echo T_('Search') ?></h3>
		<form name="searchform" method="get" class="search" action="<?php bloginfo('blogurl', 'raw') ?>">
				<input type="text" name="s" size="30" value="<?php echo $s ?>" class="s1" />
				<input type="radio" name="sentence" value="AND" id="sentAND" <?php if( $sentence=='AND' ) echo 'checked="checked" ' ?>/><label for="sentAND"><?php echo T_('All Words') ?></label>
				<input type="radio" name="sentence" value="OR" id="sentOR" <?php if( $sentence=='OR' ) echo 'checked="checked" ' ?>/><label for="sentOR"><?php echo T_('Some Word') ?></label>
				<input type="radio" name="sentence" value="sentence" id="sentence" <?php if( $sentence=='sentence' ) echo 'checked="checked" ' ?>/><label for="sentence"><?php echo T_('Sentence') ?></label>
			<input type="submit" name="submit" value="<?php echo T_('Search') ?>" />
			<input type="reset" value="<?php echo T_('Reset form') ?>" />
		</form>
	</div>

	<div class="bSideItem">
		<h3><?php echo T_('Categories') ?></h3>
		<form action="<?php bloginfo('blogurl', 'raw') ?>" method="get">
		<?php	// -------------------------- CATEGORIES INCLUDED HERE -----------------------------
			require( dirname(__FILE__)."/_categories.php"); 
			// -------------------------------- END OF CATEGORIES ---------------------------------- ?>
		<br />
		<input type="submit" value="<?php echo T_('Get selection') ?>" />
		<input type="reset" value="<?php echo T_('Reset form') ?>" />
		</form>
	</div>

	<div class="bSideItem">
    <h3>Archives</h3>
    <ul>
			<?php	// -------------------------- ARCHIVES INCLUDED HERE -----------------------------
				require( dirname(__FILE__)."/_archives.php"); 
				// -------------------------------- END OF ARCHIVES ---------------------------------- ?>
				<li><a href="<?php bloginfo('blogurl') ?>?disp=arcdir"><?php echo T_('more...') ?></a></li>
	  </ul>
  </div>

	<?php if (! $stats) 
	{ ?>
<div class="bSideItem">
		<h3><?php echo T_('Recent Referers') ?></h3>
			<?php refererList(5,'global'); ?>
	  	<ul>
				<?php while($row_stats = mysql_fetch_array($res_stats)){  ?>
					<li><a href="<?php stats_referer() ?>"><?php stats_basedomain() ?></a></li>
				<?php } // End stat loop ?>
				<li><a href="<?php bloginfo('blogstatsurl') ?>"><?php echo T_('more...') ?></a></li>
			</ul>
		<br />
		<h3><?php echo T_('Top Referers') ?></h3>
			<?php refererList(5,'global',0,0,'no','baseDomain'); ?>
	   	<ul>
				<?php while($row_stats = mysql_fetch_array($res_stats)){  ?>
					<li><a href="<?php stats_referer() ?>"><?php stats_basedomain() ?></a></li>
				<?php } // End stat loop ?>
				<li><a href="<?php bloginfo('blogstatsurl') ?>"><?php echo T_('more...') ?></a></li>
			</ul>
</div>

	<?php } ?>


	<div class="bSideItem">
    <h3><?php echo T_('Blogroll') ?></h3>
		<?php	// -------------------------- BLOGROLL INCLUDED HERE -----------------------------
			require( dirname(__FILE__)."/_blogroll.php"); 
			// -------------------------------- END OF BLOGROLL ---------------------------------- ?>
	</div>

	<div class="bSideItem">
    <h3><?php echo T_('Misc') ?></h3>
		<ul>  
			<li><a href="<?php echo $pathserver?>/b2login.php"><?php echo T_('Login...') ?></a></li>
			<li><a href="<?php echo $pathserver?>/b2register.php"><?php echo T_('Register...') ?></a></li>
		</ul>	
	</div>

	<div class="bSideItem">
    <h3><?php echo T_('Syndicate this blog') ?> <img src="<?php echo $baseurl; ?>/img/xml.gif" alt="XML" width="36" height="14" class="middle" /></h3>


      <ul>
        <li><a href="<?php bloginfo('rss_url', 'raw'); ?>">RSS 0.92 (Userland)</a></li>
        <li><a href="<?php bloginfo('rdf_url', 'raw'); ?>">RSS 1.0 (RDF)</a></li>
        <li><a href="<?php bloginfo('rss2_url', 'raw'); ?>">RSS 2.0 (Userland)</a></li>
      </ul>
      <p><a href="http://www.xml.com/pub/a/2002/12/18/dive-into-xml.html" title="xml.com - External - English">What
        is RSS?</a> by Mark Pilgrim</p>

	</div>

	<p class="center">powered by<br />
	<a href="http://b2evolution.net/" title="b2evolution home"><img src="<?php echo $baseurl; ?>/img/b2evolution_button.png" alt="b2evolution" width="80" height="15" border="0" class="middle" /></a></p>

</div>
<!-- InstanceEndEditable --></div>
<table cellspacing="3" class="wide">
  <tr> 
  <td class="cartouche">Original page design by <a href="http://fplanque.net/">Fran&ccedil;ois PLANQUE</a> </td>
    
	<td class="cartouche" align="right"> <a href="http://b2evolution.net/" title="b2evolution home"><img src="img/b2evolution_button.png" alt="b2evolution" width="80" height="15" border="0" class="middle" /></a></td>
  </tr>
</table>
<p class="baseline"> <a href="http://validator.w3.org/check/referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0!" class="middle" /></a> 
  <a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" class="middle" /></a>&nbsp;<!-- InstanceBeginEditable name="Baseline" -->
<?php // $blog=1;  fplanque: removed
	log_hit();	// log the hit on this page
	if ($debug==1)
	{
		printf( T_('Totals: %d posts - %d queries - %01.3f seconds'), $result_num_rows, $querycount, timer_stop() );
	}
?>
<!-- InstanceEndEditable --></p>
</body>
<!-- InstanceEnd --></html>
