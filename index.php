<!DOCTYPE html>
<html>
<head>
	<title>Beer App</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link rel="stylesheet" href="static/style.css">
</head>
<body>
<script src="static/beer.js"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	var randomBtn = document.getElementById('action_random');
	var breweryBtn = document.getElementById('action_brewery');

	randomBtn.onclick = function(e) {
		e.preventDefault();
		Beer.getRandom();
	}

	breweryBtn.onclick = function(e) {
		e.preventDefault();
		Beer.getRandomFromBrewery();
	}

	Beer.getRandom();
});
</script>

<div class="container">
<h3>Distilled SCH Beer App</h3>
	<div class="col-md-10" id="random_beer"></div>
	<div class="col-md-2">
		<form method="get" action="">
			<input type="button" name="action" id="action_random" value="Another Beer" class="btn btn-primary btn-block" />
			<input type="button" name="action" id="action_brewery" value="More from Brewery" class="btn btn-primary btn-block" />
		</form>
	</div>

</div>
<?php
include 'autoload.php';

$results = false;
$query = isset($_GET['query']) ? $_GET['query'] : null;
if ($query) {
	$api = new BreweryApi();
	$results = json_decode($api->search($query, $_GET['type']), true);
}

?>

<form method="get" action="">
<div class="container">
	<h3>Search</h3>
	<div class="col-md-6"><input type="text" name="query" placeholder="Search" class="form-control" value="<?=$query?>" required /></div>
	<div class="col-md-4">
		<div>
		<label class="radio-inline">
			<input type="radio" name="type" value="beer" <?=!isset($_GET['type']) || $_GET['type'] == 'beer' ? 'checked' : ''?>> Beer 
		</label>
		<label class="radio-inline">
			<input type="radio" name="type" value="brewery" <?=isset($_GET['type']) && $_GET['type'] == 'brewery' ? 'checked' : ''?>> Brewery
		</label>
		</div>
	</div>
	<div class="col-md-2"><input type="submit" name="action" value="Search" class="btn btn-primary" /></div>
</div>
</form>

<?php
if ($results) { 
	?>
	<div class="container">
		<h3>Search Results</h3>
		<?php
		if ($results['status'] == 'failure' || !count($results['data'])) {
			?>
			<div class="col-md-12">No results were found.</div>
			<?php
		} else {
			foreach($results['data'] as $res) {
				?>
				<div class="col-md-12 result-box">
					<div class="col-md-2">
						<?=isset($res['labels']['icon']) ? '<img src="' . $res['labels']['icon'] .'" class="img-thumbnail" />' : '<div class="img-thumbnail fake-thumbnail"></div>'?>	
					</div>
					<div class="col-md-10">
						<div><?=$res['name']?></div>
						<div><?=isset($res['description']) ? $res['description'] : 'No description available'?></div>
					</div>
				</div>
				<?php			
			}
		}
		?>
	</div>
	<?php
}
?>
</body>
</html>
