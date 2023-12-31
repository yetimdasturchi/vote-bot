<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
   	<meta http-equiv="X-UA-Compatible" content="IE=edge">
   	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<script src="https://telegram.org/js/telegram-web-app.js"></script>
</head>
<body>
	<script>
		function isEmpty(obj) {
			for (var prop in obj) {
				if (Object.prototype.hasOwnProperty.call(obj, prop)) {
			   		return false;
				}
			}

			return true
		}

		let tg = window.Telegram.WebApp;
		tg.expand();

		if ( isEmpty( tg.initDataUnsafe ) ) {
        	location.href = "http://manu.uz";
      	}

   		tg.close();
	</script>
</body>
</html>