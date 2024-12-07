<?php $ci = get_instance(); ?>
<script>
	function print_lab(labId) {
		window.open(`<?= base_url() ?>admin/print_lab/${labId}`, '_blank');
	}
</script>
