<?php $ci = get_instance(); ?>
<div class="teethContainer containerBaby"
	 style="<?= ($profile['age'] <= 13) ? '' : 'display: none;' ?>" dir="ltr">
	<div class="upperTeethSimple">


		<div class="qi">

			<div class="babytooth" onclick="insertBaby_test(this, toothe)">
				<h6>E</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/j.png"
					alt="" class="babytoothimg"/>
			</div>


			<div class="babytooth" onclick="insertBaby_test(this, toothi)">
				<h6>D</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/i.png"
					alt="" class="babytoothimg"/>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothh)">
				<h6>C</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/h.png"
					alt="" class="babytoothimg"/>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothg)">
				<h6>B</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/g.png"
					alt="" class="babytoothimg"/>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothf)">
				<h6>A</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/f.png"
					alt="" class="babytoothimg"/>
			</div>


		</div>

		<div id="vspace3" class="v-space"></div>

		<div class="qii">

			<div class="babytooth" onclick="insertBaby_test(this, toothe)">
				<h6>A</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/e.png"
					alt="" class="babytoothimg"/>
			</div>


			<div class="babytooth" onclick="insertBaby_test(this, toothd)">
				<h6>B</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/d.png"
					alt="" class="babytoothimg"/>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothc)">
				<h6>C</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/c.png"
					alt="" class="babytoothimg bigtooth"/>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothb)">
				<h6>D</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/b.png"
					alt="" class="babytoothimg bigtooth"/>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, tootha)">
				<h6>E</h6>
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/a.png"
					alt="" class="babytoothimg bigtooth"/>
			</div>


		</div>


	</div>


	<div class="downTeethSimple">

		<div class="qi">

			<div class="babytooth" onclick="insertBaby_test(this, toothk)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/k.png"
					alt="" class="babytoothimg bigtooth"/>
				<h6>E</h6>
			</div>

			<div class="babytooth" onclick="toggleModal(this, toothl)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/l.png"
					alt="" class="babytoothimg  bigtooth"/>

				<h6>D</h6>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothm)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/m.png"
					alt="" class="babytoothimg  bigtooth"/>

				<h6>C</h6>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothn)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/n.png"
					alt="" class="babytoothimg "/>

				<h6>B</h6>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, tootho)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/o.png"
					alt="" class="babytoothimg "/>

				<h6>A</h6>
			</div>
		</div>


		<div id="vspace4" class="v-space"></div>


		<div class="qii">

			<div class="babytooth" onclick="insertBaby_test(this, toothp)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/p.png"
					alt="" class="babytoothimg "/>

				<h6>A</h6>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, toothq)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/q.png"
					alt="" class="babytoothimg "/>

				<h6>B</h6>
			</div>


			<div class="babytooth" onclick="insertBaby_test(this, toothr)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/r.png"
					alt="" class="babytoothimg "/>

				<h6>C</h6>
			</div>


			<div class="babytooth" onclick="insertBaby_test(this, tooths)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/s.png"
					alt="" class="babytoothimg "/>

				<h6>D</h6>
			</div>

			<div class="babytooth" onclick="insertBaby_test(this, tootht)">
				<img
					src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/baby/t.png"
					alt="" class="babytoothimg "/>
				<h6>E</h6>
			</div>

		</div>

	</div>


</div>

<script>
	function insertBaby(div, ToothFunction) {
		resetinputs_baby();
		var imageSrc = div.querySelector("img").src;
		var titleText = div.querySelector("h6").textContent;
		var modalImage = document.getElementById("modalImageb");
		var modalTitle = document.getElementById("modalTitleb");
		modalImage.src = imageSrc;
		let imageUrl = modalImage.src;
		let imagePath = imageUrl.substring(imageUrl.indexOf("/v2"));
		$("#child_teeth_location").val(imagePath);
		modalTitle.textContent = titleText;
		ToothFunction();
		$(`#teethmodal_baby`).modal("toggle");

	}

	function insertBaby_test(div, toothFunction) {
		// Get image source and title from clicked .babytooth div
		const imgSrc = div.querySelector('img').getAttribute('src');
		const title = div.querySelector('h6').innerText;

		// Update all modal titles
		document.querySelectorAll('#teethmodal_baby .modal-Title').forEach(el => {
			el.innerText = title;
		});

		// Update all three modal images
		document.getElementById('modalImage_baby').setAttribute('src', imgSrc);
		document.getElementById('modalImage2_baby').setAttribute('src', imgSrc);
		document.getElementById('modalImage3_baby').setAttribute('src', imgSrc);

		toothFunction(); // Leave it as-is, as requested
		// Show the modal
		$(`#teethmodal_baby`).modal("toggle");
	}



	function insertTooth_baby(div, toothFunction) {
		resetinputs_baby();
		var imageSrc = div.querySelector("img").src;
		var titleText = div.querySelector("h6").textContent;
		var modalImage = document.getElementById("modalImageb");
		var modalTitle = document.getElementById("modalTitleb");
		modalImage.src = imageSrc;
		let imageUrl = modalImage.src;
		let imagePath = imageUrl.substring(imageUrl.indexOf("/v2"));
		$("#child_teeth_location").val(imagePath);
		modalTitle.textContent = titleText;
		toothFunction();
		$(`#extralargemodalxx`).modal("toggle");
	}


</script>
