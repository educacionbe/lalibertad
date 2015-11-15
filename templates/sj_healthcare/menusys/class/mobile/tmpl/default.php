<?php
/** 
 * YouTech menu template file.
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource default.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */
global $yt;
$typelayout = $yt->getParam('layouttype');

?>

<?php
if ($this->isRoot()){
	if($yt->getParam('layouttype')=='res'){ ?>
		<div id="yt-responivemenu" class="yt-resmenu ">
			<a  href="#yt-off-resmenu">
				<i class="fa fa-bars"></i>
			</a>
			<div id="yt_resmenu_sidebar" class="hidden">
				<ul class=" blank">
			<?php
				if($this->haveChild()){
					$idx = 0;
					foreach($this->getChild() as $child){
						++$idx;
						$child->getContent('sidebar');
					}
				}
			?>
				</ul>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					if($('#yt-off-resmenu ')){
						$('#yt-off-resmenu').html($('#yt_resmenu_sidebar').html());
						$("#yt_resmenu_sidebar").remove();
					}
					$('#yt-off-resmenu').mmenu({});

				});
			</script>
		</div>
	<?php
	}
}
?>
