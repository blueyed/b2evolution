<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="<?php locale_lang() ?>" lang="<?php locale_lang() ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php locale_charset() ?>" />
	<title>b2evo &gt; <?php echo $title; ?></title>
	<link href="b2.css" rel="stylesheet" type="text/css" />
	<link href="blog.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" language="javascript">
		<!--
		function profile(userID) 
		{
			window.open ("b2profile.php?action=viewprofile&user="+userID, "Profile", "width=500, height=450, location=0, menubar=1, resizable=1, scrollbars=1, status=1, titlebar=0, toolbar=0, screenX=60, left=60, screenY=60, top=60");
		}
	<?php 
	if ($use_spellchecker) 
	{ // --------------------------- SPELL CHECKER -------------------------------
		?>
		function DoSpell(formname, subject, body)
		{
			document.SPELLDATA.formname.value=formname
			document.SPELLDATA.subjectname.value=subject
			document.SPELLDATA.messagebodyname.value=body
			document.SPELLDATA.companyID.value="custom\\http://cafelog.com"
			document.SPELLDATA.language.value=1033
			document.SPELLDATA.opener.value="<?php echo $pathserver ?>/sproxy.pl"
			document.SPELLDATA.formaction.value="http://www.spellchecker.com/spell/startspelling.asp "
			window.open("<?php echo $pathserver ?>/b2spell.php","Spell","toolbar=no,directories=no,location=yes,resizable=yes,width=620,height=400,top=100,left=100")
		}
	<?php
	}
	if ($redirect==1) 
	{ // --------------------------- REDIRECT -------------------------------
	?>
		function redirect() {
			window.location = "<?php echo $redirect_url; ?>";
		}
		setTimeout("redirect();", 600);
	<?php
	}
	if( $blog )
	{ // --------------------------- PREVIEW -------------------------------
	?>
		/*
		 * open_preview()
		 * fplanque: created
		 */
		function open_preview(form) 
		{
			// Stupid thing: having a field called action !
			var saved_action =  form.attributes.getNamedItem('action').value;
			form.attributes.getNamedItem('action').value = '<?php bloginfo('dynurl') ?>';
			form.target = 'b2evo_preview';
			form.submit();
			preview_window = window.open( '', 'b2evo_preview' );
			preview_window.focus();
			form.attributes.getNamedItem('action').value = saved_action;
			form.target = '_self';
		}
	
		function launchupload() 
		{
			window.open ("b2upload.php", "b2upload", "width=380,height=360,location=0,menubar=0,resizable=1,scrollbars=yes,status=1,toolbar=0");
		}
	<?php 
	}
	?>
	
		// End -->
	</script>
</head>
<body>

<?php
if ($profile==0) 
{
?>
<img src="img/blank.gif" width="1" height="5" alt="" border="0" />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr height="15">

<td height="15" width="20"><img src="img/blank.gif" width="1" height="1" alt="" /></td>

<td rowspan="3" width="50" valign="top"><a href="http://b2evolution.net/"><img src="img/b2minilogo.png" width="50" height="50" border="0" alt="visit b2evolution's website" style="border-width:1px; border-color: #999999; border-style: dashed" /></a></td>

<td><strong><span style="color:#333333">e</span><span style="color:#554433">v</span><span style="color:#775522">o</span><span style="color:#996622">l</span><span style="color:#bb7722">u</span><span style="color:#cc8811">t</span><span style="color:#dd9911">i</span><span style="color:#ee9900">o</span><span style="color:#ff9900">n</span></strong> <?php echo $b2_version ?></td>
<td width="150" style="text-align: right; padding-rightt: 6px;">
<span style="color: #b0b0b0; font-family: verdana, arial, helvetica; font-size: 10px;"><?php echo T_('logged in as:') ?> <strong><?php echo $user_login; ?></strong></span>
</td>

</tr>
<tr>

<td class="menutop" width="20">&nbsp;
</td>

<td class="menutop"<?php if ($is_NS4) { echo " width=\"500\""; } ?>>
<div class="menutop"<?php if ($is_NS4) { echo " width=\"500\""; } ?>>
&nbsp;
<?php 
	echo '<a href="b2edit.php?action=new&blog=', $blog, '" class="menutop" style="font-weight: bold;">', T_('New Post'), '</a>';
	echo " | \n";
	echo '<a href="b2edit.php?blog=', $blog, '" class="menutop" style="font-weight: bold;">', T_('Browse/Edit'), '</a>';
	if($user_level >= 9) 
	{
		echo " | \n";
		echo '<a href="b2stats.php" class="menutop">', T_('Stats'), '</a>';
	}
	if($user_level >= 3) 
	{
		echo " | \n";
		echo '<a href="b2categories.php" class="menutop">', T_('Cats'), '</a>';
	}
	if($user_level >= 9) 
	{
		echo " | \n";
		echo '<a href="b2blogs.php" class="menutop">', T_('Blogs'), '</a>';
	}
	if($user_level >= 9) 
	{
		echo " | \n";
		echo '<a href="b2options.php" class="menutop">', T_('Options'), '</a>';
	}
	if($user_level >= 3) 
	{
		echo " | \n";
		echo '<a href="b2template.php" class="menutop">', T_('Templates'), '</a>';
	}
	echo " | \n";
	echo '<a href="b2team.php" class="menutop">', T_('Users'), '</a>';
	echo " | \n";
	echo '<a href="javascript:profile(', $user_ID, ')" class="menutop">', T_('My Profile'), '</a>';
?>
</div>
</td>

<td class="menutop" align="right" bgcolor="#FF9900">
<a href="<?php echo $pathadmin_out ?>" class="menutop"><?php echo T_('Exit to blogs') ?></a>
|
<a href="b2login.php?action=logout" class="menutop"><?php echo T_('Logout') ?></a>
&nbsp;
</td>

</tr>
<tr>

<td>&nbsp;</td>
<td style="padding-left: 6px;" colspan="2">
	<span class="menutoptitle">:: <?php echo $title; ?></span>
<?php
}
?>

