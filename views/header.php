<!DOCTYPE html>
<html>
<head>
<meta content=text/html charset=utf-8>
<title>%%TITLE%%</title>
<link rel="stylesheet" type="text/css" href="%%URL%%/public/css/style.css"/>
<script src="%%URL%%/public/js/jquery-2.1.1.min.js"></script>
<script src="%%URL%%/public/js/script.js"></script>
</head>
<body>
<div id="header">
	<ul id="header_items">
	<li><a href="%%URL%%/article/all">Новости</a>
		<ul>
			%%CATEGORIES%%
		</ul>
	</li>
	<li><a href="%%URL%%/forum">Форум</a>
		<ul>
			<li><a href="#">Обсуждения</a></li>
			<li><a href="#">Комментарии</a></li>
		</ul>
	</li>
    %%USERS%%
    </ul>
	<div id="login">
	%%AUTH_TEXT%%
	</div>
</div>
<div id="center" align="center">