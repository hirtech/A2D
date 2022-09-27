<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<div class="row vh-100 justify-content-between align-items-center" id="user_login">
    <div class="col-12">
        <form id="frmlogin" class="row row-eq-height lockscreen  mt-5 mb-5">
            <input type="hidden" name="login_flag" value="1">
            <input type="hidden" name="no_of_attempt" id="no_of_attempt" value="0">
            <div class="lock-image col-12 col-sm-5 text-center"><img src="{$site_url}assets/images/home-logo.png" width="200" class="mt-5"></div>
            <div class="login-form col-12 col-sm-7">
				<h3 class="text-center" id="county_head_lbl"></h3>
				<h6 class="text-center" id="county_head_url"></h6>
                <div class="alert alert-danger" id="msg" style="display:none;"></div>
                <div class="form-group mb-3">
                    <label for="emailaddress">User Name</label>
                    <input class="form-control" type="text" id="vUsername" name="vUsername" placeholder="User Name" required="" >
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" id="vPassword" name="vPassword"  required="" placeholder="Password">
                </div>
                <div class="form-group mb-0">
                    <button class="btn btn-primary" type="button" id="btn_login"> Log In </button>
                </div>
                <div class="form-group mt-5 text-center"><a href="{$domain_url}" target="_blank" title="{$COMPANY_COPYRIGHTS}">{$COMPANY_COPYRIGHTS}</a></div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
{literal}
$(document).ready(function () {
    $('#btn_login').click(function (event) {
        //alert(222)
        if ($('#vUsername').val() == "" || $('#vUsername').val() == "Username") {
            $('#msg').html("Please enter username").show();
            $('#vUsername').focus();
            return false;
        }
        if ($('#vPassword').val() == "" || $('#vPassword').val() == "Password") {
            $('#msg').html("Please enter password").show();
            $('#vPassword').focus();
            return false;
        }
        var dt = $('#frmlogin').serializeArray();
        loginFuction(dt);
        return false;
    });
});
$(document).on('keypress',function(e) {
    if(e.which == 13) {
        $("#btn_login").trigger("click");
    }
});

function loginFuction(loginData) {
	//alert(JSON.stringify(loginData));
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url +"home/login",
        data: loginData,
        success: function (data) {
            var login = data.login;
            var msg = data.error_msg;
            if (login == 1) {
                window.location = site_url ;
            }else {
                $('#msg').html(msg).show();
                return false;
            }
        }
    });
}
{/literal}
</script>

