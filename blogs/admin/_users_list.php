<?php
/**
 * This file implements the UI view for the user/group list for user/group editing.
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2003-2005 by Francois PLANQUE - {@link http://fplanque.net/}
 *
 * @package admin
 */
if( !defined('EVO_CONFIG_LOADED') ) die( 'Please, do not access this page directly.' );
?>
<div class="panelblock">
<h2><?php echo T_('Groups &amp; Users') ?></h2>
<table class="grouped" cellspacing="0">
	<tr>
		<th class="firstcol"><?php echo T_('ID') ?></th>
		<th><?php /* TRANS: table header for user list */ echo T_('Login ') ?></th>
		<th><?php echo T_('Nickname') ?></th>
		<th><?php echo T_('Name') ?></th>
		<th><?php echo T_('Email') ?></th>
		<th><?php echo T_('URL') ?></th>
		<th<?php
			if( $current_User->check_perm( 'users', 'edit', false ) )
			{ // extra table cell for +/-
				echo ' colspan="2"';
			}
			echo '>'.T_('Level') ?></th>
		<?php
			if( $current_User->check_perm( 'users', 'edit', false ) )
			{
				echo '<th>'.T_('Edit').'</th>';
			}
		?>
	</tr>
	<?php

	$loop_prev_grp_ID = 0;

	if( count($userlist) )
	{
		// query which groups have users
		$query = 'SELECT grp_ID FROM T_groups, T_users
							WHERE user_grp_ID = grp_ID
							GROUP BY grp_ID';
		$usedgroups = $DB->get_col($query);

		$count = 0;
		foreach( $userlist as $row )
		{ // For each line (can be a user/group or just an empty group)
			$loop_grp_ID = $row->grp_ID;

			if( $loop_prev_grp_ID != $loop_grp_ID )
			{ // ---------- We just entered a new group! ----------
				?>
				<tr class="group">
					<td colspan="7">
						<strong><a href="b2users.php?group=<?php echo $loop_grp_ID ?>"><img src="img/properties.png" width="18" height="13" class="middle" alt="<?php echo T_('Properties') ?>" /> <?php echo format_to_output( $row->grp_name, 'htmlbody' ); ?></a></strong>
						<?php
							if( $loop_grp_ID == $Settings->get('newusers_grp_ID') )
							{
								echo '<span class="notes">('.T_('default group for new users').')</span>';
							}
						?>
					</td>
					<?php
					if( $current_User->check_perm( 'users', 'edit', false ) )
					{ // copy
						?>
						<td>&nbsp;</td>
						<td>
							<a href="b2users.php?group=<?php echo $loop_grp_ID ?>"><img src="img/properties.png" width="18" height="13" class="middle" alt="<?php echo T_('Properties') ?>" /></a>

							<a href="b2users.php?action=newgroup&amp;template=<?php echo $loop_grp_ID ?>" title="<?php echo T_('Copy group') ?>"><img src="img/copy.gif" width="13" height="13" class="middle" alt="<?php echo T_('Copy') ?>" title="<?php echo T_('Copy group') ?>" /></a>
						<?php
						if( ($loop_grp_ID != 1) && ($loop_grp_ID != $Settings->get('newusers_grp_ID'))
								&& !in_array( $loop_grp_ID, $usedgroups ) )
						{ // delete
							?>
							<a href="b2users.php?action=deletegroup&amp;id=<?php echo $loop_grp_ID ?>" title="<?php echo T_('Delete group') ?>"><img src="img/xross.gif" width="13" height="13" class="middle" alt="<?php echo /* TRANS: Abbrev. for Delete */ T_('Del') ?>" title="<?php echo T_('Delete group') ?>" /></a>
							<?php
						}
						echo '</td>';
					}
					?>
				</tr>
				<?php
				$loop_prev_grp_ID = $loop_grp_ID;
			}

			if( !empty( $row->ID ) )
			{ // We have a user here: (i-e group was not empty)
				$loop_User = & new User( $row );
				if( $count%2 == 1 )
					echo "<tr class=\"odd\">\n";
				else
					echo "<tr>\n";
				$email = $loop_User->get('email');
				$url = $loop_User->get('url');
				echo '<td class="firstcol">', $loop_User->get('ID'), "</td>\n";
				echo '<td><a href="b2users.php?user=', $loop_User->get('ID'), '">';
				echo '<img src="img/properties.png" width="18" height="13" class="middle" alt="', T_('Properties'), '" /> ';
				echo $loop_User->get('login'), "</a></td>\n";
				?>
				<td><?php $loop_User->disp('nickname') ?></td>
				<?php
				echo '<td>', $loop_User->get('firstname').'&nbsp;'.$loop_User->get('lastname')."</td>\n";
				echo '<td>&nbsp;';
				if( !empty($email) )
				{
					echo '<a href="mailto:'.$email.'" title="e-mail: '.$email.'"><img src="img/email.gif"  alt="e-mail: '.$email.'" class="middle" /></a>&nbsp;';
				}
				echo '</td><td>&nbsp;';
				if (($url != 'http://') and ($url != ''))
				{
					if( !preg_match('#://#', $url) )
					{
						$url = 'http://'.$url;
					}
					echo "<a href=\"$url\" title=\"website: $url\"><img src=\"img/url.gif\" alt=\"website: $url\" class=\"middle\" /></a>&nbsp;";
				}
				echo "</td>\n";

				echo "<td>".$loop_User->get('level');

				if( $current_User->check_perm( 'users', 'edit', false ) )
				{ // edit actions

					echo '</td><td align="right">';
					if( ($loop_User->get('level') > 0) )
					{ // prom=down
						echo ' <a href="b2users.php?action=promote&amp;id='. $loop_User->get('ID'). '&amp;prom=down'
									.'" title="'.T_('decrease user level').'">-</a> ';
					}
					if( ($loop_User->get('level') < 10 ) )
					{ // prom=up
						echo ' <a href="b2users.php?action=promote&amp;id='. $loop_User->get('ID'). '&amp;prom=up'
									.'" title="'.T_('increase user level').'">+</a> ';
					}

					echo '</td><td>';

					// edit user
					echo ' <a href="b2users.php?user=', $loop_User->get('ID'), '"><img src="img/properties.png" width="18" height="13" class="middle" alt="', T_('Properties'), '" /></a> ';

					// copy user
					echo ' <a href="?action=newuser&amp;template='.$loop_User->get('ID').'"><img src="img/copy.gif" width="13" height="13" class="middle" alt="'.T_('Copy').'" /></a> ';

					if( ($loop_User->ID != 1) && ($loop_User->ID != $current_User->ID) )
					{ // delete
						?>
						<a href="b2users.php?action=deleteuser&amp;id=<?php echo $loop_User->get('ID') ?>" title="<?php echo T_('Delete user') ?>"  onclick="return confirm('<?php echo /* TRANS: Warning this is a javascript string */ T_('Are you sure you want to delete this user?\\nWarning: all his posts will be deleted too!') ?>')"><img src="img/xross.gif" width="13" height="13" class="middle" alt="<?php echo /* TRANS: Abbrev. for Delete */ T_('Del') ?>" title="<?php echo T_('Delete user') ?>" /></a>
						<?php
					}
				}
				echo "</td>\n";
				echo "</tr>\n";
				$count++;
			}
		}

		echo "\n</table>";
	}

if( $current_User->check_perm( 'users', 'edit', false ) )
{ // create new user link
	?>
	<p class="center">
		<a href="?action=newuser"><img src="img/new.gif" width="13" height="13" class="middle" alt="" /> <?php echo T_('New user...') ?></a>
		&middot;
		<a href="?action=newgroup"><img src="img/new.gif" width="13" height="13" class="middle" alt="" /> <?php echo T_('New group...') ?></a>
	</p>
	<?php
}
?>
</div>
