<?php
/**
 * @package Extension Download
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */


/**
 * return subfolder and file in directory
 */
	$exts_group = array(0=>'modules',
						1=>'templates',
						2=>'plugins_system',
						3=>'plugins_content'
						); 
 
	function getFolder($dir , $type = 'folder'){
		$result = array();
		$items = scandir($dir);
		if(!empty($items)){
			foreach($items as $key => $item){
				if(!in_array($item,array(".",".."))){
					if($type == 'folder') {
						if(is_dir($dir.DIRECTORY_SEPARATOR.$item)){
							$result[] = $item;
							
						}
					}else{
						if(is_file($dir.DIRECTORY_SEPARATOR.$item)){
							$result[] = $item;
						}
					}
				}
			}
		}
		return $result;
	}

	function _ucWords($str){
		$_str = $str;
		if($str != ''){
			if(strpos($str, '_')) {
				$_str = str_replace('_', ' ', $str);
				$_str = str_replace('mod ', '', $_str);
			}
			$_str = ucwords($_str);
		}
		return $_str;
	}

	
	function addAll($folder_path,$local_path,$z){
	   $dh=opendir($folder_path);
		while (($file = readdir($dh)) !== false) {
			if( ($file !== ".") && ($file !== "..")){
				if (is_file($folder_path.$file)){
					$z->addFile($folder_path.$file,$local_path.$file);
				}else{
					addAll($folder_path.$file."/",$local_path.$file."/",$z);
				}
			}
		 }
	}

	function zipFileOuput($type,$ext_name,$name_gr = '', $read_me = ''){
		$folder = 'exts_dowload_tmp';
		$file_path_group = null;
		if($type == 'single'){
			$file_path = getFolder($folder , '');
			$file_path_group =  $folder.'/'.$file_path[0];
			
		}else{
			
			
			if($name_gr != ''){
				$file_path_group = $folder.'/'.$name_gr.'.zip';
			}else{
				$file_path_group = $folder.'/please_rename_UNZIPFIRST.zip';
			}
			if($read_me != '') {
				$fileLocation = $folder. "/readme.txt";
				$file = fopen($fileLocation,"w");
				$content = $read_me;
				fwrite($file,$content);
				fclose($file);
			}
			$zip = new ZipArchive();
			if ($zip->open($file_path_group, ZIPARCHIVE::CREATE) === true) {
				if(!file_exists($file_path_group)){
					addAll($folder.'/',"",$zip);
				}
				$zip->close();
			}
		}
		if(file_exists($file_path_group)){
			header("Content-type: application/zip;\n");
			header("Content-Transfer-Encoding: Binary");
			header("Content-length: ".filesize($file_path_group).";\n");
			header("Content-disposition: attachment; filename=\"".basename($file_path_group)."\"");
			readfile($file_path_group);
		}
		_delelteFolder($folder);
		return true;
		
	}
	
	function _delelteFolder($folder){
		if(is_dir($folder)){
			$folder_handler = dir($folder);
			while ($file = $folder_handler->read()) {
				if ($file == "." || $file == "..")
					continue;
				unlink($folder.'/'.$file);

			}
			$folder_handler->close();
			rmdir($folder);
		}
	}

	function _proGeneral($gr = 'mod', $ext_name){
		$folder = 'exts_dowload_tmp';
		$jversion = null;
		if(!file_exists($folder)){
			mkdir ($folder, 0777);
		}

		if(!defined('_JEXEC')){
			define('_JEXEC',1) ;
		}
		
		if (!defined('JPATH_PLATFORM'))
		{
			define('JPATH_PLATFORM',__DIR__);
		}

		if(file_exists('libraries/cms/version/version.php')) {
			if(!class_exists('JVersion')) {
				require_once dirname(__FILE__).'/libraries/cms/version/version.php';
			}
			$version = new JVersion();
			$jversion = $version->RELEASE;
			
		}		
		$file_name = $ext_name;
		
		switch($gr){
			case 'mod':
				$xml = simplexml_load_file('modules/'.$file_name.'/'.$file_name.'.xml');
				$_file_name = _getVersion($file_name,$jversion, $xml);
				$file_path= $folder.'/'.$_file_name.'.zip';
				$zip = new ZipArchive();
				if ($zip->open($file_path, ZIPARCHIVE::CREATE) === true) {
					if(!file_exists($file_path)){
						addAll("modules/".$file_name."/","",$zip);
					}
					$zip->close();
				}
			break;
			case 'pls':
				$path = 'plugins/system/';	
				$prefix = '.plg_system_';
			case 'plc':
				if($gr == 'plc'){
					$path = 'plugins/content/';	
					$prefix = '.plg_content_';
				}
			case 'tmp':
				
				if($gr == 'tmp'){
					$path = 'templates/';	
					$prefix = '.tpl_';
					$folder_plc = $path.$file_name;
					if($file_name == 'system'){
						echo 'Can not download this file';
						exit;
					}
					$xml = simplexml_load_file($folder_plc.'/templateDetails.xml');
				}else{
					$folder_plc = $path.$file_name;
					$xml = simplexml_load_file($folder_plc.'/'.$file_name.'.xml');
				}
				
				$_file_name = _getVersion($file_name,$jversion, $xml);
				$lag = $xml->languages->language['tag'];
				$folder_lag = $path.$file_name.'/language';
				if($lag != null && !file_exists($folder_lag)){
					if($gr == 'tmp'){
						$srcfile 	= 'language/'.$lag.'/'.$lag.$prefix.$file_name.'.ini';
						$srcfile1	= 'language/'.$lag.'/'.$lag.$prefix.$file_name.'.sys.ini';
					}else{
						$srcfile 	= 'administrator/language/'.$lag.'/'.$lag.$prefix.$file_name.'.ini';
						$srcfile1	= 'administrator/language/'.$lag.'/'.$lag.$prefix.$file_name.'.sys.ini';
					}
					$src_index = $folder_plc.'/index.html';
					$dstfile = $folder_lag.'/'.$lag.'/'.$lag.$prefix.$file_name.'.ini';
					$dstfile1 = $folder_lag.'/'.$lag.'/'.$lag.$prefix.$file_name.'.sys.ini';
					$dh = opendir($folder_plc);
					while (($file = readdir($dh)) !== false) {
						if( ($file !== ".") && ($file !== "..")){
							if(!file_exists($folder_lag)){
								mkdir ($folder_lag, 0777);
								mkdir ($folder_lag.'/'.$lag, 0777);
								copy($src_index, $folder_lag.'/index.html');
								copy($src_index, $folder_lag.'/'.$lag.'/index.html');
								copy($srcfile, $dstfile);
								copy($srcfile1, $dstfile1);
							}
						}

					}
				}
				$file_path= $folder.'/'.$_file_name.'.zip';
				$zip = new ZipArchive();
				if ($zip->open($file_path, ZIPARCHIVE::CREATE) === true) {
					if(!file_exists($file_path)){
						addAll($path.$file_name."/","",$zip);
					}
					$zip->close();
				}
				
			break;
			default:
		}
	}

	function _getVersion($file_name,$jversion = null, $xml){
		if($jversion != null) {
			$_file_name = $file_name.'_j'.$jversion.'_v'.$xml->version;
		}else{
			$_file_name = $file_name.'_v'.$xml->version;
		}
		return $_file_name;
	}

	if(isset($_POST['submit']) && $_POST['submit'] == 'Download' && !empty($_POST['zip_group']) ) {
		$zip_group = $_POST['zip_group'];
		
		if($zip_group !=''){
			$group = '';
			$ext_name = '';
			foreach($zip_group as $zip){
				$tmp = explode('.',$zip);
				$group = $tmp[0];
				$ext_name = $tmp[1];
				$type = 'single';
				_proGeneral($group,$ext_name);
			}
			if(count($zip_group) > 1) {
				$type = 'group';
			}else{
				$type = 'group';
			}
			$name_gr =  isset($_POST['name-group'])?$_POST['name-group']:'';
			$read_me = isset($_POST['read_me'])?$_POST['read_me']:'';
			 zipFileOuput($type,$ext_name,$name_gr, $read_me);
		}
	}

	if(isset($_GET['zipfile'])){
		$file_name = $_GET['zipfile'];
		$tmp = explode('.',$file_name);
		$group = $tmp[0];
		$ext_name = $tmp[1];
		$type = 'single';
		
		_proGeneral($group,$ext_name);
		
		 zipFileOuput($type,$ext_name);
	}

?>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Extension Download</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<style type="text/css">
		.cf:before,
		.cf:after {
			content: " "; /* 1 */
			display: table; /* 2 */
		}

		.cf:after {
			clear: both;
		}
		.ext-download{ margin:20px auto; padding:0; overflow:hidden; max-width:500px; }
		
		.ext-download ul.extd-tabs{
			list-style: none; 
			margin:0; 
			padding:0;
			border-bottom:1px solid #DDD;
			border-left:1px solid #DDD;
		}
		.ext-download ul.extd-tabs li{
			float:left; 
			margin:0; 
			margin-bottom:-1px;
		}
		.ext-download ul.extd-tabs li > a{
			text-decoration: none;
			border: 1px solid #DDD;
			line-height: 20px;
			padding: 8px 8px;
			display:block; 
			border-left:0;
			color:#555555;
			background-color: #F5F5F5;
			background-image: linear-gradient(to bottom, #FFFFFF, #E6E6E6);
			background-repeat: repeat-x;
			border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) #B3B3B3;
			border-image: none;
			box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
			color: #333333;
			cursor: pointer;
			display: inline-block;
			font-size: 20px;
			margin-bottom: 0;
			text-align: center;
			text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
			vertical-align: middle;
			border-bottom-color:#DDD;
		}
		
		.ext-download ul.extd-tabs li.active >a{
			border-bottom-color: #FFF;
			box-shadow:none;
			background:none;
		}
		
		.extd-tabs-content{
			border:1px solid #DDD;
			border-top:0;
			padding:20px 0;
		}
		
		/*.extd-tabs-content-inner{
			max-height:400px;
			overflow-x:hidden;
			overflow-y:auto;
		}*/
		
		.extd-tabs-content ul{ 
			margin:0; 
			padding:0 20px; 
			list-style:none;
			display:none;
		}
		
		.extd-tabs-content ul li{ margin:2px 0; 
			border-bottom: 1px solid #FFFFFF;
			border-top: 1px solid transparent;
				color: #666699;
			padding: 8px;
		}
		.extd-tabs-content ul li.color{
			background: none repeat scroll 0 0 #E8EDFF;
		}
		.extd-tabs-content ul li { font-size:18px;}
		
		.extd-tabs-content ul li a{ float:right; line-height:18px;}
		
		.ext-download .extd-sbumit{ text-align:center; margin:10px;}
		
		.ext-download .extd-sbumit input[type="submit"],
		.ext-download .extd-sbumit input[type="reset"]{	background-color: #F5F5F5;
			background-image: linear-gradient(to bottom, #FFFFFF, #E6E6E6);
			background-repeat: repeat-x;
			border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) #B3B3B3;
			border-image: none;
			box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
			color: #333333;
			cursor: pointer;
			display: inline-block;
			font-size: 20px;
			margin-bottom: 0;
			text-align: center;
			text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
			border:1px solid #CCC;
			padding:5px;
		}
		
		.ext-download .extd-sbumit input[type="submit"]{
			background-color: #49AFCD;
			background-image: linear-gradient(to bottom, #5BC0DE, #2F96B4);
		}
		
		.extd-tabs-content ul.extd-content-active{ display:block;}
		
		.extd-name-group,.extd-readmre{text-align:center; margin:10px 0;}
	</style>
	<?php $tag_id = 'ext_download'.rand().time(); ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"  type="text/javascript"></script>
	<script type="text/javascript">
		$.noConflict();
		jQuery(document).ready(function($){
			;(function(element){
				var $element = $(element);
				var $extd_tab = $('.extd-tab',$element);
				var $tab_content = $('.extd-tab-pane',$element);
				$extd_tab.each(function(val,el){
					var $tab = $(this);
					$tab.on('click.download', function(){
						var $this = $(this);
						if($this.hasClass('active')) return false;
						$extd_tab.removeClass('active');
						$this.addClass('active');	
						$tab_content.removeClass('extd-content-active');
						var $tab_content_active = $this.attr('data-tab');
						$($tab_content_active).addClass('extd-content-active');
						return false;
					});
					
				});
			})('#<?php echo $tag_id; ?>');
		});
	</script>
</head>	
<body>
	<div class="ext-download" id="<?php echo $tag_id; ?>" >
		<ul class="extd-tabs cf">
			<?php foreach($exts_group  as $key=> $ext){ ?>	
			<li class="extd-tab <?php echo ($key == 0)?' active':'';?>" data-tab="<?php echo '.extd-'.$ext; ?>">
				<a data-toggle="tab" href="#<?php echo $ext ?>"><?php echo _ucWords($ext); ?></a>
			</li>
		  <?php  } ?>
		</ul>
		<form  method="post" action="">
			<div class="extd-tabs-content">
				<div class="extd-tabs-content-inner">
				<?php
				foreach($exts_group  as $_key => $ext){
				$_ext = '';
				$gext = '';	
				if($ext == 'modules'){
					$gext = 'mod';
					$_ext = 'modules';
				}else if($ext == 'templates'){
					$gext = 'tmp';
					$_ext = 'templates';
				}else if($ext == 'plugins_content'){
					$gext = 'plc';
					$_ext = 'plugins/content';
				}else {
					$gext = 'pls';
					$_ext = 'plugins/system';
				}
				$items = getFolder($_ext);
				$cls = 'extd-'.$ext;
				$cls .=  ($_key == 0)?' extd-content-active':'';
				if(!empty($items)) { ?>
				<ul class="extd-tab-pane  <?php echo $cls; ?> ">
					<?php  $i = 0; 
					foreach($items as $item) { $i++;
						//var_dump($gext); die;
					?>
						<li class="item-content <?php echo ($i%2)?' color':''; ?>">
							<input type="checkbox" value="<?php echo $gext.'.'.$item; ?>" name="zip_group[]">
							<span  class="title"><?php echo _ucWords($item); ?></span>
							<a href="<?php echo '?zipfile='.$gext.'.'.$item; ?>" title="<?php echo _ucWords($item); ?>">Download</a>
						</li>
					<?php } ?>
				</ul>
				<?php } 
				} ?>
				</div>
			</div>
			<div class="extd-name-group">
				Enter Name for Group: 
				<input type="text" name="name-group" value="" size="50" />
			</div>
			<div class="extd-readmre">
				Enter Content for Readme.txt: 
				<textarea rows="5" cols="55" name="read_me"></textarea>
			</div>
			<div class="extd-sbumit">
				<input type="submit" name="submit" value="Download" />
				<input type="reset"  value="Reset" />
			</div>
		</form>
	</div>
</body>
</html>