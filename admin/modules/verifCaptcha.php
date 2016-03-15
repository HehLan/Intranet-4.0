<?php
require_once('recaptchalib.php');
$publickey = "6Lch0ukSAAAAAGXw7YmpEF_VuRVpWBVioL1uhUgK";
$privatekey = "6Lch0ukSAAAAAK6c6lVdWixXElmGlcuPv5MUj-8G";
# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;
$valid=false;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                # set the error code so that we can display it
                $valid=true;
        }
        else{
            $valid=false;
            $error = $resp->error;
        }
}
if($valid){
    echo('true');
}
else{
    echo('false ');
    echo($error);
}
?>