<?php
/**
 * @package Module Responsive CSS3 Slider for Joomla! 2.5 & 3.0
 * @version 3.1: mod_responsive_css3_slider.php February,2013
 * @author Joomla Drive Team
 * @copyright (C) 2013- Joomla Drive
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

	defined('_JEXEC') or die;
	$source = $params->get('source');
	$img = array();
	$i = 0;
	if ($handle = opendir($source)) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($entry = readdir($handle))) {
			if($entry == "index.html" || $entry == "." || $entry == "..")
			{}
			else
			{
				$img[$i] = $source."/".$entry;
				$i++;
			}
		}
		closedir($handle);
	}
?>

<link href="modules/mod_responsive_css3_slider/css/main.css" media="screen, projection" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="chrome-extension://cpngackimfmofbokmjmljamhdncknpmg/style.css">
<script type="text/javascript" charset="utf-8" src="chrome-extension://cpngackimfmofbokmjmljamhdncknpmg/page_context.js"></script>

<style type="text/css">
	<?php
		$s_name = '#slide';
		$m_left = 0;
		$n = $i;
		for($j=1;$j<=$n;$j++)
		{
	?>
	<?php echo $s_name.$j;?>:checked ~ #slides .inner { margin-left:<?php echo $m_left."%"; ?>; }

	<?php
			$m_left = $m_left-100;
		}
	?>
	<?php
	$aw = intval(100/$n);
	$riw = 100%$n;
	$aiw = $n * $riw;
	$hiw = ($n * 100)+$aiw;
	?>

	#slides .inner {
		width: <?php echo $hiw."%";?>;
		line-height: 0;
	}

	#slides article {
		width: <?php echo $aw."%"; ?>;
		float: left;
	}
	
	<?php
		for($j=1;$j<$n;$j++)
		{
	?>
	<?php echo $s_name.$j;?>:checked ~ #active label:nth-child(<?php echo $j;?>),
	<?php
		}
	?>
	<?php echo $s_name.$j;?>:checked ~ #active label:nth-child(<?php echo $j;?>) {
		background: #333;
		border-color: #333 !important;
	}
	
	<?php 
		$sl_anim = 0;
		$sl_gap = intval(100/(($n-1)*2));
		$sr_pra = 0;
		$sl_ml = 0;
		$sl_dur = $params->get('duration');
		$autoplay = $params->get('autoplay');
		if($autoplay == 1)
		{
	?>
	
	@keyframes slider {
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%";?> {margin-left:0;}
		
		<?php for($j=1;$j<$n-1;$j++){ 
				
				$sr_pra = $sr_pra + $sl_gap; 
				$sl_ml = $sl_ml - 100; 
		?>
		
		<?php echo $sr_pra."%";?> {margin-left: <?php echo $sl_ml."%";?>} 
		
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%"; ?> {margin-left: <?php echo $sl_ml."%";?>}
		
		<?php
			}
		?>
		<?php $sl_ml = $sl_ml - 100;  ?>
		
		100%{margin-left:<?php echo $sl_ml."%";?>}
	}
	<?php 
		$sr_pra = 0;
		$sl_ml = 0;
	?>
	@-webkit-keyframes slider {
	
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%";?> {margin-left:0;}
		
		<?php for($j=1;$j<$n-1;$j++){ 
				
				$sr_pra = $sr_pra + $sl_gap; 
				$sl_ml = $sl_ml - 100; 
		?>
		
		<?php echo $sr_pra."%";?> {margin-left: <?php echo $sl_ml."%";?>} 
		
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%"; ?> {margin-left: <?php echo $sl_ml."%";?>}
		
		<?php
			}
		?>
		<?php $sl_ml = $sl_ml - 100;  ?>
		
		100%{margin-left:<?php echo $sl_ml."%";?>}
	}
	<?php 
		$sr_pra = 0;
		$sl_ml = 0;
	?>
	@-moz-keyframes slider {
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%";?> {margin-left:0;}
		
		<?php for($j=1;$j<$n-1;$j++){ 
				
				$sr_pra = $sr_pra + $sl_gap; 
				$sl_ml = $sl_ml - 100; 
		?>
		
		<?php echo $sr_pra."%";?> {margin-left: <?php echo $sl_ml."%";?>} 
		
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%"; ?> {margin-left: <?php echo $sl_ml."%";?>}
		
		<?php
			}
		?>
		<?php $sl_ml = $sl_ml - 100;  ?>
		
		100%{margin-left:<?php echo $sl_ml."%";?>}
	}
	<?php 
		$sr_pra = 0;
		$sl_ml = 0;
	?>
	@-o-keyframes slider {
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%";?> {margin-left:0;}
		
		<?php for($j=1;$j<$n-1;$j++){ 
				
				$sr_pra = $sr_pra + $sl_gap; 
				$sl_ml = $sl_ml - 100; 
		?>
		
		<?php echo $sr_pra."%";?> {margin-left: <?php echo $sl_ml."%";?>} 
		
		<?php $sr_pra = $sr_pra + $sl_gap; ?>
		
		<?php echo $sr_pra."%"; ?> {margin-left: <?php echo $sl_ml."%";?>}
		
		<?php
			}
		?>
		<?php $sl_ml = $sl_ml - 100;  ?>
		
		100%{margin-left:<?php echo $sl_ml."%";?>}
	}
	
	<?php
		$effect = $params -> get('effect');
		$mouse_over = $params -> get('mouseover');
	?>
	.inner{
		animation: slider <?php echo $sl_dur;?> infinite <?php echo $effect;?>;
		-webkit-animation: slider <?php echo $sl_dur; ?> infinite <?php echo $effect;?>;
		-moz-animation: slider <?php echo $sl_dur;?> infinite <?php echo $effect;?>;
		-o-animation: slider <?php echo $sl_dur;?> infinite <?php echo $effect;?>;
	}
	<?php
			if($mouse_over == 0)
			{
	?>
				
				.inner:hover{
					animation-play-state:paused;
					-webkit-animation-play-state:paused;
					-moz-animation-play-state:paused;
					-o-animation-play-state:paused;
				}
	<?php
			}
		}
	?>
</style>
<!-- Enable MaxWidth Switching -->

<input checked="" type="radio" name="respond" id="desktop">
<input type="radio" name="respond" id="tablet">
<input type="radio" name="respond" id="mobile">
	
<article id="slider">
	<!-- MaxWidth Options -->
	
	<!-- Slider Setup -->

	<?php
		$slide = 'slide';
		for($j=1;$j<$i+1;$j++)
		{
			if($j==1)
			{
	?>
			<input checked="" type="radio" name="slider" id="<?php echo $slide.$j;?>">
	<?php
			}
			else
			{
	?>
			<input type="radio" name="slider" id="<?php echo $slide.$j;?>">
	<?php
			}
		}
	?>

	<!-- The Slider -->
	
	<div id="slides">
	
		<div id="overflow">
		
			<div class="inner">
			
				<?php
					for($j=0;$j<$i;$j++)
					{
				?>
				<article>
					<img src="<?php echo $img[$j];?>">
				</article>
				<?php
					}
				?>
				
			</div> <!-- .inner -->
			
		</div> <!-- #overflow -->
	
	</div> <!-- #slides -->

	<?php
		if($autoplay == 0)
		{
	?>

	<!-- Controls and Active Slide Display -->

	<div id="controls">
		<?php
			for($j=1;$j<$i+1;$j++)
			{
		?>
		
		<label for="<?php echo $slide.$j;?>"></label>
		
		<?php
			}
		?>
	</div> <!-- #controls -->
	
	<div id="active">

		<?php
			for($j=1;$j<$i+1;$j++)
			{
		?>
		
		<label for="<?php echo $slide.$j;?>"></label>
		
		<?php
			}
		?>
		
	</div> <!-- #active -->
	<?php
		}
	?>

</article> <!-- #slider -->