<h1>Пользователь не найден</h1>
<p>Через 5 секунд вы будете перенаправлены к списку пользователей. Если это не произойдет автоматически, щелкните <a href="<?php echo URL; ?>/user/all">здесь</a>.</p>
<script>
    function redirect() {
    	window.location=SITE_URL+"/user/all";
    }
    setTimeout(redirect, 5000);
</script>