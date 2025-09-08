<div class="tab-pane fade p-0 border-0 bg-white p-4 rounded-3" id="pros_update-pane" role="tabpanel"
    aria-labelledby="pros_update-pane" tabindex="0">

    <div class="modal-body">

        <div class="row">


            <!-- Pro modal content update -->
            <div class="col-md-10">
                <div class="row">

                    <div class="col-sm-12">
                        <form action="" id="insertPro_update">
                            <div class="row">

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('type of restoration') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="type_restoration_update" name="type_restoration"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>
                                            <?php foreach ($CariesDepthList as $CariesDepth) : ?>
                                                <option
                                                    value="<?= $CariesDepth['id'] ?>"><?= $ci->lang($CariesDepth['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('Filling material (Core)') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="filling_material_update" name="filling_material"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>
                                            <?php foreach ($CariesDepthList as $CariesDepth) : ?>
                                                <option
                                                    value="<?= $CariesDepth['id'] ?>"><?= $ci->lang($CariesDepth['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- post update -->
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('post') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="post_update" name="post"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>"
                                            onchange="showSelect()">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1">PreFibricated Post</option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- fiber post update -->
                                <div class="col-sm-12 col-md-6 nonDisplay" id="fiber_post_div">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('type of prefabricated post') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="fiber_post_update" name="fiber_post"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1">PreFibricated Post</option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- metal screw post update-->
                                <div class="col-sm-12 col-md-6 nonDisplay" id="metal_screw_post_div">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('type of custom post') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="metal_screw_post_update" name="metal_screw_post"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1"></option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6" id="type_crown_material_div">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('type of crown material') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="crown_material_update" name="crown_material"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('color') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="pro_color_update" name="pro_color"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>" multiple>
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('Pontic Design') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="pontic_design_update" name="pontic_design"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1">PreFibricated Post</option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('Impression Technique') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="impression_techniq_update"
                                            name="impression_technique"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1">PreFibricated Post</option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('Impression material') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="impression_techniq_update"
                                            name="Impression_material"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1">PreFibricated Post</option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">

                                        <label class="form-label">
                                            <?= ucwords('Cement Material') ?>
                                        </label>
                                        <!-- this is an important select tag remember it -->
                                        <select id="content_material_update" name="content_material"
                                            class="form-control select2-show-search form-select"
                                            data-placeholder="<?= $ci->lang('select') ?>">
                                            <option label="<?= $ci->lang('select') ?>"></option>

                                            <option value="1">PreFibricated Post</option>
                                            <option value="2">Custom Post</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Pro modal picture update -->
            <div class="col-md-2">

                <div class="modal-image-area">
                    <h2 class="modal-Title"></h2>
                    <img src="<?= $ci->dentist->assets_url() ?>assets/images/tooth/v2/up/PNG/8upup.png"
                        class="modalimg" id="modalImage2">
                    <div>
                        <label class="form-label">
                            <?= $ci->lang('pay amount') ?>
                        </label>
                        <input type="text" id="priceTag_resto" class="form-control" name="total_price">
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>