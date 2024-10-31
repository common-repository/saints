# WordPress Plugin Saints
* Biography of Saints for any date. For Orthodox sites using WordPress.
* Описание жития Святых для сайтов православной тематики.

## Contents

The WordPress Plugin Saints includes the following files:

* `CHANGELOG.md`. The list of changes to the core project.
* `README.md`. The file that you’re currently reading.
* A `Saints` directory that contains the source code - a fully executable WordPress plugin.

## Features

* The Saints is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to change.

## Installation

* The Saints can be installed directly into your plugins folder "as-is".
* Change the name of the folder with the plugin to "Saints" if it is different

## WordPress.org Preparation

The original launch of this version of the Saints included the folder structure needed for using your plugin on WordPress.org. That folder structure has been moved to its own repo here: https://github.com/Siamajor/Saints

## License

The WordPress Plugin Saints is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

# Credits

The WordPress Plugin Saints was started in 2021 by [Igor Soloshenko](https://github.com/Siamajor) and has since included a number of great contributions. 
The current version of the Saints using API https://azbyka.ru/ 

## Documentation, FAQs, and More

-- UA --
* Плагін замислювався як доповнення до сторінки "Цей день в календарі", але може використовуватися і самостійно.
* Плагін виводить за допомогою шорткода [saints] життєописи Святих на будь-яку дату (з віджетом вбудованого календаря (шорткод [s-calendar]), або календаря плагіна *Bg Orthodox Calendar*, або будь-якого іншого елемента календаря з форматом дати YYYY-MM-DD) .
* Для отримання даних використовується API сайту *azbyka.ru* на зазначений день, тому тексти будуть лише російською (Використовувати Google перекладач або будь-який інший машинний переклад для церковних текстів неможливо через жахливи помилки, що призводять до спотворення змісту). Application Programming Interface сайту *azbyka.ru* не вимагає введення логіна, пароля або інших умов. Ознайомитися з повним списком послуг API можна за адресою https://azbyka.ru/days/site/api_help. В плагіні Saints використовується API https://azbyka.ru/days/api/saints/-дата-/group.json - набір святих для заданої дати, святі об'єднані в групи, а саме масиви: імена святих; назва мініатюр ікон святих; текст опису житія Святих; ідентифікатор Святого; заголовки тропарів, кондаків і молитов; тексти тропарів, кондаків і молитов;
* Мініатюри ікон святих завантажуються з адреси https://azbyka.ru/days/assets/img/saints/["saint_id"]/["image"] для відповідної дати, мають атрибут "nofollow".
* Для виведення короткої інформації на конкретну дату використовується https://azbyka.ru/days/api/presentations/-дата-.json - html-блок для заданої дати, масив: ["presentations"].
* Всі посилання на місця з Біблії та ін. мають атрибут nofollow.
* Якщо не встановлено віджет календаря, будуть виводитися дані на поточний день.

* З метою зменшення навантаження на сервер і прискорення завантаження сторінки ви можете скачати собі архів підготовлених мініатюр ікон святих і завантажити його в директорію /wp-content/uploads/saints-cache/ в форматі .zip. При виборі відповідного джерела картинок на сторінці налаштувань плагіна архів буде розпакований в директорію /wp-content/uploads/saints-cache/img/
* Адреса для скачування архіву img.zip (82.66 мб): https://xn--b1aplbci.xn--j1amh/vebmasteru/
* Плагін російською та українською мовами

-- RU --
* Плагин задумывался как дополнение к странице "Этот день в календаре", но может использоваться и самостоятельно.
* Плагин выводит с помощью шорткода [saints] жизнеописания Святых на любую дату (с виджетом встроенного календаря  (шорткод [s-calendar]), либо календаря плагина *Bg Orthodox Calendar*, либо любого другого виджета календаря с форматом даты YYYY-MM-DD) .
* Для получения данных используется API сайта *azbyka.ru* на указанный день. Application Programming Interface сайта *azbyka.ru* не требует ввода логина, пароля или других условий. Ознакомиться с полным списком предоставляемых API можно по адресу https://azbyka.ru/days/site/api_help В плагине Saints используется API https://azbyka.ru/days/api/saints/-дата-/group.json - набор святых для заданной даты, святые объединены в группы, а конкретно массивы: имена Святых; название миниатюр икон Святых; текст описания жития Святых; идентификатор Святого; заголовки тропарей, кондаков и молитв; тексты тропарей, кондаков и молитв; 
* Миниатюры икон Святых загружаются с адреса https://azbyka.ru/days/assets/img/saints/["saint_id"]/["image"] для соответствующей даты, имеют атрибут "nofollow".
* Для вывода краткой информации на конкретную дату используется https://azbyka.ru/days/api/presentations/-дата-.json - html-блок для заданной даты, массив: ["presentations"].
* Все ссылки на места из Библии и др. имеют атрибут nofollow.
* Если не установлен виджет календаря, будут выводиться данные на текущий день.

* В целях уменьшения нагрузки на сервер и ускорения загрузки страницы вы можете скачать себе архив подготовленных миниатюр икон Святых и загрузить его в директорию /wp-content/uploads/saints-cache/  в формате .zip. При выборе соответствующего источника картинок на странице настроек плагина архив будет распакован в директорию /wp-content/uploads/saints-cache/img/ 
* Адрес для скачивания архива img.zip (82.66 мб): https://xn--b1aplbci.xn--j1amh/vebmasteru/
* Плагин на русском и украинском языках