#Быстрый старт

Последовательность действий для создание сайта на базе быстрого старта

1\. Установка "чистого" ларавел 5.0


        composer create-project laravel/laravel ./ "~5.0.0"

---
2\. Установка QuickStorage пакета


    composer require interpro/quickstorage 1.0.x-dev

---
3\. Установка пакета для работы с изображениями 


    composer require interpro/imagefilelogic 1.0.x-dev

---
4\. Установка пакета для приема информации с модальных окон


    composer require interpro/fidback 1.0.x-dev

---
5\. Установка пакета генератора админ панели


    composer require interpro/admingenerator 1.0.x-dev

---
6\. ServicProvider

Необходимо в файле app.php (/config/app.php) в providers прописывать следующий провайдеры

    'Interpro\QuickStorage\QuickStorageServiceProvider',
    'Interpro\ImageFileLogic\ImageFileLogicServiceProvider',
    'Interpro\Fidback\FidbackServiceProvider',
    'Intervention\Image\ImageServiceProvider',
    'Interpro\AdminGenerator\AdminGeneratorServiceProvider'
    
Затем из консоли выполнить следующую команду 
```
    php artisan vendor:publish
```
---
7\. Config файлы проекта
    
- [Правила разметки конфига qstorage](https://github.com/KocaHocTpa/quickstart/blob/master/config.md)
- [Правила разметки конфига resize](http://example.com)
- [Правила разметки конфига fidback](http://example.com)

---
- qstorage.php - Основной файл конфига. В нем прописываются весь контент сайта.
    
- resize.php   - Конфиг для изображений на сайте. В нем прописываются ресайзы для изображений на сайта.
  А так же водяные знаки и маски для изображений.
  
- fidback.php  - Конфиг для обработки данных с модальных окон сайта.  