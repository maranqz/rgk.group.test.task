# Тестовое задание, часть 2, база данных #

[Текст задания](https://github.com/maranqz/rkg.test.task/blob/master/task.pdf)

![База данных](https://github.com/maranqz/rkg.test.task/blob/second_task/database/database.png)

* Все связь между таблицами являются один ко многим

Данный способ представления базы данных выбран благодаря легкой расширяемости для добавления новых параметров.

Можно зафиксировать часть параметров (условие выбора пользователей), тем самым выиграв в производительности, но потеряв возможность просто добавлять новые параметры для отбора пользователей.

Дополнительно можно зафиксировать все параметры, а те, которые могут принимать несколько значений вынести в отдельные таблицы.


```sql
# Таблица хранит данные о уведомлениях
CREATE TABLE public.ntfctn (
	id integer NOT NULL,
	class varchar() NOT NULL,# Класс, которому принадлежит событие
	event varchar() NOT NULL,# Событие, при сробатывание которого будет отправлятся уведомление
	template_id integer NOT NULL,# Шаблон отправки, пункт 4-и задания
);

# Таблица параметров уведомлений.  
CREATE TABLE public.ntfctn_param (
	id integer NOT NULL,
	type_id integer NOT NULL, # Тип параметра, позволяет правильно обрабатывать и применять значение
	value varchar() NOT NULL, # Значение параметра
	ntfctn_id integer NOT NULL, # Уведомление, которому принадлежит параметр
);

# Таблица описывающая типы параметров
CREATE TABLE public.ntfctn_param_type (
	id integer NOT NULL,
	name varchar() NOT NULL, # Имя типа
	type varchar() NOT NULL, # Тип значения
	description text, # Описание
);

# Таблица хранит данные по шаблону вывода уведомления
CREATE TABLE public.ntfctn_template (
	id integer NOT NULL,
	name varchar() NOT NULL, # Название шаблона
	content text NOT NULL, # Содержание шаблона
);

# Таблица ведет историю отправок уведомлений
CREATE TABLE public.ntfctn_history (
	id integer NOT NULL,
	ntfctn_id  NOT NULL,# Определяет уведомление, которому принадлежит данная запись
	datetime timestamp without time zone NOT NULL,# Временная метка отправки уведомления
);

# Таблица определяет было ли прочитано уведомление пользователем или нет
CREATE TABLE public.ntfctn_readed (
	history_id integer NOT NULL, # Определяет отправленное уведомление
	user_id integer NOT NULL, # Определяет получателя уведомления
	readed boolean NOT NULL, # Пометка о том, что уведомление было прочитано
);
```