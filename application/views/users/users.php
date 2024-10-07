  <?php $ci = get_instance(); ?>
  <!-- Start::row-1 -->
  <div class="row">
    <div class="col-xxl-3">
      <div class="card custom-card overflow-hidden">
        <div class="card-body border-bottom">
          <div class="d-sm-flex main-profile-cover">
            <span class="avatar avatar-xxl online me-3">
              <img src="../assets/images/faces/5.jpg" alt="" class="avatar avatar-xxl" />
            </span>
            <div class="flex-fill main-profile-info my-auto">
              <h5 class="fw-semibold mb-1">Json Taylor</h5>
              <div>
                <p class="mb-1 text-muted">
                  Chief Executive Officer (C.E.O)
                </p>
                <p class="fs-12 op-7 mb-0">
                  <span class="me-3 d-inline-flex align-items-center"><i class="ri-building-line me-1 align-middle"></i>Georgia</span>
                  <span class="d-inline-flex align-items-center"><i class="ri-map-pin-line me-1 align-middle"></i>Washington D.C</span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0 main-profile-info">
          <div class="d-flex align-items-center justify-content-between w-100">
            <div class="py-3 border-end w-100 text-center">
              <p class="fw-bold fs-20 text-shadow mb-0">113</p>
              <p class="mb-0 fs-12 text-muted">Projects</p>
            </div>
            <div class="py-3 border-end w-100 text-center">
              <p class="fw-bold fs-20 text-shadow mb-0">12.2k</p>
              <p class="mb-0 fs-12 text-muted">Followers</p>
            </div>
            <div class="py-3 w-100 text-center">
              <p class="fw-bold fs-20 text-shadow mb-0">128</p>
              <p class="mb-0 fs-12 text-muted">Following</p>
            </div>
          </div>
        </div>
      </div>
      <div class="card custom-card">
        <div class="p-4 border-bottom border-block-end-dashed">
          <p class="fs-15 mb-2 me-4 fw-semibold">
            Contact Information :
          </p>
          <div class="text-muted">
            <p class="mb-3">
              <span class="avatar avatar-sm avatar-rounded me-2 bg-info-transparent">
                <i class="ri-mail-line align-middle fs-14"></i>
              </span>
              sonyataylor2531@gmail.com
            </p>
            <p class="mb-3">
              <span class="avatar avatar-sm avatar-rounded me-2 bg-warning-transparent">
                <i class="ri-phone-line align-middle fs-14"></i>
              </span>
              +(555) 555-1234
            </p>
            <div class="d-flex">
              <p class="mb-0">
                <span class="avatar avatar-sm avatar-rounded me-2 bg-success-transparent">
                  <i class="ri-map-pin-line align-middle fs-14"></i>
                </span>
              </p>
              <p class="mb-0">
                MIG-1-11, Monroe Street, Georgetown, Washington D.C,
                USA,20071
              </p>
            </div>
          </div>
        </div>
        <div class="p-4 border-bottom border-block-end-dashed d-flex align-items-center">
          <p class="fs-15 mb-2 me-4 fw-semibold">Social Networks :</p>
          <div class="btn-list mb-0">
            <button class="btn btn-sm btn-icon btn-info-light btn-wave waves-effect waves-light">
              <i class="ri-facebook-line"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-secondary-light btn-wave waves-effect waves-light">
              <i class="ri-twitter-line"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-warning-light btn-wave waves-effect waves-light">
              <i class="ri-instagram-line"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-success-light btn-wave waves-effect waves-light">
              <i class="ri-github-line"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-danger-light btn-wave waves-effect waves-light">
              <i class="ri-youtube-line"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xxl-9">
      <div class="row">
        <div class="col-xl-12">
          <div class="custom-card">
            <div class="card-body p-0">
              <div class="border-block-end-dashed bg-white rounded-2 p-2">
                <div>
                  <ul class="nav nav-pills nav-justified gx-3 tab-style-6 d-sm-flex d-block" id="myTab" role="tablist">
                    <li class="nav-item rounded" role="presentation">
                      <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts-tab-pane" type="button" role="tab" aria-controls="posts-tab-pane" aria-selected="true">
                        <i class="ri-bill-line me-1 align-middle d-inline-block fs-16"></i>functionality
                      </button>
                    </li>

                    <li class="nav-item rounded" role="presentation">
                      <button class="nav-link" id="posts-tab" data-bs-toggle="tab" data-bs-target="#pages-tab-pane" type="button" role="tab" aria-controls="pages-tab-pane" aria-selected="true">
                        <i class="ri-bill-line me-1 align-middle d-inline-block fs-16"></i>Pages
                      </button>
                    </li>

                  </ul>
                </div>
              </div>
              <div class="py-4">
                <div class="tab-content" id="myTabContent">
                  <!-- TODO: functionality div start-->
                  <div class="tab-pane fade p-0 border-0 active show" id="posts-tab-pane" role="tabpanel" aria-labelledby="posts-tab" tabindex="0">
                    <div class="row">
                      <!-- TODO: switches form -->
                      <form action="">
                        <!-- TODO projects Cards -->
                        <div class="col-md-12 task-card cannotChange">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info edn -->

                              <!-- TODO: the toggle start -->
                              <div style="display: flex; gap: 115px">
                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission1</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission2</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission3</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission4</label>
                                </div>
                              </div>

                              <!-- the toggle end -->
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 task-card">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info edn -->

                              <!-- TODO: the toggle start -->
                              <div style="display: flex; gap: 20px">
                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission1</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission2</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission3</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission3</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission3</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission3</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission3</label>
                                </div>

                                <div class="switch">
                                  <input role="switch" type="checkbox" class="switch-input" id="switchId" />
                                  <label class="switch-input-label">permission4</label>
                                </div>
                              </div>

                              <!-- the toggle end -->
                            </div>
                          </div>
                        </div>

                      </form>
                    </div>
                  </div>
                  <!-- TODO: functionality div start-->



                  <!-- TODO pages div start -->
                  <div class="tab-pane fade p-0 border-0" id="pages-tab-pane" role="tabpanel" aria-labelledby="pages-tab" tabindex="0">
                    <div class="row">
                      <!-- TODO: switches form -->
                      <form action="">
                      <div class="row">
                        <!-- TODO pages Cards -->
                        <div class="col-md-4 task-card">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info end -->

                              <!-- the checkmark div -->
                              <div class="checkbox-wrapper-44" style="scale: 2; padding-top: 13px;">
                                <label class="toggleButton">
                                  <!-- <input type="checkbox" /> -->
                                  <input type="checkbox" name="checkbox1" class="checkbox" value="restorative" checked />
                                  <div>
                                    <svg viewBox="0 0 44 44">
                                      <path d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758" transform="translate(-2.000000, -2.000000)"></path>
                                    </svg>
                                  </div>
                                </label>
                              </div>
                              <!-- the checkmark div -->

                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 task-card">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info end -->

                              <!-- the checkmark div -->
                              <div class="checkbox-wrapper-44" style="scale: 2; padding-top: 13px;">
                                <label class="toggleButton">
                                  <!-- <input type="checkbox" /> -->
                                  <input type="checkbox" name="checkbox1" class="checkbox" value="restorative" checked />
                                  <div>
                                    <svg viewBox="0 0 44 44">
                                      <path d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758" transform="translate(-2.000000, -2.000000)"></path>
                                    </svg>
                                  </div>
                                </label>
                              </div>
                              <!-- the checkmark div -->

                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 task-card">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info end -->

                              <!-- the checkmark div -->
                              <div class="checkbox-wrapper-44" style="scale: 2; padding-top: 13px;">
                                <label class="toggleButton">
                                  <!-- <input type="checkbox" /> -->
                                  <input type="checkbox" name="checkbox1" class="checkbox" value="restorative" checked />
                                  <div>
                                    <svg viewBox="0 0 44 44">
                                      <path d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758" transform="translate(-2.000000, -2.000000)"></path>
                                    </svg>
                                  </div>
                                </label>
                              </div>
                              <!-- the checkmark div -->

                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 task-card">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info end -->

                              <!-- the checkmark div -->
                              <div class="checkbox-wrapper-44" style="scale: 2; padding-top: 13px;">
                                <label class="toggleButton">
                                  <!-- <input type="checkbox" /> -->
                                  <input type="checkbox" name="checkbox1" class="checkbox" value="restorative" checked />
                                  <div>
                                    <svg viewBox="0 0 44 44">
                                      <path d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758" transform="translate(-2.000000, -2.000000)"></path>
                                    </svg>
                                  </div>
                                </label>
                              </div>
                              <!-- the checkmark div -->

                            </div>
                          </div>
                        </div>

                        <div class="col-md-4 task-card">
                          <div class="card custom-card task-pending-card">
                            <div class="card-body" style="
                                        display: flex;
                                        align-items: center;
                                        gap: 100px;
                                      ">
                              <!-- info start -->
                              <div>
                                <h3>the title</h3>
                                <p>
                                  some details about the permission you
                                  gnna add
                                </p>
                              </div>
                              <!-- info end -->

                              <!-- the checkmark div -->
                              <div class="checkbox-wrapper-44" style="scale: 2; padding-top: 13px;">
                                <label class="toggleButton">
                                  <!-- <input type="checkbox" /> -->
                                  <input type="checkbox" name="checkbox1" class="checkbox" value="restorative" checked />
                                  <div>
                                    <svg viewBox="0 0 44 44">
                                      <path d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758" transform="translate(-2.000000, -2.000000)"></path>
                                    </svg>
                                  </div>
                                </label>
                              </div>
                              <!-- the checkmark div -->

                            </div>
                          </div>
                        </div>

                      </form>
                    </div>
                    </div>
                  </div>
                  <!-- TODO pages div start -->

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--End::row-1 -->