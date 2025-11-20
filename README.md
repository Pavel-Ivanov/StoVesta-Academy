🎓 StoVesta Online Academy (b0lms)
About The Project
StoVesta Online Academy (b0lms) is a comprehensive internal platform developed specifically for StoVesta, combining the functions of a Learning Management System (LMS) and a Corporate Knowledge Base (Wiki).

The project's main goal is the holistic development of employees. The platform provides centralized training, testing, and skill improvement while serving as the single source of up-to-date corporate information. This ensures high operational standards and fosters continuous professional growth within the company.

✨ Key Features
Modular LMS System: Flexible creation, structuring, and updating of training courses, supporting various content types (video, documents, interactive assignments).

Automated Testing: Built-in tests and exams for objective knowledge assessment, with the option for instant feedback.

Knowledge Base / Wiki: A tool for creating and managing a corporate "Wikipedia," where employees can easily find regulations, instructions, standards, and other critical information.

Progress Tracking: Personal dashboards for employees and detailed analytical reports for management on performance, knowledge retention levels, and platform activity.

User and Role Management: Configuration of different access levels for administrators, instructors, and staff members.

Forums and Discussions: Tools for experience sharing and collaborative problem-solving on work-related issues.

Git — правила слияния веток (merge)
Чтобы сократить количество конфликтов при объединении веток, в репозиторий добавлены правила .gitattributes:
- Автоматическая нормализация концов строк: text=auto eol=lf
- Файлы блокировок (lock-файлы) — composer.lock, package-lock.json, yarn.lock, pnpm-lock.yaml — сливаются стратегией «ours». Это означает: при конфликте сохраняется версия текущей ветки. После слияния обязательно переустановите зависимости, чтобы пересобрать lock-файл под объединённое состояние:
  - PHP: composer install
  - Node: npm install или yarn install / pnpm install
- .env — merge=ours (конфликты настроек окружения не имеют смысла, каждый разработчик хранит локальные значения)
- Бинарные файлы помечены как binary (Git не будет пытаться объединять их как текст).

Рекомендации по рабочему процессу
- Перед началом работы обновляйтесь: git pull --rebase
- При возникновении конфликтов в lock-файлах просто завершайте слияние, затем выполните установку зависимостей (см. выше) и закоммитьте обновлённый lock-файл.
- Если конфликт всё же возник в текстовых файлах, используйте привычные инструменты сравнения/разрешения (IDE или git mergetool).

Как зафиксировать русский язык ответов ассистента
- Если вы используете ассистента в IDE/сервисе, где доступна конфигурация сессии, задайте:
  - language_detection: ru — основной параметр выбора языка.
  - preferred_response_language: ru — дополнительный параметр (если поддерживается вашим окружением).
- Альтернативно (или дополнительно) добавьте системную инструкцию в промпт окружения: «Всегда отвечай на русском языке».
- Подсказки по средам:
  - JetBrains IDEs (AI Assistant): в настройках ассистента укажите предпочитаемый язык ответа (ru) или добавьте системную подсказку для проекта.
  - CLI/боты: в конфигурационном файле или при инициализации сессии передавайте поля language_detection=ru и/или preferred_response_language=ru.
  - API: в метаданных запроса зафиксируйте язык (например, в system‑prompt или в полях запроса, если платформа их поддерживает).
