<?php $ci = get_instance(); ?>

	<div class="row row-sm">
		<div class="col-sm-12 col-md-12" style="border: 1px solid white; padding: 30px">
			<!-- input area -->
			<div class="col-sm-12 col-md-6" style="margin: 0 auto">
				<div class="form-group">
					<label class="form-label">
						Enter the Permission
					</label>
					<input type="text" name="notset" class="form-control" placeholder="Permission Name"
						   style="height: 50px">
				</div>
			</div>

			<!--columns area start-->
			<?php
			$ci->render('users/components/permissions_group_column.php');
			?>
			<!--columns area end-->

		</div>
	</div>


<?php
//$ci->render('users/components/test.php');
