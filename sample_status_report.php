<?php
session_start();
ob_start();
if(!isset($_SESSION['adminuser'])) {
	header('Location: index.php'); exit;
}
if($_SESSION['userType'] != 1 && $_SESSION['userType'] != 10) {
	header('Location: index.php'); exit;
}
include_once("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Work Sphere</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/highcharts-3d.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/stock/modules/exporting.js"></script>
<script>
function reportBy(value) {
	if(value != 'overall') {
		$.post('functions/ajax_request.php', {report_by:'Yes', value:value, reportType:'3'},function(data){
			if(data != '') {
				$('#reportbyId').html('');
				$('#reportUser').html('');
				$('#reportbyId').html(data);
			} 
		});
		
	} else {
		$('#reportbyId').html('');
		$('#reportUser').html('');
	}
}

function userlist(userType) {
	$.post('functions/ajax_request.php', {userlist:'Yes', userType:userType},function(data){
		if(data != '') {
			$('#reportUser').html(data);
		} 
	});
}
</script>
</head>
<body id="inner_page">
	<div id="wrapper" class="bg_top">
		<div id="content">
			<?php require_once('header.php'); ?>
			<div class="clear"></div>
			<div class="inner_content float_l">
				<div class="left_container">
					<div class="cnt_title">
						<h2>Sample Status Report</h2>
					</div>
					<?php if(!isset($_POST['generate_report'])) { ?>
					<form action="" method="post">
					<table class="form_table">
						<tr>
							<td width="170px;">From Date</td>
							<td><input type="text" value="<?php echo date('d-m-Y'); ?>" required readonly class="txtBx datetimepicker" name="fromDate"/></td>
						</tr>
						<tr>
							<td>To Date</td>
							<td><input type="text" value="<?php echo date('d-m-Y'); ?>" required readonly class="txtBx datetimepicker" name="toDate"/></td>
						</tr>
						<tr>
							<td>Report By</td>
							<td>
								<select required name="report_By" class="txtBx" onchange="return reportBy(this.value);">
									<option value="overall">Overall</option>
									<!--option value="group">Group</option-->
									<option value="user">User</option>
								</select>
							</td>
						</tr>
						<tr id="reportbyId">
						</tr>
						<tr id="reportUser">
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="subBtn frm_sub" name="generate_report" value="Generate"/></td>
						</tr>
					</table>
					</form>
					<?php } else {
						$whereCase = " AND createdDate BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))." 00:00:00' AND '".date('Y-m-d',strtotime($_POST['toDate']))." 23:59:59'";
						if($_POST['report_By'] == 'group') {
							$whereCase .= " AND salesDivision = '".$_POST['reportByType']."'";
							if($_POST['reportByType'] == '1') {
								$userGroupName = 'BM';
							} else {
								$userGroupName = 'TD';
							}
							$reportTitle = 'Sample Status - Group ('.$userGroupName.')';
						} else if($_POST['report_By'] == 'user'){
							if($_POST['reportByType'] == '2') {
								$userTypeName = 'BDE/BDC';
							} else if($_POST['reportByType'] == '4') {
								$userTypeName = 'DTL';
							} else if($_POST['reportByType'] == '5') {
								$userTypeName = 'BDM';
							} else if($_POST['reportByType'] == '6') {
								$userTypeName = 'Business Analysit';
							} else if($_POST['reportByType'] == '7') {
								$userTypeName = 'Lead Generator';
							}
							$userData = mysql_query("select * from admin_login where userId = '".$_POST['userId']."'");
							$userRow = mysql_fetch_array($userData);
							if($_POST['reportByType'] == 2) {
								$whereCase .= " And executive = '".$_POST['userId']."'";
							} else if($_POST['reportByType'] == 4) {
								$whereCase .= " And processed_by = '".$_POST['userId']."'";
							}
							$reportTitle = 'Sample Status - '.$userTypeName.' ('.$userRow['name'].')';
						} else {
							$reportTitle = 'Sample Status - Overall';
						}
						$statusQuery = mysql_query("SELECT (SELECT count(*) FROM sales_request WHERE status='1'".$whereCase.") AS Pending, (SELECT count(*) FROM sales_request WHERE status='2'".$whereCase.") AS Assigned, (SELECT count(*) FROM sales_request WHERE status='3'".$whereCase.") AS Completed, (SELECT count(*) FROM sales_request WHERE status='4'".$whereCase.") AS Closed FROM sales_request LIMIT 0,1");
						$statusRow = mysql_fetch_array($statusQuery);
					?>
						<div id="container" style="height: 800px; margin:auto; margin-top:20px; width: 600px"></div>
					<?php } ?>
				</div>
				<?php require_once('navigation.php'); ?>
			</div>
		</div>
	</div>
</body>
</html>
<script>
	jQuery('.datetimepicker').datetimepicker({
		timepicker:false,
		format:'d-m-Y',
		maxDate:'+1970/01/01',
		scrollInput: false
	});
</script>
<?php
if(isset($_POST['generate_report'])) { 
	$totalSamples = $statusRow['Pending'] + $statusRow['Assigned'] + $statusRow['Completed'] + $statusRow['Closed']; ?>
	<script type="text/javascript">
	/**
	 * Create the data table
	 */
	Highcharts.drawTable = function() {
    
		// user options
		var tableTop = 660,
			colWidth = 150,
			tableLeft = 50,
			rowHeight = 20,
			cellPadding = 2.5,
			valueDecimals = 1,
			valueSuffix = '';
        
		// internal variables
		var chart = this,
			series = chart.series,
			renderer = chart.renderer,
			cellLeft = tableLeft;
		$.each(series, function(i, serie) {
			// Apply the cell text
			renderer.text(
					'Status',
					cellLeft - cellPadding + (colWidth/2), 
					tableTop + rowHeight - cellPadding
				)
				.attr({
					align: 'center'
				})
				.css({
					fontWeight: 'bold'
				})
				.add();
			
			$.each(serie.data, function(row, point) {
				
				// Apply the cell text
				renderer.text(
						point.name, 
						cellLeft + cellPadding, 
						tableTop + (row + 2) * rowHeight - cellPadding
					)
					.attr({
						align: 'left'
					})
					.add();					
			});		
		});
		
		$.each(series, function(i, serie) {
			cellLeft += colWidth;

			// Apply the cell text
			renderer.text(
					'Percentage',
					cellLeft - cellPadding + (colWidth/2), 
					tableTop + rowHeight - cellPadding
				)
				.attr({
					align: 'center'
				})
				.css({
					fontWeight: 'bold'
				})
				.add();
			
			$.each(serie.data, function(row, point) {
				
				// Apply the cell text
				renderer.text(
						Highcharts.numberFormat((point.y/'<?php echo $totalSamples; ?>')*100, 0) + '%', 
						cellLeft - cellPadding + (colWidth/2),
						tableTop + (row + 2) * rowHeight - cellPadding
					)
					.attr({
						align: 'center'
					})
					.add();					
			});	
			renderer.text(
						'Total', 
						cellLeft - cellPadding + (colWidth/2),
						tableTop + (serie.data.length + 2) * rowHeight - cellPadding
					)
					.attr({
						align: 'center'
					}).css({
					fontWeight: 'bold'
				})
					.add();		
			Highcharts.tableLine(
				renderer,
				cellLeft, 
				tableTop + cellPadding,
				cellLeft, 
				tableTop + (serie.data.length + 2) * rowHeight + cellPadding
			);			
		});
		
		$.each(series, function(i, serie) {
			cellLeft += colWidth;
			
			// Apply the cell text
			renderer.text(
					'No of Records',
					cellLeft - cellPadding + (colWidth/2), 
					tableTop + rowHeight - cellPadding
				)
				.attr({
					align: 'center'
				})
				.css({
					fontWeight: 'bold'
				})
				.add();
			
			$.each(serie.data, function(row, point) {
				
				// Apply the cell text
				renderer.text(
						point.y, 
						cellLeft + (colWidth/2) - cellPadding, 
						tableTop + (row + 2) * rowHeight - cellPadding
					)
					.attr({
						align: 'center'
					})
					.add();
				
				// horizontal lines
				if (row == 0) {
					Highcharts.tableLine( // top
						renderer,
						tableLeft, 
						tableTop + cellPadding,
						cellLeft + colWidth, 
						tableTop + cellPadding
					);
					Highcharts.tableLine( // bottom
						renderer,
						tableLeft, 
						tableTop + (serie.data.length + 2) * rowHeight + cellPadding,
						cellLeft + colWidth, 
						tableTop + (serie.data.length + 2) * rowHeight + cellPadding
					);
				}
				// horizontal line
				Highcharts.tableLine(
					renderer,
					tableLeft, 
					tableTop + row * rowHeight + rowHeight + cellPadding,
					cellLeft + colWidth, 
					tableTop + row * rowHeight + rowHeight + cellPadding
				);
					
			});
			renderer.text(
				'<?php echo $totalSamples; ?>', 
				cellLeft - cellPadding + (colWidth/2),
				tableTop + (serie.data.length + 2) * rowHeight - cellPadding
			)
			.attr({
				align: 'center'
			})
			.add();		
			Highcharts.tableLine(
					renderer,
					tableLeft, 
					tableTop + (serie.data.length) * rowHeight + rowHeight + cellPadding,
					cellLeft + colWidth, 
					tableTop + (serie.data.length) * rowHeight + rowHeight + cellPadding
				);
			// vertical lines        
			if (i == 0) { // left table border  
				Highcharts.tableLine(
					renderer,
					tableLeft, 
					tableTop + cellPadding,
					tableLeft, 
					tableTop + (serie.data.length + 2) * rowHeight + cellPadding
				);
			}
			
			Highcharts.tableLine(
				renderer,
				cellLeft, 
				tableTop + cellPadding,
				cellLeft, 
				tableTop + (serie.data.length + 2) * rowHeight + cellPadding
			);
				
			if (i == series.length - 1) { // right table border    
	 
				Highcharts.tableLine(
					renderer,
					cellLeft + colWidth, 
					tableTop + cellPadding,
					cellLeft + colWidth, 
					tableTop + (serie.data.length + 2) * rowHeight + cellPadding
				);
			}
			
		});
	};

	/**
	* Draw a single line in the table
	*/
	Highcharts.tableLine = function (renderer, x1, y1, x2, y2) {
		renderer.path(['M', x1, y1, 'L', x2, y2])
			.attr({
				'stroke': 'silver',
				'stroke-width': 1
			})
			.add();
	}
	$(function () {
		$('#container').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45
				},
				events: {
					load: Highcharts.drawTable
				},
			},
			title: {
				text: '<?php echo $reportTitle; ?>'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					dataLabels: {
						enabled: true,
						format: '{point.name} ({point.y})',
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ <?php if($statusRow['Pending'] > 0) { ?>
							['Pending', <?php echo $statusRow['Pending']; ?>],
						<?php } ?>
						<?php if($statusRow['Assigned'] > 0) { ?>
							['Assigned', <?php echo $statusRow['Assigned']; ?>],
						<?php } ?>
						<?php if($statusRow['Completed'] > 0) { ?>
							['Completed', <?php echo $statusRow['Completed']; ?>],
						<?php } ?>
						<?php if($statusRow['Closed'] > 0) { ?>
							['Closed', <?php echo $statusRow['Closed']; ?>],
						<?php } ?>
				]
			}]
		});
	});
	/*$(function () {
		$('#container').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45
				},
				events: {
					load: function () {
						//add report div
						this.renderer.text('<div style="width:300px; margin:auto; height:200px; display:inline-block; border:1px solid #000;"></div>', 250 ,0, true).attr({ })
						.css({
							position:'relative',
							display:'inline-block',
							margin-top:20
						}).add();
					}
				},
			},
			title: {
				text: '<?php echo $reportTitle; ?>'
			},
			plotOptions: {
				pie: {
					innerSize: 100,
					cursor: 'pointer',
					depth: 45,
					dataLabels: {
						enabled: true,
						format: '{point.name} ({point.y})',
					}
				}
			},
			series: [{
				name: 'Total',
				data: [ <?php if($statusRow['Pending'] > 0) { ?>
							['Pending', <?php echo $statusRow['Pending']; ?>],
						<?php } ?>
						<?php if($statusRow['Assigned'] > 0) { ?>
							['Assigned', <?php echo $statusRow['Assigned']; ?>],
						<?php } ?>
						<?php if($statusRow['Completed'] > 0) { ?>
							['Completed', <?php echo $statusRow['Completed']; ?>],
						<?php } ?>
						<?php if($statusRow['Closed'] > 0) { ?>
							['Closed', <?php echo $statusRow['Closed']; ?>],
						<?php } ?>
				]
			}]
		});
	});*/
	</script>
<?php } ?>
