<!DOCTYPE html>
<html lang="eng">
<head>
<title>Digital Design</title>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/> 

<script type="text/javascript">
<?php
        include_once 'db_con.php';

		if(isset($_POST['value'])) {
			if($_POST['value'] == 'none') {
			
				$sqlquery = "select pattern_file_id as id, patterns.name as Name,  ROUND(AVG(question_1)) as Rating,  group_concat(`question_2`) as Problems,  group_concat(`question_5`) as 'Comments'
                FROM    reviews
                left outer join pattern_files on reviews.pattern_file_id =pattern_files.id
                left outer join patterns on patterns.id = pattern_files.pattern_id
                where (question_1 >0 and question_5 !='') and ( question_2 like '%none%')
                group by reviews.pattern_file_id";  
			}  
			elseif($_POST['value'] == 'bumper') {  
			
			$sqlquery = "select pattern_file_id as id, patterns.name as Name,  ROUND(AVG(question_1)) as Rating,  group_concat(`question_2`) as Problems,  group_concat(`question_5`) as 'Comments'
                FROM    reviews
                left outer join pattern_files on reviews.pattern_file_id =pattern_files.id
                left outer join patterns on patterns.id = pattern_files.pattern_id
                where (question_1 >0 and question_5 !='') and ( question_2 like '%none%')
                group by reviews.pattern_file_id";
			} elseif($_POST['value'] == 'hoods'){
				$sqlquery = "select pattern_file_id as id, patterns.name as Name,  ROUND(AVG(question_1)) as Rating,  group_concat(`question_2`) as Problems,  group_concat(`question_5`) as 'Comments'
                FROM    reviews
                left outer join pattern_files on reviews.pattern_file_id =pattern_files.id
                left outer join patterns on patterns.id = pattern_files.pattern_id
                where (question_1 >0 and question_5 !='') and ( question_2 like '%hoods%')
                group by reviews.pattern_file_id";
			}
		
		}else {  
			
			$sqlquery = "select pattern_file_id as id, patterns.name as Name,  ROUND(AVG(question_1)) as Rating,  group_concat(`question_2`) as Problems,  group_concat(`question_5`) as 'Comments'
                FROM    reviews
                left outer join pattern_files on reviews.pattern_file_id =pattern_files.id
                left outer join patterns on patterns.id = pattern_files.pattern_id
                where question_1 >0 and question_5 !=''
                group by reviews.pattern_file_id";  
			} 
		
		
		$result = mysqli_query($db_con, $sqlquery);


        $graph_data = "";
		$name = "";

        while($row = mysqli_fetch_array($result)){
                $rating = $row['Rating'];
                $file_name = $row['Name'];
                $file_id = $row['id'];

                $problems= $row['Problems'];

                $comments = str_replace(array("'", "\r", "\n"), "", $row['Comments']);

                $graph_data .= "{y:$rating, patterns_name: '$file_name', comments:'$comments',problems:'$problems'},";
                $name .="'$file_name',";
				

        }

        ?>
		
$(function () {
    $('#container').highcharts({
        chart: {
            renderTo: 'container',
            type: 'bar'
        },
                 plotOptions: {
            series: {
                colorByPoint: true
            }
        },
                 title: {
            text: 'DD4 Graph'
			
			
		},
                subtitle: {
            text: 'Comments & Feedback Against Patterns Name with Rating'
        },
        xAxis: {
            categories: [<?php echo $name;?>]
        },

          yAxis: {
            min: 0,
            title: {
                text: 'Ratings (0-5)',

            },

        },
                credits: {
            enabled: false
        },
        series: [{
                        showInLegend: false,
                       
            data: [<?php echo $graph_data; ?>]
        },],
        tooltip: {
            formatter: function() {return ' ' +
                                '<span style="color:#8E44AD;">Name: </span>' + '<strong>' + this.point.patterns_name + '</strong>' + '<br />' +
                                '<span style="color:#F1C40F;">Ratings: </span>' + '<strong>' + this.point.y + '</strong>' + '<br />' +
                                '<span style="color:#2CC990;">Comments: </span>' + '<strong>' + this.point.comments + '</strong>' + '<br />' +
                                '<span style="color:#A94442;">Problems: </span>' + '<strong>' + this.point.problems + '</strong>' + '<br />' ;

            }
        }
    });
});



</script>

</head>
<body>
<form method='post' name='form_filter' > 
	<select name="value"> 
		<option value="all">All</option> 
		<option value="none">None</option> 
		<option value="bumper">Bumper</option> 
		<option value="hoods">Hoods</option>
	</select> 
	<input type='submit' value = 'Filter'> 
</form>
<div id="container" style="width:100; height:600px;"></div>
</body>
</html>
			
