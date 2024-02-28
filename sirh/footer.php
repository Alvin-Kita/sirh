<?php
include_once('/var/www/html/sirh/config/connectdb.php');

$footers = $bdd->query('SELECT * from menu');
$copy = $bdd->query('SELECT * FROM langues');

$url = $_SERVER['REQUEST_URI'];

while ($lang = $copy->fetchAll())
{
	$fr = $lang[6];
	$en = $lang[6];
}
?>

<footer>
<li>
						<?php if (strpos($url,'en') !== false) { ?>
						<a href="/">Home</a>
						<?php }
						elseif (strpos($url,'fr') !== false) { ?>
						<a href="/">Accueil</a>
						<?php } else { ?>
						<a href="/">Accueil</a>
						<?php } ?>
</li>
<?php
while ($footer = $footers->fetch())
{
	/*if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {*/
		if (($footer['for_admin_only'] == 1 ) /*AND ((strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.207.') !== false) OR (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.215.') !== false))*/) {
			$class = "hide" ;
		}
		
		elseif (($footer['for_admin_only'] == 1 ) /*AND ((strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.207.') !== true) OR (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.215.') !== true))*/) {
			$class = "hide" ;
		}
		else {
			$class = "show" ;
		}
	/*
	}
	elseif (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		if ($footer['for_admin_only'] == 1 ) {
			$class = "hide" ;
		}
		else {
			$class = "show" ;
		}
	}*/ 
	?>

				<li class="<?php echo $class ; ?>">
				<a class="<?php echo $class ; ?>" href="<?php echo $footer['url']; ?>">
					<?php 
						echo $footer['fre'];
					?>
				</a></li>

	<?php 
}
?>
<p>&copy; 2022 - DSI PAREDES - <?php 
							echo $fr['fre'];
					?></p>
</footer>

<?php 
$footers->closeCursor(); // Termine le traitement de la requête
$copy->closeCursor(); // Termine le traitement de la requête
?>
		<?php
		/*
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		*/
		?>

