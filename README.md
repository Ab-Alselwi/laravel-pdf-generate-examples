
## كيف تقوم بتحويل ملف HTML إلى PDF عن طريق PHP 

  قرأت اسئلة كثيرة  عن افضل مكتبة تستخدم لتحويل صفحة  
  html إلى  pdf
  بال php، 
  وهناك قائمة طويلة بالمكتبات والباكجات التي تقوم بذلك، ولكن معظمها لا يدعم اللغة العربية، او لا يدعم التنسيقات وهناك مشاكل اخرى يواجهها الكثير, البعض يستطيع حلها وبعضهم لا. .

لذا قمت بكتابة المقال التالي    [ركيف تقوم بتحويل ملف HTML إلى PDF عن طريق PHP ](https://droub.net/blog/post/%D9%83%D9%8A%D9%81-%D8%AA%D9%82%D9%88%D9%85-%D8%A8%D8%AA%D8%AD%D9%88%D9%8A%D9%84-%D9%85%D9%84%D9%81-html-%D8%A5%D9%84%D9%89-pdf-%D8%B9%D9%86-%D8%B7%D8%B1%D9%8A%D9%82-php).لشرح كيفية استخدام بعض المكتبات والباكجات 

 تم  تنفيذ الأمثلة باستخدام  : 
 - wkhtmltopdf 
 - symfony process
 - php shell
 - chrome-php
 

## Requirements

Requires PHP 7.3-8.0|7.4 and a chrome/chromium 65+ executable.

note it dose not work correct in php 8
Note that the library is only tested on Linux but is compatible with MacOS and Windows.

## Installation

قم  بتنزيل الملفات ومن ثم نفذ  الأمر التالي : 
clone the repostory and by composer run 

```bash
$ composer update
```
ثم بعدها  

```bash
$ php artisan serve
```

الروابط التي يمكنك الانتقال اليها عن طريق المتصفح  :
- 127.0.0.1:8000/ باستخدام  HeadlessChromium 
- 127.0.0.1:8000/runProsses باستخدام  runProsses by php shell
- 127.0.0.1:8000/symfonyProcess باستخدام  symfonyProcess with wkhtmltopdf library 
- 127.0.0.1:8000/phpchromepdf باستخدام  dawood\phpChrome\Chrome package 

وغيرها يمكنك الإطلاع عليها في الكود 
route/web.php, 
HomeController 


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
