# Тестовое задание, часть 2, классы #

[Текст задания](https://github.com/maranqz/rkg.test.task/blob/master/task.pdf)

![Диаграмма классов](https://github.com/maranqz/rkg.test.task/blob/second_task/class/class.png)

- HistoryModel (ntfctn_history)

- TemplateModel (ntfctn_template)

- NotificationModel (ntfctn)
    - переопределяются setter и getter, что позволяет работать на прямую с параметрами уведомления.
    - getTemplate получает прикрепленный шаблон.
    - save переопределяется, для того, чтобы правильно сохранить и обработать параметры.
    - getConditions получает значения условных параметров (задается в переменной conditionParams) и создает sql запрос для их получения.

- ParamModel (ntfctn_params)

- NotificationController (CRUD уведомлений)
    - actionExecute вызывает уведомления по id, задавая для выполнения вместо реальных параметров фиктивный в web интерфейсе.

- TemplateController (CRUD шаблонов)
    - actionPreview вызывает шаблон по id, задавая набор фиктивных параметров, для того чтобы показать, как он будет выглядеть предварительно.

- NotificationComponent
    
    Переопределяет стандартный класс Component (через настройки ControllerMap), что позволяет прикреплять только те, уведомления, которые принадлежат данному классу (getNotifications - получение, навешивание setNotification функции sendNotification). Установка уведомления происходит в функции init.

- Sending (abstractClass)
    
    Определяет интерфейс реализации отправки, для того, чтобы можно было легко добавить новый способ доставки уведомления, не изменяя код вызова отправления.

- EmailSending (реализация Sending для отправки уведомлений по почте)

- BrowserSending (реализация Sending для отправки уведомлений в браузер)