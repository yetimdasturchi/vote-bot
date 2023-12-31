# vote-bot
Turli xil tanlovlar va so'rovnomalar o'tkazish uchun qilingan telegram bot


![cover](https://i.ibb.co/LJzps2B/image.png)

## Imkoniyatlari:

- Minimal no code konstruktor
- Dinamik maydonlar
- Vazifalar yuklash va shu orqali foydalanuvchilar ma'lumotlarini to'ldirish
- To'laqonli statistika
- User menejment
- Kanalx orqali so'rovnomalar amalga oshirish
- Web orqali so'rovnomalar amalga oshirish
...va yana ko'plab narsalar)

## Donating
O'zidan ketib bosar-tusarini bilmay qolganlar quyidagi metodlarda muallifni qo'llab quvvatlashlari mumkin :)

- [buymeacoffee](https://www.buymeacoffee.com/yetimdasturchi)
- [github](https://github.com/sponsors/yetimdasturchi)
- uzcard: `8600 4929 5502 3508`
- humo: `9860 6004 3619 2758`

## Talablar

- nginx yoki apache2
- php8.1 yoki undan yuqorisi
- mysql yoki undan yuqorisi
- cronjob sozlash imkoni

## O'rnatish

## Fayllar

Githubdan olingan barcha fayllarni domenning asosiy root papkasiga ko'chiring. Misol: `/var/www/html/`

## Server

Server sozlamalari uchun quyidagi havoladagi video bilan tanishib chiqishingiz mumkin, shundan so'ng sizdan nginx sozlamalarini o'zingizga moslashingiz kifoya:

Video uchun havola: [https://www.youtube.com/watch?v=L0XmC6RCA78](https://www.youtube.com/watch?v=L0XmC6RCA78)

### Nginx:
```nginx
location / {
	try_files $uri $uri/ /index.php;
}

location ~* \.php$ {
	include fastcgi.conf;
	fastcgi_pass unix:/var/run/php8.1-fpm.sock;
    #fastcgi_param CI_ENV 'production';
}
```

### Apache:
```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### SQL:
MySQL ma'lumotlar bazasida yangi baza ochib unga `data.sql` faylini yuklang. Shuningdek clients va managers jadvallaridan chat_id bandini o'zingizning telegram idenfikatoringizga almashtiring.

## Konfiguratsiya

`config.ini.sample` faylini `config.ini` nomiga almashtiring va quyidagi bandlarini o'zingizga moslang:

- db_hostname
- db_username
- db_password
- db_database
- base_url
- bot_token
- bot_username
- webhook_secret

*webhook_secret bandi istalga heshdan foydalanishingiz mumkin, misol md5.*

## Telegram hookni ulash

Telegram bot bilan ulanish qismi webhook versiyasi uchun moslashgan. Quyidagi misol tarzida berilgan havoladan `TOKENINGIZ`, `domain.uz` va `webhook_secret` (config.ini faylida qo'shilgan) bandlarini o'zingizga moslang. Shundan so'ng brauzer orqali shu havolaga yo'l oling.

```
https://api.telegram.org/botTOKENINGIZ/setwebhooki?url=https://domain.uz/hook?secret=webhook_secret
```

## Cronjob va Servislar

Bildirishnomalarni yuborish uchun alohida servis ochish eng maqul ish hisoblanadi. Bunda php fayl doimiy yuborish uchun bildirishnoma bor yoki yo'qligini tekshiradi va shunga qarab harakat qiladi. Shuningdek katta oqimdagi bildirishnomalarni yuborish uchun ham eng maqul yo'l.

Demak bu uchun `botnotifications.service` faylini yaratish va uning kontentiga quyidagi fayl kontentini joylash lozim

```bash
sudo nano /etc/systemd/system/botnotifications.service
```
botnotifications.service fayli kontenti:
```
[Unit]
Description=botnotifications service
After=mysql.service
StartLimitIntervalSec=0
[Service]
Type=simple
Restart=always
RestartSec=1
User=root
ExecStart=/usr/bin/php8.1  /var/www/html/index.php crone notifications

[Install]
WantedBy=multi-user.target
```
Shunday so'ng daemon qayta yuklanadi va servish ishga tushiriladi:

```bash
systemctl daemon-reload
systemctl enable botnotifications
systemctl start botnotifications
```

Cronjob fayliga:

```bash
* * * * * /usr/bin/php /var/www/html/index.php crone vote_queue > /dev/null 2>&1
*/15 * * * * /usr/bin/php /var/www/html/index.php crone poll_queue > /dev/null 2>&1
```


## Boshqaruv paneli

```
https://domain.uz/client
https://domain.uz/manage
```