<?php $ci = get_instance() ?>
<div class="table-responsive">
    <table class="table text-nowrap text-center" id="prescriptionTable">
        <thead class="tableHead">
            <tr>
                <th scope="col">#</th>
                <th scope="col"><?= $ci->lang('date and time') ?></th>
                <th scope="col"><?= $ci->lang('user') ?></th>
                <th scope="col"><?= $ci->lang('actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($prescriptions as $prescription) : ?>
                <tr id="<?= $prescription['id'] ?>" class="tableRow">
                    <td scope="row"><?= $i ?></td>
                    <td><?= $prescription['date_time'] ?></td>
                    <td><?= $prescription['user_name'] ?></td>
                    <td>
                        <div class="g-2">
                            <a href="javascript:viewPrescriptionsMedicines('<?= $prescription['id'] ?>')"
                                class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"><span
                                    class="las la-eye fs-14"></span></a>
                            <a href="javascript:print_prescription('<?= $prescription['id'] ?>')"
                                class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"><span
                                    class="fa-solid fa-print fs-14"></span></a>
                        </div>
                    </td>
                </tr>
            <?php $i++;
            endforeach; ?>
        </tbody>
    </table>
</div>
