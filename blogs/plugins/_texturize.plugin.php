<?php
/**
 * This file implements the Texturize plugin for b2evolution
 *
 * @author WordPress team - http://sourceforge.net/project/memberlist.php?group_id=51422
 * b2evo: 1 notice fix.
 *
 * @package plugins
 */
if( !defined('EVO_CONFIG_LOADED') ) die( 'Please, do not access this page directly.' );

/**
 * @package plugins
 */
class texturize_plugin extends Plugin
{
	var $code = 'b2WPTxrz';
	var $name = 'Texturize';
	var $priority = 90;
	var $apply_when = 'opt-in';
	var $apply_to_html = true;
	var $apply_to_xml = true;
	var $short_desc;
	var $long_desc;


	/**
	 * Constructor
	 *
	 * {@internal texturize_plugin::texturize_plugin(-)}}
	 */
	function texturize_plugin()
	{
		$this->short_desc = 'Smart quotes and more';
		$this->long_desc = 'No description available';
	}


	/**
	 * Perform rendering
	 *
	 * {@internal texturize_plugin::Render(-)}}
	 *
	 * @param array Associative array of parameters
	 * 							(Output format, see {@link format_to_output()})
	 * @return boolean true if we can render something for the required output format
	 */
	function Render( & $params )
	{
		if( ! parent::Render( $params ) )
		{	// We cannot render the required format
			return false;
		}

		$content = & $params['data'];

		$output = '';
		$textarr = preg_split("/(<.*>)/Us", $content, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
		$stop = count($textarr); $next = true; // loop stuff
		for ($i = 0; $i < $stop; $i++) {
			$curl = $textarr[$i];

			if (strlen($curl) && '<' != $curl{0} && $next) { // If it's not a tag
				$curl = str_replace('---', '&#8212;', $curl);
				$curl = str_replace('--', '&#8211;', $curl);
				$curl = str_replace("...", '&#8230;', $curl);
				$curl = str_replace('``', '&#8220;', $curl);

				// This is a hack, look at this more later. It works pretty well though.
				$cockney = array("'tain't","'twere","'twas","'tis","'twill","'til","'bout","'nuff","'round");
				$cockneyreplace = array("&#8217;tain&#8217;t","&#8217;twere","&#8217;twas","&#8217;tis","&#8217;twill","&#8217;til","&#8217;bout","&#8217;nuff","&#8217;round");
				$curl = str_replace($cockney, $cockneyreplace, $curl);

				$curl = preg_replace("/'s/", '&#8217;s', $curl);
				$curl = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $curl);
				$curl = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $curl);
				$curl = preg_replace('/(\d+)"/', '$1&Prime;', $curl);
				$curl = preg_replace("/(\d+)'/", '$1&prime;', $curl);
				$curl = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $curl);
				$curl = preg_replace('/(\s|\A)"(?!\s)/', '$1&#8220;$2', $curl);
				$curl = preg_replace('/"(\s|\Z)/', '&#8221;$1', $curl);
				$curl = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $curl);
				$curl = preg_replace("/\(tm\)/i", '&#8482;', $curl);
				$curl = preg_replace("/\(c\)/i", '&#169;', $curl);
				$curl = preg_replace("/\(r\)/i", '&#174;', $curl);
				$curl = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $curl);
				$curl = str_replace("''", '&#8221;', $curl);

				$curl = preg_replace('/(d+)x(\d+)/', "$1&#215;$2", $curl);

			} elseif (strstr($curl, '<code') || strstr($curl, '<pre') || strstr($curl, '<kbd' || strstr($curl, '<style') || strstr($curl, '<script'))) {
				// strstr is fast
				$next = false;
			} else {
				$next = true;
			}
			$output .= $curl;
		}
		$content = $output;

		return true;
	}
}

?>