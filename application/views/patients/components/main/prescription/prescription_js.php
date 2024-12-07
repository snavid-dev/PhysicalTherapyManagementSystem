<?php $ci = get_instance(); ?>
<script>
	function print_prescription(prescriptionId) {
		window.open(`<?= base_url() ?>admin/print_prescription/${prescriptionId}`, '_blank');
	}
</script>

