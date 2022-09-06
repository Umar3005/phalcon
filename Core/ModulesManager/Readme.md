# Modules Manager

---

## Назначение модуля

Модуль отвечает за парсинг файлов модулей

---

## Структура директорий модуля

```bash
├── ModulesManager
│  ├── Core
├── Model
│  ├── Module
│  ├── WebApi
```

---

## Ключевые классы модуля
* `ModuleRegistrationAbstract` загружает данные о модулей из `Registration.php` файлов модуля;
* `ModulesManagerAbstract` - Обработчик `.xml` файлов;
* `ModulesLoader` и `WebApiLoader` классы отвечают за парсинг `module.xml` и `webapi.xml` файлов соответственно.

#### P.S.
Как видно из метода `\Core\ModulesManager\Model\WebApi\WebApiLoader::getModuleByNamespace`
Название модуля должно соответствовать пространству имён