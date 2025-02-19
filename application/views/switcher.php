
      <?php $ci = get_instance() ?>
      <!--Row-->
      <div class="container">
        <div class="row row-sm">
          <div class="col-xl-6 m-auto">
            <div class="card sidebar-right1">
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('navigation style') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('vertical menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch15" id="myonoffswitch34" class="onoffswitch2-checkbox" checked>
                      <label for="myonoffswitch34" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('horizontal click') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch15" id="myonoffswitch35" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch35" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('horizontal hover') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch15" id="myonoffswitch111" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch111" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body horizontal-switcher">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('horizontal style') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('default logo') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch9" id="default-logo" class="onoffswitch2-checkbox" checked>
                      <label for="default-logo" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('center logo') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch9" id="center-logo" class="onoffswitch2-checkbox">
                      <label for="center-logo" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('light theme style') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('light theme') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch1" id="myonoffswitch1" class="onoffswitch2-checkbox" checked>
                      <label for="myonoffswitch1" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('light primary') ?></span>
                    <div class="">
                      <input class="w-30p h-30 input-color-picker color-primary-light" value="#ed1940" id="colorID" oninput="changePrimaryColor()" type="color" data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" data-id7="transparentcolor" name="lightPrimary">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('dark theme style') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('dark theme') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch1" id="myonoffswitch2" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch2" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('dark primary') ?></span>
                    <div class="">
                      <input class="w-30p h-30 input-dark-color-picker color-primary-dark" value="#ed1940" id="darkPrimaryColorID" oninput="darkPrimaryColor()" type="color" data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" data-id3="primary" data-id8="transparentcolor" name="darkPrimary">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('transparent theme styles') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('transparent theme') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch1" id="myonoffswitchTransparent" class="onoffswitch2-checkbox">
                      <label for="myonoffswitchTransparent" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('transparent primary color') ?></span>
                    <div class="">
                      <input class="w-30p h-30 input-transparent-color-picker color-primary-transparent" value="#ed1940" id="transparentPrimaryColorID" oninput="transparentPrimaryColor()" type="color" data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" data-id3="primary" data-id4="primary" data-id9="transparentcolor" name="tranparentPrimary">
                    </div>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('transparent background color') ?></span>
                    <div class="">
                      <input class="w-30p h-30 input-transparent-color-picker color-bg-transparent" value="#ed1940" id="transparentBgColorID" oninput="transparentBgColor()" type="color" data-id5="body" data-id6="theme" data-id9="transparentcolor" name="transparentBackground">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('transparent bg-image styles') ?></h6>
                </div>
                <div class="skin-body switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('background image primary') ?></span>
                    <div class="">
                      <input class="w-30p h-30 input-transparent-color-picker color-primary-transparent" value="#ed1940" id="transparentBgImgPrimaryColorID" oninput="transparentBgImgPrimaryColor()" type="color" data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" data-id3="primary" data-id4="primary" data-id9="transparentcolor" name="tranparentPrimary">
                    </div>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <a class="bg-img1 me-2" href="javascript:void(0);" onclick="bgImage(this)"><img src="<?= $ci->dentist->assets_url() ?>assets/images/media/bg-img1.jpg" alt="Bg-Image" id="bgimage1" class="br-7"></a>
                    <a class="bg-img2 me-2" href="javascript:void(0);" onclick="bgImage(this)"><img src="<?= $ci->dentist->assets_url() ?>assets/images/gradients/3.jpg" alt="Bg-Image" id="bgimage2" class="br-7"></a>
                    <a class="bg-img3 me-2" href="javascript:void(0);" onclick="bgImage(this)"><img src="<?= $ci->dentist->assets_url() ?>assets/images/gradients/3-1.jpg" alt="Bg-Image" id="bgimage3" class="br-7"></a>
                    <a class="bg-img4 me-2" href="javascript:void(0);" onclick="bgImage(this)"><img src="<?= $ci->dentist->assets_url() ?>assets/images/gradients/2-3.jpg" alt="Bg-Image" id="bgimage4" class="br-7"></a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('left menu styles') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle lightMenu d-flex">
                    <span class="me-auto"><?= $ci->lang('light menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch3" class="onoffswitch2-checkbox" checked>
                      <label for="myonoffswitch3" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle colorMenu d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('color menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch4" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch4" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle darkMenu d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('dark menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch5" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch5" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle gradientMenu d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('gradient menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch19" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch19" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('header styles') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle lightHeader d-flex">
                    <span class="me-auto"><?= $ci->lang('light header') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch6" class="onoffswitch2-checkbox" checked>
                      <label for="myonoffswitch6" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle  colorHeader d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('color header') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch7" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch7" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle darkHeader d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('dark header') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch8" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch8" class="onoffswitch2-label"></label>
                    </p>
                  </div>

                  <div class="switch-toggle darkHeader d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('gradient header') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch20" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch20" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('layout width styles') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('full width') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch4" id="myonoffswitch9" class="onoffswitch2-checkbox" checked>
                      <label for="myonoffswitch9" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('boxed') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch4" id="myonoffswitch10" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch10" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('layout positions') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('fixed') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch5" id="myonoffswitch11" class="onoffswitch2-checkbox" checked>
                      <label for="myonoffswitch11" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('scrollable') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch5" id="myonoffswitch12" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch12" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body leftmenu-styles">
                <div>
                  <h6 class="main-content-label mb-3"><?= $ci->lang('side menu layout styles') ?></h6>
                </div>
                <div class="switch_section">
                  <div class="switch-toggle d-flex">
                    <span class="me-auto"><?= $ci->lang('default menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch13" class="onoffswitch2-checkbox default-menu" checked="">
                      <label for="myonoffswitch13" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('icon with text') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch14" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch14" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('icon overlay') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch15" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch15" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('closed side menu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch16" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch16" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('hover submenu') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch17" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch17" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('hover submenu style 1') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch18" class="onoffswitch2-checkbox">
                      <label for="myonoffswitch18" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('double menu style') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="doublemenu-switch" class="onoffswitch2-checkbox">
                      <label for="doublemenu-switch" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                  <div class="switch-toggle d-flex mt-2">
                    <span class="me-auto"><?= $ci->lang('double menu style 1') ?></span>
                    <p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="doublemenu-switch1" class="onoffswitch2-checkbox">
                      <label for="doublemenu-switch1" class="onoffswitch2-label"></label>
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="switch_section text-center px-0">
                  <div class="btn-list">
                    <button class="btn btn-success w-lg"><?= $ci->lang('save settings') ?></button>
                    <button class="btn btn-danger" onclick="localStorage.clear();
                                                    document.querySelector('html').style = '';
                                                    names() ;
                                                    resetData() ;" type="button"><?= $ci->lang('reset all') ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--End Row-->
