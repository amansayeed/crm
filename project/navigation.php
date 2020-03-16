<?php
$pagename = basename($_SERVER['PHP_SELF']);
?>
<script>
function navToggle(id) {
	$('.module_list').hide();
	$('#'+id).slideDown();
}
</script>
<div class="right_container">
	<ul class="main_nav">
		<?php
		if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10 || $_SESSION['userType'] == 2 || $_SESSION['userType'] == 5 || $_SESSION['userType'] == 6 || $_SESSION['userType'] == 14 || $_SESSION['userType'] == 7 || $_SESSION['userType'] == 8) { ?>
			<li>
				<img src="../images/icon_folder.png" />
				<a href="javascript:void(0);" onclick="navToggle('module_list_lead');"> Lead Management</a>
				<ul class="module_list disp_none" id="module_list_lead">
					<li>
						<img src="../images/icon_dash.png" />
						<a href="../dashboard_lead.php?clear=1"> Dashboard</a>
					</li>
					<?php if($_SESSION['userType'] != 3 && $_SESSION['userType'] != 4 && $_SESSION['userType'] != 9 && $_SESSION['userType'] != 10 && $_SESSION['userType'] != 11 && $_SESSION['userType'] != 12 && $_SESSION['userType'] != 13) { ?>
						<li>
							<img src="../images/icon_data.png" />
							<a href="../lead_entry.php" style="color:#00aeef;">Create New Lead</a>
						</li>
					<?php } ?>
					<?php if($_SESSION['userType'] != 3 && $_SESSION['userType'] != 4 && $_SESSION['userType'] != 6 && $_SESSION['userType'] != 7 && $_SESSION['userType'] != 14 && $_SESSION['userType'] != 9 && $_SESSION['userType'] != 11 && $_SESSION['userType'] != 12 && $_SESSION['userType'] != 13) { ?>
						<li>
							<img src="../images/icon_data.png" />
							<a href="javascript:void(0);"> Lead Status</a>
							<ul>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_lead.php?status=2"> Initial Stage (<?php echo $statusCount['initial']; ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_lead.php?status=3"> Pricing stage (<?php echo $statusCount['pricing']; ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_lead.php?status=4"> Followup (<?php echo $statusCount['followup']; ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_lead.php?status=5"> Deal (<?php echo $statusCount['complete']; ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_lead.php?status=6"> Dropped (<?php echo $statusCount['dropped']; ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_lead.php?status=7"> Disqualified (<?php echo $statusCount['disqualified']; ?>)</a>
								</li>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</li>
		<?php }
		if($_SESSION['userType'] != 6 && $_SESSION['userType'] != 7 && $_SESSION['userType'] != 14 && $_SESSION['userType'] != 9 && $_SESSION['userType'] != 11 && $_SESSION['userType'] != 12 && $_SESSION['userType'] != 13) { ?>
			<li>
				<img src="../images/icon_folder.png" />
				<a href="javascript:void(0);" onclick="navToggle('module_list_sample');"> Sample Management</a>
				<ul class="module_list disp_none" id="module_list_sample">
					<li>
						<img src="../images/icon_dash.png" />
						<a href="../dashboard.php?clear=1"> Dashboard</a>
					</li>
					<?php if($_SESSION['userType'] != 6 && $_SESSION['userType'] != 7 && $_SESSION['userType'] != 14 && $_SESSION['userType'] != 9 && $_SESSION['userType'] != 11 && $_SESSION['userType'] != 12 && $_SESSION['userType'] != 13) { ?>
						<li>
							<img src="../images/icon_data.png" />
							<a href="javascript:void(0);"> Sample Status</a>
							<ul>
								<?php if($_SESSION['userType'] != 4) { ?>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_data.php?list=1&listType=1&clear=1"> Pending (<?php echo mysql_num_rows($pendQuery); ?>)</a>
								</li>
								<?php } ?>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_data.php?list=2&listType=1&clear=1"> Assigned (<?php echo mysql_num_rows($assQuery); ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="../manage_data.php?list=3&listType=1&clear=1"> Completed (<?php echo mysql_num_rows($comQuery); ?>)</a>
								</li>
								<?php if($_SESSION['userType'] == 2 || $_SESSION['userType'] == 5 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) { ?>
									<li>
										<img src="../images/icon_arrow.png" />
										<a href="../manage_data.php?list=4&listType=1&clear=1"> Closed (<?php echo mysql_num_rows($closeQuery); ?>)</a>
									</li>
								<?php }  ?>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</li>
		<?php } if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10 || $_SESSION['userType'] == 11 || $_SESSION['userType'] == 12 || $_SESSION['userType'] == 13) { ?>
			<li>
				<img src="../images/icon_folder.png" />
				<a href="javascript:void(0);" onclick="navToggle('module_list_pro');"> Project Management</a>
				<ul class="module_list disp_none" id="module_list_pro">
					<li>
						<img src="../images/icon_dash.png" />
						<a href="dashboard.php?clear=1"> Dashboard</a>
					</li>
					<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10 || $_SESSION['userType'] == 11 || $_SESSION['userType'] == 12 || $_SESSION['userType'] == 13) { ?>
						<li>
							<img src="../images/icon_data.png" />
							<a href="javascript:void(0);"> Project Status</a>
							<ul>
							<?php if($_SESSION['userType'] != 13) {?>
									<li>
										<img src="../images/icon_arrow.png" />
										<a href="manage_data.php?list=1&listType=1&clear=1"> Unassigned  (<?php echo mysql_num_rows($pro_pendQuery); ?>)</a>
									</li>
								<?php } ?>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="manage_data.php?list=2&listType=1&clear=1"> Assigned (<?php echo mysql_num_rows($pro_assQuery); ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="manage_data.php?list=5&listType=1&clear=1"> Approval (<?php echo mysql_num_rows($pro_approvalQuery); ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="manage_data.php?list=3&listType=1&clear=1"> Completed (<?php echo mysql_num_rows($pro_comQuery); ?>)</a>
								</li>
								<li>
									<img src="../images/icon_arrow.png" />
									<a href="manage_data.php?list=8&listType=1&clear=1"> Partially Completed (<?php echo mysql_num_rows($pro_parComQuery); ?>)</a>
								</li>
								<?php if($_SESSION['userType'] == 11 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
									<li>
										<img src="../images/icon_arrow.png" />
										<a href="manage_data.php?list=4&listType=1&clear=1"> Closed (<?php echo mysql_num_rows($pro_closeQuery); ?>)</a>
									</li>
								<?php } ?>
								
								<?php if($_SESSION['userType'] == 12 || $_SESSION['userType'] == 13 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
									<li>
										<img src="../images/icon_arrow.png" />
										<a href="manage_data.php?list=6&listType=1&clear=1"> Reassigned (<?php echo mysql_num_rows($pro_reassignQuery); ?>)</a>
									</li>
								<?php } ?>
								<?php if($_SESSION['userType'] == 12 || $_SESSION['userType'] == 13 || $_SESSION['userType'] == 1 || $_SESSION['userType'] == 8 || $_SESSION['userType'] == 10) { ?>
									<li>
										<img src="../images/icon_arrow.png" />
										<a href="manage_data.php?list=7&listType=1&clear=1"> Rejected (<?php echo mysql_num_rows($pro_rejectedQuery); ?>)</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</li>
		<?php }?>
		<li>
			<img src="../images/icon_folder.png" />
			<a href="javascript:void(0);"  onclick="navToggle('module_list_datacard');"> Data Cards</a>
			<ul class="module_list disp_none" id="module_list_datacard">
				<?php
				if($_SESSION['userType'] == 11) { ?>
					<li>
						<img src="../images/icon_arrow.png" />
						<a href="../add_datacard.php" <?php if($pagename == 'add_datacard.php') { echo 'class="active"'; }?>> Add New User</a>
					</li>
				<?php 
					$DataListName = 'Manage Data Cards';
				} else {
					$DataListName = 'View Data Cards';
				}?>
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../manage_datacard.php" <?php if($pagename == 'manage_datacard.php') { echo 'class="active"'; }?>> <?php echo $DataListName; ?></a>
				</li>
			</ul>
		</li>
		<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 9 || $_SESSION['userType'] == 10) { ?>
		<li>
			<img src="../images/icon_user.png" />
			<a href="javascript:void(0);" onclick="navToggle('module_list_user');"> User Management</a>
			<ul class="module_list disp_none" id="module_list_user">
				<?php
				if($_SESSION['userType'] !=10) { ?>
					<li>
						<img src="../images/icon_arrow.png" />
						<a href="../add_user.php" <?php if($pagename == 'add_user.php') { echo 'class="active"'; }?>> Add New User</a>
					</li>
					<li>
						<img src="../images/icon_arrow.png" />
						<a href="../manage_user.php" <?php if($pagename == 'manage_user.php') { echo 'class="active"'; }?>> Manage</a>
					</li>
				<?php } ?>
				<?php
				if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) { ?>
					<li>
						<img src="../images/icon_arrow.png" />
						<a href="../transfer_user.php" <?php if($pagename == 'transfer_user.php') { echo 'class="active"'; }?>>Transfer User</a>
					</li>
				<?php } ?>
			</ul>
		</li>
		<?php } ?>
		<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) { ?>
		<li>
			<img src="../images/icon_folder.png" />
			<a href="javascript:void(0);" onclick="navToggle('module_list_report');"> Reports</a>
			<ul class="module_list disp_none" id="module_list_report">
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../lead_status_report.php" <?php if($pagename == 'lead_status_report.php') { echo 'class="active"'; }?>> Lead Status</a>
				</li>
				<!--li>
					<img src="../images/icon_arrow.png" />
					<a href="../deal_value_report.php" <?php if($pagename == 'deal_value_report.php') { echo 'class="active"'; }?>> Deal Value</a>
				</li-->
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../sample_status_report.php" <?php if($pagename == 'sample_status_report.php') { echo 'class="active"'; }?>> Sample Status</a>
				</li>
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../project_report.php" <?php if($pagename == 'project_report.php') { echo 'class="active"'; }?>> Project Report</a>
				</li>
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../booking_report.php" <?php if($pagename == 'booking_report.php') { echo 'class="active"'; }?>> Booking report</a>
				</li>
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../payment_report.php" <?php if($pagename == 'booking_report.php') { echo 'class="active"'; }?>> Collection report</a>
				</li>
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../gm_report.php" <?php if($pagename == 'gm_report.php') { echo 'class="active"'; }?>> Team Based Report</a>
				</li>
				<li>
					<img src="../images/icon_arrow.png" />
					<a href="../lead_count_report.php" <?php if($pagename == 'lead_count_report.php') { echo 'class="active"'; }?>> Lead Count Report</a>
				</li>
				<?php if($_SESSION['userType'] == 1) { ?>
					<li>
						<img src="../images/icon_arrow.png" />
						<a href="../overall_export.php" <?php if($pagename == 'overall_export.php') { echo 'class="active"'; }?>> Overall Export</a>
					</li>
				<?php } ?>
			</ul>
		</li>
		<?php } ?>
		<?php if($_SESSION['userType'] == 1 || $_SESSION['userType'] == 10) { ?>
		<li>
			<img src="../images/icon_folder.png" />
			<a href="../payment_collection.php?clear=1"> Payment Collection</a>
		</li>
		<?php } ?>
	</ul>
</div>