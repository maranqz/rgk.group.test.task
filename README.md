# Тестовое задание, часть 2 #

[Текст задания](https://github.com/maranqz/rkg.test.task/blob/master/task.pdf)

[Описание базы данных](database)

[Описание классов](class)

Будет создан Yii2 модуль.

### 1. Возможность привязки событий к любым моделям (триггерим события), отслеживание событий (слушаем события модели - сохранение, удаление).

Данный функционал реализован по средствам переопределения стандартного класса Component Yii2 и его метода init, в котором будет определяться необходимость привязки событий.
Данный способ выбран из-за того, что альтернативой является задания обработки для всех событий в БД, что является не нужным, т.к. при выполнении запроса PHP(Yii2), скорее всего не будет создавать и вызывать большую часть из имеющихся уведомлений, но будет привносить накладные расходы.

### 2. Возможность управления уведомлениями к событиям из веб-интерфейса.
- Выбор получателей (ролей/пользователей)
- Выбор типа уведомления (email и/или браузер).

Все параметры хранятся в таблице ntfctn_param, кроме шаблона, класса и события, к которому прикрепляется уведомление.
Каждый параметр имеет свой тип, один из которых определяет пользователя, роль. 

Для возможности CRUD уведомлений добавлены классы NotificationController, а для CRUD шаблонов и их предварительного вывода TemplateController

### 3. Реализовать возможность управления шаблонами текстов уведомлений с автоподстановкой туда информации из модели. Например, подстановка имени пользователя или ссылки на появившуюся новость в тексте и заголовке уведомления.(например: “Уважаемый, {username}! На нашем сайте {site_url} добавлена новая новость: <a href=”{new-link}”>{new-title}</a> <br> {new-description}”)

Для этого предназначен TemplateController::render(parameters), где parameters содержит пользователя, данные переданные в событие и данную переменную можно дополнительно расширить.

### 4. Предусмотреть возможность легкого добавления новых типов уведомлений. (например: telegram, sms или push).

Создан абстрактный класс Sending, с реализацией интерфейса отправителя, от которого можно создать другие типы отправки уведомлений.

### 5. Немедленная отправка уведомлений выбранным пользователям/ролям/всем по требованию администратора без события в модели (рассылка важных новостей партнерам)

Был создан метод actionExecute в классе NotificationController, который принимает id уведомления и данные, сходные с теми, что используются в шаблоне и могут быть получены при настоящей отправки уведомления