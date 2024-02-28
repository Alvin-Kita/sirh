<?php
include_once('/var/www/html/sirh/config/connectdb.php');

$menus = $bdd->query('SELECT * FROM menu');
$url = $_SERVER['REQUEST_URI'];
?>

<div id="menu">
	<nav>
		<ul>
			<?php
			while ($menu = $menus->fetch())
			{
			if (($menu['for_admin_only'] == 1 ) /* AND ((strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.207.') !== false) OR (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.215.') !== false)) */ ) {
				$class = "hide" ;
			}
			elseif (($menu['for_admin_only'] == 1 ) /*  AND ((strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.207.') !== true) OR (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], '10.65.215.') !== true)) */ ) {
				$class = "hide" ;
			}
			else {
				$class = "show" ;
			}
				?>
					<li class="<?php if (strpos($url,$menu['url']) !== false) {echo "current";}else {}?> <?php echo $class ; ?>">
						<a href="<?php echo $menu['url']; ?>" class="<?php echo $class ; ?>">
							<?php 
									echo $menu['fre'];
							?>
						</a>
					</li>
				<?php
			}
			?>
		</ul>
	</nav>
</div>

<?php 
$menus->closeCursor(); // Termine le traitement de la requête
?>
