<?php $ci = get_instance(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lock Screen</title>

    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styleLockScreen.css">
</head>

<body>
    <div class="autentication-bg">
        <div class="container-lg">
            <div class="row justify-content-center authentication authentication-basic align-items-center h-100">
                <!-- TODO: glassMorphism section -->
                <section class="glassContainer">
                    <div class="login-container">
                        <div class="circle circle-one"></div>

                        <div class="line line-one"></div>
                        <div class="form-container">

                            <div class="imageContainer">
                                <img src="<?= base_url() ?>assets/images/spartan.jpg" alt="User Image" style="width: 110px; height: 110px; border-radius: 50%;">

                                <h1 class="opacity">USERNAME</h1>
                            </div>

                            <form>
                                <p><?= $ci->lang('unlockMessage') ?></p>
                                <input type="password" placeholder="PASSWORD" />
                                <button class="opacity"><?= $ci->lang('unlock') ?></button>
                            </form>
                            <div class="register-forget opacity">
                                <!-- <a href="">REGISTER</a> -->
                                <a href=""><?= $ci->lang('forget password') ?></a>
                            </div>
                        </div>
                        <div class="circle circle-two"></div>
                    </div>
                    <div class="theme-btn-container"></div>
                </section>
            </div>
        </div>
    </div>
</body>

</html>