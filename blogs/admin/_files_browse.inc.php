<?php
/**
 * This file implements the UI for file browsing.
 *
 * This file is part of the b2evolution/evocms project - {@link http://b2evolution.net/}.
 * See also {@link http://sourceforge.net/projects/evocms/}.
 *
 * @copyright (c)2003-2005 by Francois PLANQUE - {@link http://fplanque.net/}.
 * Parts of this file are copyright (c)2004-2005 by Daniel HAHLER - {@link http://thequod.de/contact}.
 *
 * @license http://b2evolution.net/about/license.html GNU General Public License (GPL)
 * {@internal
 * b2evolution is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * b2evolution is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with b2evolution; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * }}
 *
 * {@internal
 * Daniel HAHLER grants Francois PLANQUE the right to license
 * Daniel HAHLER's contributions to this file and the b2evolution project
 * under any OSI approved OSS license (http://www.opensource.org/licenses/).
 * }}
 *
 * @package admin
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author blueyed: Daniel HAHLER.
 * @author fplanque: Francois PLANQUE.
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );


// Begin payload block:
$AdminUI->disp_payload_begin();


?>

<!-- FLAT MODE: -->

<form action="files.php#FM_anchor" id="fmbar_flatmode" class="toolbaritem">
	<?php
	echo $Fileman->getFormHiddenInputs();
	echo $Fileman->getFormHiddenSelectedFiles();
	?>
	<input name="actionArray[<?php echo $Fileman->flatmode ? 'noflatmode' : 'flatmode' ?>]"
		class="ActionButton"
		type="submit"
		title="<?php
			echo format_to_output( $Fileman->flatmode ?
															T_('Normal mode') :
															T_('All files and folders, including subdirectories'), 'formvalue' );
			?>"
		onclick="this.form.action='files.php#FM_anchor';"
		value="<?php
			echo format_to_output( $Fileman->flatmode ?
															T_('Normal mode') :
															T_('Flat mode'), 'formvalue' ); ?>" />
</form>


<!-- FILTER BOX: -->

<?php
// Title for checkbox and its label
$titleRegExp = format_to_output( T_('Filter is a regular expression'), 'formvalue' );
?>

<form action="files.php#FM_anchor" id="fmbar_filter" class="toolbaritem">
	<?php
	echo $Fileman->getFormHiddenInputs( array( 'filterString'=>'', 'filterIsRegexp'=>'' ) );
	echo $Fileman->getFormHiddenSelectedFiles();
	?>
	<label for="filterString" id="filterString" class="tooltitle"><?php echo T_('Filter') ?>:</label>
	<input type="text"
		name="filterString"
		value="<?php echo format_to_output( $Fileman->get_filter( false ), 'formvalue' ) ?>"
		size="7"
		accesskey="f" />
	<input type="checkbox" class="checkbox" name="filterIsRegexp" id="filterIsRegexp" title="<?php echo $titleRegExp; ?>"
		value="1"<?php if( $Fileman->is_filter_regexp() ) echo ' checked="checked"' ?> />
	<label for="filterIsRegexp" title="<?php echo $titleRegExp; ?>"><?php
		echo /* TRANS: short for "is regular expression" */ T_('RegExp'); ?></label>

	<input name="actionArray[filter]"
		class="ActionButton"
		type="submit"
		value="<?php echo format_to_output( T_('Apply'), 'formvalue' ) ?>" />

	<?php
	if( $Fileman->is_filtering() )
	{ // "reset filter" form
		?>
		<input value="<?php echo T_('Unset filter'); ?>"
			title="<?php echo T_('Unset filter'); ?>"
			type="image"
			name="actionArray[filter_unset]"
			class="ActionButton"
			src="<?php echo get_icon( 'delete', 'url' ) ?>" />
		<?php
	}
	?>
</form>


<?php /* Not implemented yet:

<!-- SEARCH BOX: -->

<form class="toolbaritem" id="fmbar_search">
	<input type="text" name="searchfor" value="--todo--" size="20" />
	<input type="image"
		class="ActionButton"
		title="<?php echo format_to_output( T_('Search'), 'formvalue' ) ?>"
		src="<glasses>" />
</form>

*/ ?>


<!-- THE MAIN FORM -->

<form action="files.php#FM_anchor" name="FilesForm" id="FilesForm" method="post">
<input type="hidden" name="confirmed" value="0" />
<input type="hidden" name="md5_filelist" value="<?php echo $Fileman->md5_checksum() ?>" />
<input type="hidden" name="md5_cwd" value="<?php echo md5($Fileman->get_ads_list_path()) ?>" />
<?php echo $Fileman->getFormHiddenInputs(); ?>


<table class="grouped clear" cellspacing="0">

<?php
/**
 * @global integer Number of cols for the files table, 8 by default
 */
if( $Fileman->flatmode )
{
	$filetable_cols = 9;
}
else
{
	$filetable_cols = 8;
}

?>

<thead>
<tr>
	<td colspan="<?php echo $filetable_cols ?>" class="firstcol lastcol">

		<?php
		/*
		 * -----------------------------------------------
		 * Display ROOTs list:
		 * -----------------------------------------------
		 */
		$rootlist = $Fileman->getRootList();
		if( count($rootlist) > 1 )
		{ // provide list of roots to choose from
			echo '<div id="fmbar_roots">';
			echo '<select name="new_root" onchange="this.form.submit();">';
			foreach( $rootlist as $lroot )
			{
				echo '<option value="'.$lroot['id'].'"';

				if( $Fileman->root == $lroot['id'] )
				{
					echo ' selected="selected"';
				}

				echo '>'.format_to_output( $lroot['name'] )."</option>\n";
			}
			echo '</select> ';
			echo '<input class="ActionButton" type="submit" value="'.T_('Change root').'" />';
			echo '</div>';
		}
		?>


		<div id="fmbar_cwd">
			<?php
			// -----------------------------------------------
			// Display table header: directory location info:
			// -----------------------------------------------

			// Display current dir:
			echo T_('Current dir').': <strong class="currentdir">'.$Fileman->getCwdClickable().'</strong>';


			// Display current filter:
			if( $Fileman->is_filtering() )
			{
				echo '[<em class="filter">'.$Fileman->get_filter().'</em>]';
				// TODO: maybe clicking on the filter should open a JS popup saying "Remove filter [...]? Yes|No"
			}


			// The hidden reload button
			?>
			<span style="display:none;" id="fm_reloadhint">
				<a href="<?php echo $Fileman->getCurUrl() ?>"
					title="<?php echo T_('A popup has discovered that the displayed content of this window is not up to date. Click to reload.'); ?>">
					<?php echo get_icon( 'reload' ) ?>
				</a>
			</span>

			<?php
			// Display filecounts:
			?>

			<span class="fm_filecounts" title="<?php printf( T_('%s bytes'), number_format($Fileman->count_bytes()) ); ?>"> (<?php
			disp_cond( $Fileman->count_dirs(), T_('One directory'), T_('%d directories'), T_('No directories') );
			echo ', ';
			disp_cond( $Fileman->count_files(), T_('One file'), T_('%d files'), T_('No files' ) );
			echo ', '.bytesreadable( $Fileman->count_bytes() );
			?>
			)</span>
		</div>
	</td>

</tr>

<?php
	/*
	 * Col headers:
	 */
	echo '<tr>';
	echo '<th colspan="2" class="firstcol">';
	$Fileman->dispButtonParent();
	echo '</th>';
	if( $Fileman->flatmode )
	{
		echo '<th>'.$Fileman->getLinkSort( 'path', /* TRANS: file/directory path */ T_('Path') ).'</th>';
	}
	echo '<th class="nowrap">'.$Fileman->getLinkSort( 'name', /* TRANS: file name */ T_('Name') ).'</th>';
	echo '<th class="nowrap">'.$Fileman->getLinkSort( 'type', /* TRANS: file type */ T_('Type') ).'</th>';
	echo '<th class="nowrap">'.$Fileman->getLinkSort( 'size', /* TRANS: file size */ T_('Size') ).'</th>';
	echo '<th class="nowrap">'.$Fileman->getLinkSort( 'lastmod', /* TRANS: file's last change / timestamp */ T_('Last change') ).'</th>';
	echo '<th class="nowrap">'.$Fileman->getLinkSort( 'perms', /* TRANS: file's permissions (short) */ T_('Perms') ).'</th>';
	echo '<th class="lastcol nowrap">'. /* TRANS: file actions; edit, rename, copy, .. */ T_('Actions').'</th>';
	echo '</tr>';
?>

</thead>


<tbody>

<?php
param( 'checkall', 'integer', 0 );  // Non-Javascript-CheckAll

/*
 * ---------------------------------------------
 * MAIN FILE LIST:
 * ---------------------------------------------
 */
$Fileman->sort();
$countFiles = 0;
while( $lFile = & $Fileman->get_next() )
{ // loop through all Files:
	echo '<tr';

	if( $countFiles%2 ) echo ' class="odd"';

	// select the file by default, when clicking on the <tr>; this gets "undone" for the checkbox itself and the filename link
	echo ' onclick="var cb = document.getElementById(\'cb_filename_'.$countFiles.'\'); cb.checked = (! cb.checked);">';

	/*
	 * Checkbox:
	 */
	echo '<td class="checkbox firstcol">';
	echo '<input title="'.T_('Select this file').'" type="checkbox" class="checkbox"
				name="fm_selected[]" value="'.$lFile->get_md5_ID().'" id="cb_filename_'.$countFiles.'"
				onclick="this.checked = ! this.checked;"'; // undo the <tr>'s onclick checking of this box
	if( $checkall || $Fileman->isSelected( $lFile ) )
	{
		echo ' checked="checked"';
	}
	echo ' />';

	/*
	 * Hidden info used by Javascript:
	 */
	if( $mode == 'upload' )
	{
		echo '<input type="hidden" name="img_tag_'.$countFiles.'" id="img_tag_'.$countFiles
						.'" value="'.format_to_output( $lFile->get_tag(), 'formvalue' ).'">';
	}

	echo '</td>';


	/*
	 * File type Icon:
	 */
	echo '<td class="icon">';
	echo '<a href="';
	if( $lFile->is_dir() )
	{
		echo $Fileman->getLinkFile( $lFile ).'" title="'.T_('Change into this directory');
	}
	else
	{
		echo $Fileman->getFileUrl().'" title="'.T_('Let the browser handle this file');
	}
	echo '">'.$lFile->get_icon().'</a>';
	echo '</td>';

	/*
	 * Path (flatmode):
	 */
	if( $Fileman->flatmode )
	{
		echo '<td class="filepath">';
		echo $lFile->get_rdfs_rel_path();
		echo '</td>';
	}


	echo '<td class="filename">';

	/*
	 * Open in new window:
	 */
	echo '<a href="'.$Fileman->getLinkFile( $lFile ).'" target="fileman_default"
				title="'.T_('Open in a new window').'" onclick="return false;">';
	$imgsize = $lFile->get_image_size( 'widthheight' );
	echo '<button class="filenameIcon" type="button" id="button_new_'.$countFiles.'" onclick="'
				.$Fileman->getJsPopupCode( NULL,
							"'+( typeof(fm_popup_type) == 'undefined' ? 'fileman_default' : 'fileman_popup_$countFiles')+'",
							($imgsize ? $imgsize[0]+100 : NULL), ($imgsize ? $imgsize[1]+150 : NULL) )
				.'">'.get_icon( 'window_new' ).'</button></a>';

	/*
	 * Invalid filename warning:
	 */
	if( !isFilename( $lFile->get_name() ) )
	{ // TODO: Warning icon with hint
		echo get_icon( 'warning', 'imgtag', array( 'class' => 'filenameIcon', 'title' => T_('The filename appears to be invalid and may cause problems.') ) );
	}

	/*
	 * Link ("chain") icon:
	 */
	if( $Fileman->fm_mode == 'link_item'
			// Plug extensions here!
		)
	{	// Offer option to link the file to an Item:
		$Fileman->dispButtonFileLink();
		echo ' ';
	}

	/*
	 * Filename:
	 */
	echo '<a href="'.$Fileman->getLinkFile( $lFile ).'" onclick="var cb = document.getElementById(\'cb_filename_'.$countFiles.'\'); cb.checked = (! cb.checked);">';
	/*
	if( $Fileman->flatmode && $Fileman->get_sort_order() != 'name' )
	{	// Display directory name
		echo './'.$lFile->get_rdfs_rel_path();
	}
	else
	*/
	{	// Display file short name
		echo $lFile->get_name();
	}
	echo '</a>';

	/*
	 * File meta data:
	 */
	echo '<span class="filemeta">';
	// Optionnaly display IMAGE pixel size:
	disp_cond( $Fileman->getFileImageSize(), ' (%s)' );
	// Optionnaly display meta data title:
	if( $lFile->meta == 'loaded' )
	{	// We have loaded meta data for this file:
		echo ' - '.$lFile->title;
	}
	echo '</span>';

	/*
	 * Directory in flat mode:
	 *
	if( $Fileman->flatmode && $Fileman->get_sort_order() == 'name' )
	{
		?>
		<div class="path" title="<?php echo T_('The directory of the file') ?>"><?php
		$subPath = $lFile->get_rdfs_rel_path();
		if( empty( $subPath ) )
		{
			$subPath = './';
		}
		echo $subPath;
		?>
		</div>
		<?php
	}
	*/
	echo '</td>';

	/*
	 * File type:
	 */
	echo '<td class="type">'.$lFile->get_type().'</td>';

	/*
	 * File size:
	 */
	echo '<td class="size">'.$lFile->get_size_formatted().'</td>';

	/*
	 * File time stamp:
	 */
	echo '<td class="timestamp">';
	echo '<span class="date">'.$lFile->get_lastmod_formatted( 'date' ).'</span> ';
	echo '<span class="time">'.$lFile->get_lastmod_formatted( 'time' ).'</span>';
	echo '</td>';

	/*
	 * File permissions:
	 */
	echo '<td class="perms">';
	$Fileman->dispButtonFileEditPerms();
	echo '</td>';

	/*
	 * Action icons:
	 */
	echo '<td class="actions lastcol">';
	// Not implemented yet: $Fileman->dispButtonFileEdit();
	$Fileman->dispButtonFileProperties();

	if( $current_User->check_perm( 'files', 'edit' ) )
	{ // User can edit:

		// Rename (NEW):
		echo '<a title="'.T_('Rename').'" href="'.$Fileman->getLinkFile( $Fileman->curFile, 'rename' ).'">'.get_icon( 'file_rename' ).'</a>';

		$Fileman->dispButtonFileMove();
		$Fileman->dispButtonFileCopy();
		$Fileman->dispButtonFileDelete();
	}
	echo '</td>';

	echo '</tr>';

	$countFiles++;
}
// / End of file list..

if( $countFiles == 0 )
{ // Filelist errors or "directory is empty"
	?>

	<tr>
		<td colspan="<?php echo $filetable_cols ?>">
			<?php
				if( !$Messages->count( 'fl_error' ) )
				{ // no Filelist errors, the directory must be empty
					$Messages->add( T_('No files found.')
						.( $Fileman->is_filtering() ? '<br />'.T_('Filter').': &laquo;'.$Fileman->get_filter().'&raquo;' : '' ), 'fl_error' );
				}
				$Messages->display( '', '', true, 'fl_error', 'log_error' );
			?>
		</td>
	</tr>

	<?php
}
else
{
	// -------------
	// Footer with "check all", "with selected: ..":
	// --------------
	?>
	<tr class="listfooter">
		<td colspan="<?php echo $filetable_cols ?>">
		<a id="checkallspan_0" href="<?php
			echo url_add_param( $Fileman->getCurUrl(), 'checkall='.( $checkall ? '0' : '1' ) );
			?>" onclick="toggleCheckboxes('FilesForm', 'fm_selected[]'); return false;"><?php
			echo ($checkall) ? T_('uncheck all') : T_('check all');
			?></a>
		&mdash; <strong><?php echo T_('With selected files:') ?> </strong>

		<input class="ActionButton"
			title="<?php echo T_('Open in new windows'); ?>"
			name="actionArray[open_in_new_windows]"
			value="open_in_new_windows"
			type="image"
			src="<?php echo get_icon( 'window_new', 'url' ) ?>"
			onclick="openselectedfiles(); return false;" />

		<?php
		if( $mode == 'upload' )
		{	// We are uploading in a popup opened by an edit screen
			?>
			<input class="ActionButton"
				title="<?php echo T_('Insert IMG tags for selected files'); ?>"
				name="actionArray[img_tag]"
				value="img"
				type="submit"
				onclick="insert_tag_for_selected_files(); return false;" />
			<?php
		}


		if( $current_User->check_perm( 'files', 'edit' ) )
		{ // User can edit:
			?>
			<input class="ActionButton" type="image" name="actionArray[rename]"
				title="<?php echo T_('Rename the selected files'); ?>"
				src="<?php echo get_icon( 'file_rename', 'url' ); ?>" />

			<input class="DeleteButton" type="image" name="actionArray[delete]"
				title="<?php echo T_('Delete the selected files') ?>"
				src="<?php echo get_icon( 'file_delete', 'url' ) ?>" />
			<?php
			// NOTE: No delete confirmation by javascript, we need to check DB integrity!

		}

		/*
			<!-- Not implemented yet: input class="ActionButton"
				title="<?php echo T_('Download the selected files') ?>"
				name="actionArray[download]"
				value="download"
				type="image"
				src="<?php echo get_icon( 'download', 'url' ) ?>"
				onclick="return openselectedfiles(true);" / -->

			<!-- Not implemented yet: input class="ActionButton" type="submit"
				name="actionArray[sendbymail]" value="<?php echo T_('Send by mail') ?>" onclick="return openselectedfiles(true);" / -->


		/* Not fully functional:
		<input class="ActionButton" type="image" name="actionArray[file_copy]"
			title="<?php echo T_('Copy the selected files'); ?>"
			onclick="return openselectedfiles(true);"
			src="<?php echo get_icon( 'file_copy', 'url' ); ?>" />

		<input class="ActionButton" type="image" name="actionArray[file_move]"
			title="<?php echo T_('Move the selected files'); ?>"
			onclick="return openselectedfiles(true);"
			src="<?php echo get_icon( 'file_move', 'url' ); ?>" />
// This is too geeky! Default perms radio options and unchecked radio groups! NO WAY!
// If you want this feature to be usable by average users you must only have one line per file OR one file for all. You can't mix both.
// The only way to have both is to have 2 spearate forms: 1 titled "change perms for all files simultaneously"-> submit  and another 1 title "change perms for each file individually" -> another submit
// POST PHOENIX
// fplanque>> second thought: changing perms for multiple files at once is useful. BUT assigning different perms to several files with ONE form is trying to solve a problem that not even geeks can face once in a lifetime.
// This has to be simplified to ONE single set of permissions for all selected files. (If you need different perms, click again)
			<input class="ActionButton" type="image" name="actionArray[edit_perms]"
			onclick="return openselectedfiles(true);"
			title="<?php echo T_('Change permissions for the selected files'); ?>"
			src="<?php echo get_icon( 'file_perms', 'url' ); ?>" />
	*/ ?>

		</td>
	</tr>
	<?php
}
?>
</tbody>

</table>

</form>


<?php
if( $countFiles )
{{{ // include JS
	// TODO: remove these javascript functions to an external .js file and include them through $AdminUI->add_headline()
	?>
	<script type="text/javascript">
		<!--
		/**
		 * Open selected files in new popup windows:
		 */
		function openselectedfiles( checkonly )
		{
			elems = document.getElementsByName( 'fm_selected[]' );
			fm_popup_type = 'selected';
			var opened = 0;
			for( i = 0; i < elems.length; i++ )
			{
				if( elems[i].checked )
				{
					if( !checkonly )
					{
						id = elems[i].id.substring( elems[i].id.lastIndexOf('_')+1, elems[i].id.length );
						document.getElementById( 'button_new_'+id ).click();
					}
					opened++;
				}
			}
			if( !opened )
			{
				alert( '<?php echo TS_('Nothing selected.') ?>' );
				return false;
			}
			else
			{
				return true;
			}
		}

		/**
		 * Insert IMG tags into parent window for selected files:
		 */
		function insert_tag_for_selected_files()
		{
			elems = document.getElementsByName( 'fm_selected[]' );
			fm_popup_type = 'selected';
			var snippet = '';
			for( i = 0; i < elems.length; i++ )
			{
				if( elems[i].checked )
				{
					id = elems[i].id.substring( elems[i].id.lastIndexOf('_')+1, elems[i].id.length );
					img_tag_info_field = document.getElementById( 'img_tag_'+id );
					snippet += img_tag_info_field.value + ' ';
				}
			}
			if( ! snippet.length )
			{
				alert( '<?php echo TS_('You must select at least one file!') ?>' );
				return false;
			}
			else
			{
				if (! (window.focus && window.opener))
				{
					return true;
				}
				window.opener.focus();
				textarea_replace_selection( window.opener.document.post.content, snippet );
				return true;
			}
		}
		// -->
	</script>
	<?php
}}}


/*
 * CREATE:
 */
if( ($Settings->get( 'fm_enable_create_dir' ) || $Settings->get( 'fm_enable_create_file' ))
			&& $current_User->check_perm( 'files', 'add' ) )
{ // dir or file creation is enabled and we're allowed to add files:
?>
<form action="files.php#FM_anchor" class="toolbaritem">
	<?php
		echo $Fileman->getFormHiddenInputs();
		if( ! $Settings->get( 'fm_enable_create_dir' ) )
		{	// We can create files only:
			echo T_('New file:');
			echo '<input type="hidden" name="createnew" value="file" />';
		}
		elseif( ! $Settings->get( 'fm_enable_create_file' ) )
		{	// We can create directories only:
			echo T_('New folder:');
			echo '<input type="hidden" name="createnew" value="dir" />';
		}
		else
		{	// We can create both files and directories:
			echo T_('New');
			echo '<select name="createnew">';
			echo '<option value="dir"';
			if( isset($createnew) &&  $createnew == 'dir' )
			{
				echo ' selected="selected"';
			}
			echo '>'.T_('folder').'</option>';

			echo '<option value="file"';
			if( isset($createnew) && $createnew == 'file' )
			{
				echo ' selected="selected"';
			}
			echo '>'.T_('file').'</option>';
			echo '</select>:';
		}
	?>
	<input type="text" name="createname" value="<?php
		if( isset( $createname ) )
		{
			echo $createname;
		} ?>" size="15" />
	<input class="ActionButton" type="submit" value="<?php echo format_to_output( T_('Create!'), 'formvalue' ) ?>" />
	<input type="hidden" name="action" value="createnew" />
</form>
<?php
}


/*
 * UPLOAD:
 */
if( $Settings->get('upload_enabled') && $current_User->check_perm( 'files', 'add' ) )
{	// Upload is enabled and we have permission to use it...
?>
<!-- UPLOAD: -->

<form action="files.php" method="post" class="toolbaritem">
	<div>
		<?php echo $Fileman->getFormHiddenInputs( array( 'fm_mode' => 'file_upload' ) ); ?>
		<input class="ActionButton" type="submit" value="<?php echo T_('Advanced upload...'); ?>" />
	</div>
</form>

<form enctype="multipart/form-data" action="files.php" method="post" class="toolbaritem">
	<?php form_hidden( 'upload_quickmode', 1 ); ?>
	<!-- The following is mainly a hint to the browser. -->
	<?php form_hidden( 'MAX_FILE_SIZE', $Settings->get( 'upload_maxkb' )*1024 ); ?>

	<?php echo $Fileman->getFormHiddenInputs( array( 'fm_mode' => 'file_upload' ) ); ?>

	<div>
		<input name="uploadfile[]" type="file" size="10" />
		<input class="ActionButton" type="submit" value="<?php echo T_('Quick Upload!'); ?>" />
	</div>
</form>
<?php
}
?>

<div class="clear"></div>

<fieldset>
	<legend><?php echo T_('Help') ?></legend>
	<ul>
		<li><?php echo T_('Clicking on a file icon lets the browser handle the file.'); ?></li>
		<li><?php echo T_('Clicking on a file name invokes the default action (images get displayed as image, raw content for all other files).'); ?></li>
		<li><?php printf( T_('Clicking on the %s icon invokes the default action in a new window.'), get_icon( 'window_new' ) ); ?></li>
		<li><?php echo T_('Actions'); ?>:
			<ul class="iconlegend">
				<li><?php echo get_icon( 'file_rename' ).' '.T_('Rename'); ?></li>
				<li><?php echo get_icon( 'file_copy' ).' '.T_('Copy'); ?></li>
				<li><?php echo get_icon( 'file_move' ).' '.T_('Move'); ?></li>
				<li><?php echo get_icon( 'file_delete' ).' '.T_('Delete'); ?></li>
			</ul>
		</li>
	</ul>
</fieldset>
<?php


// ------------------
// Display options:
// Let's keep these at the end since they won't be accessed more than occasionaly
// and only by advanced users
// ------------------
param( 'options_show', 'integer', 0 );

$options_Form = & new Form( 'files.php#FM_anchor', 'options_form', 'get', 'none' );
	$options_Form->label_to_the_left = false;
	$options_Form->label_suffix = '';
	$options_Form->fieldend = '<br />';
	$options_Form->begin_form( 'fform' );
	$options_Form->hidden( 'options_show', 1 );
	echo $Fileman->getFormHiddenInputs();

	// Link to toggle the display of the form
	$toggle_link = '['.get_link_showhide( 'options_toggle', 'options_list', T_('Hide menu'), T_('Show menu'), !$options_show ).']';

	$options_Form->begin_fieldset( T_('Options').$toggle_link );
	?>

	<div id="options_list"<?php if( !$options_show ) echo ' style="display:none"' ?>>
		<?php
		$options_Form->checkbox( 'option_dirsattop', !$UserSettings->get('fm_dirsnotattop'), T_('Sort directories at top') );
		$options_Form->checkbox( 'option_showhidden', $UserSettings->get('fm_showhidden'), T_('Show hidden files') );
		$options_Form->checkbox( 'option_permlikelsl', $UserSettings->get('fm_permlikelsl'), T_('Display file permissions like "rwxr-xr-x" rather than short form') );
		$options_Form->checkbox( 'option_getimagesizes', $UserSettings->get('fm_getimagesizes'), T_('Display the image size of image files') );
		$options_Form->checkbox( 'option_recursivedirsize', $UserSettings->get('fm_recursivedirsize'), T_('Recursive size of directories') );
		$options_Form->checkbox( 'option_forceFM', $UserSettings->get('fm_forceFM'), T_('Always show the Filemanager'), 'Display the Filemanager also in modes like upload.' );

		$options_Form->submit( array('actionArray[update_settings]', T_('Update !'), 'ActionButton') );
		?>
	</div>

	<?php
	$options_Form->end_fieldset();
$options_Form->end_form();
?>

</div>

<div class="clear"></div>

<?php
// End payload block:
$AdminUI->disp_payload_end();

/*
 * $Log$
 * Revision 1.60  2005/12/12 19:21:20  fplanque
 * big merge; lots of small mods; hope I didn't make to many mistakes :]
 *
 * Revision 1.59  2005/12/10 03:02:49  blueyed
 * Quick upload mode merged from post-phoenix
 *
 * Revision 1.58  2005/11/27 06:13:52  blueyed
 * Moved textarea_replace_selection() to functions.js to allow using it everywhere.
 *
 * Revision 1.57  2005/11/25 14:33:35  fplanque
 * no message
 *
 * Revision 1.56  2005/11/24 20:29:01  blueyed
 * minor changes (fixes in commented out code); allow mass-editing of file perms (again)
 *
 * Revision 1.55  2005/11/24 18:33:04  blueyed
 * Konqueror (Safari?) and Firefox fixes
 *
 * Revision 1.54  2005/11/24 08:53:59  blueyed
 * file_cmr is deprecated
 *
 * Revision 1.53  2005/11/22 04:41:38  blueyed
 * Fix permissions editing again
 *
 * Revision 1.52  2005/11/21 18:33:19  fplanque
 * Too many undiscussed changes all around: Massive rollback! :((
 * As said before, I am only taking CLEARLY labelled bugfixes.
 *
 * Revision 1.46  2005/11/18 21:01:21  fplanque
 * no message
 *
 * Revision 1.45  2005/11/14 18:08:14  blueyed
 * Fix fatal error when displaying paths in flat mode.
 *
 * Revision 1.43.2.1  2005/11/14 17:57:18  blueyed
 * The options bug was another bug (actionArray)
 *
 * Revision 1.43  2005/11/06 11:22:10  yabs
 * correcting options bug
 *
 * Revision 1.42  2005/10/30 03:51:24  blueyed
 * Refactored showhide-JS functionality.
 * Moved showhide() from the features form to functions.js, and renamed to toggle_display_by_id();
 * Moved web_help_link() to get_web_help_link() in _misc.funcs.php, doc
 *
 * Revision 1.41  2005/10/28 20:08:46  blueyed
 * Normalized AdminUI
 *
 * Revision 1.40  2005/09/26 23:06:53  blueyed
 * Converted options fieldset to Form class
 *
 * Revision 1.39  2005/09/06 17:13:53  fplanque
 * stop processing early if referer spam has been detected
 *
 * Revision 1.38  2005/08/12 17:31:06  fplanque
 * minor
 *
 * Revision 1.37  2005/08/08 18:30:48  fplanque
 * allow inserting of files as IMG or A HREFs from the filemanager
 *
 * Revision 1.36  2005/06/22 17:23:23  blueyed
 * html fix: closing <ul>
 *
 * Revision 1.35  2005/05/26 19:11:09  fplanque
 * no message
 *
 * Revision 1.34  2005/05/17 19:26:05  fplanque
 * FM: copy / move debugging
 *
 * Revision 1.33  2005/05/13 16:49:17  fplanque
 * Finished handling of multiple roots in storing file data.
 * Also removed many full paths passed through URL requests.
 * No full path should ever be seen by the user (only the admins).
 *
 * Revision 1.32  2005/05/12 18:39:24  fplanque
 * storing multi homed/relative pathnames for file meta data
 *
 * Revision 1.31  2005/05/11 15:58:30  fplanque
 * cleanup
 *
 * Revision 1.30  2005/05/09 16:09:37  fplanque
 * implemented file manager permissions through Groups
 *
 * Revision 1.29  2005/05/06 20:04:33  fplanque
 * added contribs
 * fixed filemanager settings
 *
 * Revision 1.28  2005/05/04 19:40:40  fplanque
 * cleaned up file settings a little bit
 *
 * Revision 1.27  2005/04/29 18:49:32  fplanque
 * Normalizing, doc, cleanup
 *
 * Revision 1.26  2005/04/28 20:44:18  fplanque
 * normalizing, doc
 *
 * Revision 1.25  2005/04/27 19:05:44  fplanque
 * normalizing, cleanup, documentaion
 *
 * Revision 1.22  2005/04/19 16:23:00  fplanque
 * cleanup
 * added FileCache
 * improved meta data handling
 *
 * Revision 1.20  2005/04/15 18:02:58  fplanque
 * finished implementation of properties/meta data editor
 * started implementation of files to items linking
 *
 * Revision 1.19  2005/04/14 19:57:52  fplanque
 * filemanager refactoring & cleanup
 * started implementation of properties/meta data editor
 * note: the whole fm_mode thing is not really desireable...
 *
 * Revision 1.18  2005/04/14 18:34:03  fplanque
 * filemanager refactoring
 *
 * Revision 1.17  2005/04/13 18:31:26  fplanque
 * tried to make copy/move/rename work ...
 *
 * Revision 1.16  2005/04/12 19:00:22  fplanque
 * File manager cosmetics
 *
 * Revision 1.14  2005/03/16 19:58:13  fplanque
 * small AdminUI cleanup tasks
 *
 * Revision 1.13  2005/03/04 18:40:26  fplanque
 * added Payload display wrappers to admin skin object
 *
 * Revision 1.12  2005/02/28 09:06:37  blueyed
 * removed constants for DB config (allows to override it from _config_TEST.php), introduced EVO_CONFIG_LOADED
 *
 * Revision 1.11  2005/02/27 20:34:48  blueyed
 * Admin UI refactoring
 *
 * Revision 1.10  2005/02/21 00:34:36  blueyed
 * check for defined DB_USER!
 *
 * Revision 1.9  2005/02/08 01:01:39  blueyed
 * moved searchbox, beautified create bar.
 *
 * Revision 1.8  2005/01/27 21:56:07  blueyed
 * layout..
 *
 * Revision 1.7  2005/01/27 20:07:51  blueyed
 * rolled layout back somehow..
 *
 * Revision 1.6  2005/01/27 13:34:56  fplanque
 * i18n tuning
 *
 * Revision 1.4  2005/01/26 17:55:23  blueyed
 * catching up..
 *
 * Revision 1.3  2005/01/25 18:07:42  fplanque
 * CSS/style cleanup
 *
 * Revision 1.2  2005/01/15 20:32:14  blueyed
 * small fix, warning icon
 *
 * Revision 1.1  2005/01/12 17:55:51  fplanque
 * extracted browsing interface into separate file to make code more readable
 *
 */
?>
