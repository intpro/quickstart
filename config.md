#Правила разметки конфига qstorage.php

- Имена блоков должны быть уникальные для всего конфига и начинаться с символа латинского алфавита
- Имена полей должны быть уникальными в пределах блока или группы и начинатся с латинского алфавита
- Имена групп должны быть уникальные для всего конфига и начинаться с символа латинского алфавита
- Имена групп, блоков и полей не должны использовать зарезервированные слова

##Список зарезервированных слов 
    stringfields
    textfields
    groups
    bools
    numbs
    images
    groups

##Поля для блоков
    
-title - текстовое поле содержащие имя блока. В конфиге указывается значение по умолчанию

    ' blockname ' => 
        'title' => 'Заголовок'
        
-stringfields - текстовое поле от 0 до 255 символов. В конфиге указываются все имена полей данного типа. 
 Поле используется для вывода коротких текстовых записей.
 
    ' blockname ' =>
        'stringfields' => ['example_var_1','example_var_2'],
        
-textfields  - текстовое поле от 0 дл 65к символов. В конфиге указываютсяя все имена полей данного типа.
 Поле используется для вывода объемных текстовых записей.
 
    ' blockname ' =>
        'textfields' => ['example_var_1','example_var_2'],
        
-numbs - целочисленное поле хранящие целые числа со знаком.

    ' blockname ' =>
        'numbs' => ['example_var_1','example_var_2'],
        
-bools - логическая переменная

    ' blockname ' =>
        'bools' => ['example_var_1','example_var_2'],
        
-images - Переменная для хранения изображений. Хранит путь до изображения а так же пути до всех его
 модифицированных копий.
 
    ' blockname ' =>
        'images' => ['example_var_1','example_var_2'],
        
-groups - специальное поле для групп принадлежащий блоку. Внутри этого поля описываются все группы данного блока.
## Поля для группы

Поля для групп аналогичны полям для блока. Помимо того у групп существует специальное поле для создание вложенных
групп. 

    owner
    
Пример использование owner

    'groups' =>[
        'group_parent => [
            ...
        ],
        'group_child' => [
            'owner' => 'group_parent'
            ...
        ]
    ]
    
Группы могут наследоваться только в пределах одного блока.
