# Tasker
The test job.

## Demo
http://supertest123.zzz.com.ua/

## Requirements
* PHP 7.1 with GD extension
* MySQL 5.x
* Apache 2 with htaccess support
* Linux is recommended

## Installation
1. Clone this project onto your dev machine
2. Create MySQL database and user (if required)
3. Copy ```app/config/db.example.php``` to ```app/config/db.php``` and edit according to database from previous step
4. Grant write permissions to folder ```web/img```
5. Run ```composer install```
6. Run ```app/console db:migrate``` from console
7. Run ```php -S localhost:8000 -t web``` indicates php to use ```web``` folder as document root.

## Admin zone
* Admin credentials is ```admin``` and ```123```

## Implementation notes
This application built using DDD (Domain Driving Development) principle. There is Domain part and Application part locates in ```src/Tasker/Domain``` and ```src/Tasker/Application``` respectively.

MVC's controllers not interacting with infrastructure directly. Instead of this controllers runs Application part. Application part works with Infrastructure.

Application core built over the Pimple container, allows easy dependency injection on demand.

## Test Job Description (in russian)
Создать приложение-задачник.

Задачи состоят из:
- имени пользователя;
- е-mail;
- текста задачи;
- картинки;

Стартовая страница - список задач с возможностью сортировки по имени пользователя, email и статусу. Вывод задач нужно сделать страницами по 3 штуки (с пагинацией). Видеть список задач и создавать новые может любой посетитель без регистрации.

Перед сохранением новой задачи можно нажать "Предварительный просмотр", он должен работать без перезагрузки страницы.

К задаче можно прикрепить картинку. Требования к изображениям - формат JPG/GIF/PNG, не более 320х240 пикселей. При попытке загрузить изображение большего размера, картинка должна быть пропорционально уменьшена до заданных размеров.

Сделайте вход для администратора (логин "admin", пароль "123"). Администратор имеет возможность редактировать текст задачи и поставить галочку о выполнении. Выполненные задачи в общем списке выводятся с соответствующей отметкой.

В приложении нужно с помощью чистого PHP реализовать модель MVC. Фреймворки PHP использовать нельзя, библиотеки - можно. Верстка на bootstrap. К дизайну особых требований нет, должно выглядеть аккуратно.

Результат нужно развернуть на любом бесплатном хостинге, (как пример - zzz.com.ua) чтобы можно было посмотреть его в действии. На github.com или bitbucket.org выкладывать не обязательно.

Для того, чтобы мы могли проверить код, пожалуйста, скопируйте в корневую папку проекта наш онлайн-редактор dayside (https://github.com/boomyjee/dayside). Таким образом редактор будет доступен по url <ваш проект>/dayside/index.php. Нужно дать PHP доступ на исполнение и запись к папке dayside. Попробуйте открыть dayside сами - вы должны увидеть код своего приложения. При первом запуске редактор попросит установить пароль: пожалуйста, поставьте как в админке "123".
