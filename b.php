<!DOCTYPE html><html lang="eng"><head><title>Tabular Prsentation</title><script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>   <?php	include_once 'db_con.php';	$result = mysqli_query($db_con, "select patterns.name as file_name, from_unixtime(reviews.created) as created_date, reviews.question_1 as a, reviews.question_2 as b, reviews.question_3 as c, reviews.question_4 as d, reviews.question_5 as e from reviews									join pattern_files on pattern_files.id = reviews.pattern_file_id									join patterns on patterns.id = pattern_files.pattern_id									group by pattern_file_id");	$graph_data = "";	while($row = mysqli_fetch_array($result)){		$files_name = $row['file_name'];		$date_created = $row['created_date'];		$reviews_a = $row['a'];		$reviews_b = $row['b'];		$reviews_c = $row['c'];		$reviews_d = $row['d'];		$reviews_e = $row['e'];						$graph_data .= "['$files_name', '$date_created', '$reviews_a', '$reviews_b', '$reviews_c', '$reviews_d', '$reviews_e'],";						}		?>		<script type="text/javascript">      google.charts.load('current', {'packages':['table']});      google.charts.setOnLoadCallback(drawTable);      function drawTable() {        var data = new google.visualization.DataTable();        data.addColumn('string', 'File Name');		data.addColumn('string', 'Date Created')		data.addColumn('string', 'Question 1');		data.addColumn('string', 'Question 2');		data.addColumn('string', 'Question 3');		data.addColumn('string', 'Question 4');		data.addColumn('string', 'Question 5');                       data.addRows([          <?php echo $graph_data;?>                ]);        var table = new google.visualization.Table(document.getElementById('table_div'));        table.draw(data, {showRowNumber: true, width: '100%', height: 'auto'});      }    </script></head><div id="table_div"></div></html>