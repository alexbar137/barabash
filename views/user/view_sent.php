<h1>Сообщение отправлено</h1>
	<p>Сообщение успешно отправлено. Через 5 секунд вы автоматически перейдете к списку пользователей. Если это не произойдет автоматически, щелкните <a href="<?php echo URL;?>/user/all">здесь</a>.</p>
<script>
    function redirect() {
    	window.location=SITE_URL+"/user/all";
    }
    setTimeout(redirect, 5000);
</script>