Как встроить виджет в сайт:

1. в хтмл - div 'widget-phone' (для нетката своя верстка!)

2. js: jquery.cookie.js
       jquery.standart_load.js
	   jquery.standart_window.js
	   jquery.standart_widgetPhone.js
	   jquery-1.11.0.min.js
	   
4. в common.js
	инициализация(можно настроить время появления виджета, всплывающего окна и продолжительности куки, 
	необходима ф-я message
	   
4. css: ВСПЛЫВАЮЩЕЕ ОКНО
        WIDGET-PHONE
		FORM
		
5. img - все из папки

6. если сайт без нетката, то нужна папка PHPMailer, JSON и widgetPhoneMailer.php

7. если неткат - то необходимо создать компонент JSON -> Виджет-телефон такой же, как на cms-nc