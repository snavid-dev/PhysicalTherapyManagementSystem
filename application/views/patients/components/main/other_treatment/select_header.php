<?php $ci = get_instance(); ?>
<div class="row">
	<div class="col-sm-12 col-md-12">


		<div>
			<div class="col-sm-12 col-md-4">
				<div class="checkbox-wrapper-4">
					<input class="inp-cbx" id="upper_jaw" type="checkbox" onclick="colorize(upperJaw)">
					<label class="cbx" for="upper_jaw">
        <span>
            <svg width="12px" height="10px"></svg>
        </span>
						<span>Upper Jaw</span>
					</label>
					<svg class="inline-svg">
						<symbol id="check-4" viewBox="0 0 12 10">
							<polyline points="1.5 6 4.5 9 10.5 1"></polyline>
						</symbol>
					</svg>
				</div>

			</div>
		</div>

	</div>

	<div class="col-sm-12 col-md-12">


		<div>
			<div class="col-sm-12 col-md-4">
				<div class="checkbox-wrapper-4">
					<input class="inp-cbx" id="down_jaw" type="checkbox" onclick="colorize(downJaw)">
					<label class="cbx" for="down_jaw"><span>
                <svg width="12px" height="10px">

                </svg></span><span>Down Jaw</span></label>
					<svg class="inline-svg">
						<symbol id="check-4" viewBox="0 0 12 10">
							<polyline points="1.5 6 4.5 9 10.5 1"></polyline>
						</symbol>
					</svg>
				</div>
			</div>
		</div>

	</div>

	<div class="col-sm-12 col-md-12">


		<div>
			<div class="col-sm-12 col-md-4">
				<div class="checkbox-wrapper-4">
					<input class="inp-cbx" id="all" type="checkbox" onclick="colorize(allJaws)">
					<label class="cbx" for="all"><span>
                <svg width="12px" height="10px">

                </svg></span><span>All</span></label>
					<svg class="inline-svg">
						<symbol id="check-4" viewBox="0 0 12 10">
							<polyline points="1.5 6 4.5 9 10.5 1"></polyline>
						</symbol>
					</svg>
				</div>
			</div>
		</div>

	</div>

</div>

<div class="row">
	<div class="col-sm-12 col-md-12">
		<span>this input is for checkboxes</span>
		<input class="form-control" id="locationCounterInput">

		<span>tins input is for single choices</span>
		<input class="form-control" id="sigleChoiseInput">

	</div>
</div>
