<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Please Login | FDCMS</title>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="/css/app/login.css" />

<!-- jQuery and System Scripts -->
<script type="text/javascript" src="/js/app/jquery.1.11.1.min.js"></script>
<script type="text/javascript">

    function centerCard() {
       // center the login form
       var h = $('div.login-card').outerHeight();
       var w = $('div.login-card').outerWidth();
       
       var offsetY = h/2;
       var offsetX = w/2;
       
       console.log(offsetY);
       console.log(offsetX);
       
       $('div.login-card').css('margin-left','-'+offsetX+'px').css('margin-top','-'+offsetY+'px');   
    }

    $(document).ready(function() {
        centerCard();
        
        $(window).resize(centerCard());
    });
</script>


</head>

<body>
    
    <div class="login-card" style="position: absolute; top: 50%; left: 50%;">
        <h1><img src="/images/app/core/fdcms-logo.png" /></h1>
        <? if($msg != '') { echo $msg; } ?>
        <form action="/admin/login/process" method="POST">
        <input type="email" name="login-email" placeholder="Email" required>
        <input type="password" name="login-password" placeholder="Password" required>
        <input type="submit" name="login" class="login login-submit" value="Log In">
        </form>
    </div>


</body>
</html>