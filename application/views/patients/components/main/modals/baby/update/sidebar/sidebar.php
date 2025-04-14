<?php
$ci = get_instance();
?>
<header class="sidebar_header">
		<div id="toggle" class="sidebar_toggle">
			<span><?= $ci->lang('Departments') ?> <i class="fa fa-bars"></i></span>
		</div>
		<form id="checkboxes_baby_update">
			<div id="menu">
				<ul class="unstyled-list">
					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('restorative') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox1" id="checkbox_resto_baby_update" class="checkbox"
									   onchange="calculate_sum_baby()" onclick="disableDepartment('checkbox_resto_baby_update', 'Restorative_baby_update', 'Restorative-pane_baby_update')" value="restorative" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>

					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('Endodantic') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox2" class="checkbox" id="checkbox_endo_baby_update"
									   onchange="calculate_sum_baby()" onclick="disableDepartment('checkbox_endo_baby_update', 'endo_baby_update', 'endo-pane_baby_update')" value="endo" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>

					<li>
						<label class="form-label" style="font-size: 20px;">
							<?= $ci->lang('Prosthodontics') ?> <span class="text-red"></span>
						</label>

						<div class="checkbox-wrapper-44" style="scale: 0.8px; padding-top: 13px;">
							<label class="toggleButton">
								<input type="checkbox" name="checkbox3" id="checkbox_prosthodontics_baby_update" class="checkbox"
									   onchange="calculate_sum_baby()" onclick="disableDepartment('checkbox_prosthodontics_baby_update', 'pros-tab_baby_update', 'pros-pane_baby_update')" value="Prosthodontics" checked/>
								<div>
									<svg viewBox="0 0 44 44">
										<path
											d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758"
											transform="translate(-2.000000, -2.000000)"></path>
									</svg>
								</div>
							</label>
						</div>
					</li>


				</ul>
			</div>
		</form>

	</header>
