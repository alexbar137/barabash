<h1>Сообщение не отправлено</h1>
	<p>Что-то пошло не так, и нам не удалось отправить сообщение. Через 5 секунд вы будете перенаправлены к списку сообщений. Если это не произойдет автоматически, щелкните <a href="/message/all">здесь</a>.</p>
<script>
    function redirect() {
    	window.location=SITE_URL+"/message/all";
    }
    setTimeout(redirect, 5000);
</script>