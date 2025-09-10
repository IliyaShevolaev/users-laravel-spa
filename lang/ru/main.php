<?php

declare(strict_types=1);

return [
    'users' => [
        'list_header' => 'Список пользователей',
        'add_header' => 'Добавить пользователя',
        'edit_header' => 'Изменить пользователя',
        'name' => 'имя',
        'enter_name_placeholder' => 'Введите имя',
        'email' => 'почта',
        'enter_email_placeholder' => 'Введите почту',
        'password' => 'пароль',
        'enter_password_placeholder' => 'Введите пароль',
        'password_confirmation' => 'пароль',
        'enter_password_confirmation_placeholder' => 'Подтвердите пароль',
        'role' => 'роль',
        'created' => 'создан',
        'updated' => 'обнавлен',
        'acions' => 'действия',
        'create_user_button' => 'Создать пользователя',
        'edit_user_button' => 'Редактировать пользователя',
        'go_back_button' => 'Назад',
        'actions_buttons' => 'Действия',
        'department' => 'отдел',
        'without_department' => 'Без отдела',
        'departments' => 'Отделы',
        'add_department_header' => 'Добавить новый отдел',
        'edit_department_header' => 'Изменить отдел',
        'delete_department_alert' => 'Удалить отдел',
        'not_allowed_to_delete_department_alert' => 'Невозможно удалить отдел, пока есть прикрепленные к нему люди.',
        'position' => 'должность',
        'positions' => 'Должности',
        'add_position_header' => 'Добавить новую должность',
        'edit_position_header' => 'Изменить должность',
        'delete_position_alert' => 'Удалить должность',
        'without_position' => 'Без должности',
        'not_allowed_to_delete_position_alert' => 'Невозможно удалить должность, пока есть люди с этой должностью.',
        'empty_role' => 'Роль не назначена',
        'gender' => 'Пол',
        'genders' => [
            'male' => 'Мужчина',
            'female' => 'Женщина',
        ],
        'status' => 'Статус',
        'statuses' => [
            'active' => 'Активен',
            'unactive' => 'Не активен',
        ],
    ],

    'logs' => [
        "values" => [
            'male' => 'мужчина',
            'female' => 'женщина',
            'active' => 'активен',
            'unactive' => 'неактивен',
            'mark' => 'исполнил',
            'unmark' => 'не исполнил',
        ],

        'set_at' => 'установлено на',
        'changed_from' => 'изменено с',
        'on_empty_value' => 'на пустое значение',
        'to' => ' на ',
        'deleted_row' => "Запись была удалена",

        "fields" => [
            "Department" => ["name" => "Название"],
            "Position" => ["name" => "Название"],


            "User" => [
                "name" => "Имя",
                "email" => "Почта",
                "department" => ["name" => "Отдел"],
                "position" => ["name" => "Должность"],
                "event" => ["title" => "Назначен на событие"],
                "role" => "Роль",
                "gender" => "Пол",
                "status" => "Статус",
                "is_done" => "Отметка о выполнении",
                'field_values' => [
                    'male' => 'мужчина',
                    'female' => 'женщина',
                    'active' => 'активен',
                    'unactive' => 'неактивен',
                ],
            ],

            "Role" => [
                'display_name' => 'Название',
                'permissions' => [
                    'entity' => [
                        'users' => 'Пользователи',
                        'departments' => 'Отделы',
                        'positions' => 'Должности',
                        'roles' => 'Роли',
                        'tasks' => 'Задачи',
                    ],
                    'action' => [
                        'read' => 'Просмотр',
                        'create' => 'Создание',
                        'update' => 'Редактирование',
                        'delete' => 'Удаление',
                        'find' => 'Поиск',
                        'logs' => 'Просмотр логов',
                    ],
                ]
            ],

            'Event' => [
                'title' => 'Название',
                'description' => 'Описание',
                'start' => 'Начало',
                'end' => 'Окончание',
                'assigned_for' => 'Ответсвенный'
            ],

            'EventUser' => [
                'is_done' => 'Сообщение о выполнении',
            ]
        ]
    ],

    'cities' => [
        'city' => 'Город',
        'region' => 'Регион',
        'regions_sheet' => 'Регионы',
        'cities_sheet' => 'Города',
        'ip_start' => 'IP — начало',
        'ip_end' => 'IP — конец',
        'created' => 'Создан',
        'updated' => 'Обновлен',
    ],

    'file_templates' => [
        'file_template' => 'файл шаблона',
        'format' => 'формат файла',
        'user_id' => 'пользователь',
    ],

    'title' => 'название',
    'title_placeholder' => 'Введите название',
    'add_button' => 'Добавить',
    'edit_button' => 'Изменить',

    'id_not_found' => 'Удаление невозможно. Ошибка',

    'date_range' => 'даты события',
    'must_user_assign' => '"событие для"'
];
