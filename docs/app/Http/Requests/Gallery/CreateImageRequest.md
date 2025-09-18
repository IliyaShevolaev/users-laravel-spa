# CreateFileTemplateRequest

Request для создания или обновления шаблона файла.

#### Правила валидации:

name — required, string, max:255, уникальное значение в таблице file_templates (игнорирует текущий ID при обновлении, учитывает soft delete).

file_template — required, file(docx, pdf) при создании (если POST запрос)/nullable, при обновлении, max: 1 GB

#### Названия атрибутов:

file_template — локализованное название из trans.
