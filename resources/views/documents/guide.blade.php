<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Инструкция по работе с системой документов</title>
    <style>
        :root { --text:#222; --muted:#666; --border:#e5e7eb; --bg:#ffffff; }
        html, body { margin:0; padding:0; background:var(--bg); color:var(--text); font: 14px/1.6 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Arial, "Apple Color Emoji", "Segoe UI Emoji"; }
        .container { max-width: 900px; margin: 0 auto; padding: 24px; }
        h1 { font-size: 26px; margin: 0 0 12px; }
        h2 { font-size: 18px; margin: 24px 0 8px; }
        p { margin: 8px 0; }
        ul { margin: 8px 0 8px 20px; }
        .lead { color: var(--muted); margin-bottom: 16px; }
        .toolbar { position: sticky; top:0; background: #f8fafc; border-bottom: 1px solid var(--border); padding: 10px 16px; display: flex; gap: 8px; align-items: center; z-index: 10; }
        .toolbar .spacer { flex: 1; }
        .btn { appearance: none; border: 1px solid var(--border); background: white; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .btn.primary { background: #111827; color: white; border-color: #111827; }
        .card { border: 1px solid var(--border); border-radius: 10px; padding: 16px; background: white; }
        .note { font-size: 12px; color: var(--muted); }
        .hr { height:1px; background: var(--border); margin: 16px 0; }
        code { background: #f3f4f6; border: 1px solid var(--border); padding: 1px 5px; border-radius: 4px; }
        @media print {
            .toolbar { display:none; }
            .container { max-width: 100%; padding: 0 20mm 20mm; }
            a { color: inherit; text-decoration: none; }
            .card { border: none; padding: 0; }
        }
    </style>
</head>
<body>
<div class="toolbar no-print">
    <strong>Инструкция по работе с системой документов</strong>
    <div class="spacer"></div>
    <button class="btn" onclick="window.print()">Скачать PDF</button>
</div>
<div class="container">
    <h1>Инструкция по работе с системой документов</h1>
    <p class="lead">Эта инструкция поможет вам быстро найти и использовать документы в панели студента. Для сохранения инструкции в формате PDF используйте кнопку «Скачать PDF» (будет открыт диалог печати браузера и выберите «Сохранить как PDF»).</p>

    <div class="card">
        <h2>1. Где найти документы</h2>
        <ul>
            <li>Войдите в систему и откройте Панель студента (Intern).</li>
            <li>В верхнем меню после пункта «Академия» доступны разделы:
                <ul>
                    <li>«Сертификаты» — документы типа certificate.</li>
                    <li>«Формы» — шаблоны и бланки для заполнения (form).</li>
                    <li>«Справочник» — полезные материалы и инструкции (handbook).</li>
                </ul>
            </li>
        </ul>

        <h2>2. Поиск и просмотр</h2>
        <ul>
            <li>В каждом разделе отображается таблица документов.</li>
            <li>Основные столбцы:
                <ul>
                    <li>«Название» — заголовок документа.</li>
                    <li>«Листов» — количество листов: показывает 1 для одиночного файла или одного листа, либо фактическое число для многостраничных документов.</li>
                    <li>«Добавлен» — дата и время добавления.</li>
                </ul>
            </li>
            <li>Доступные действия:
                <ul>
                    <li><strong>Просмотр</strong> — открывает документ в новой вкладке.</li>
                    <li><strong>Скачать</strong> — скачивает основной файл (или первый лист) документа.</li>
                    <li><strong>Распечатать</strong> — открывает специальную страницу печати для всех листов документа (в нужном порядке).</li>
                    <li><strong>Скачать всё</strong> — скачивает все листы документа одним ZIP-архивом.</li>
                </ul>
            </li>
        </ul>

        <h2>3. Многостраничные документы</h2>
        <ul>
            <li>Некоторые документы состоят из нескольких листов.</li>
            <li>Кнопка «Распечатать» выведет все листы подряд с разрывами страниц.</li>
            <li>«Скачать всё» сохранит все листы одним ZIP-архивом.</li>
            <li>«Просмотр» и «Скачать» работают с основным файлом документа: это либо одиночный файл, либо первый лист многостраничного документа.</li>
        </ul>

        <h2>4. Доступ к документам</h2>
        <ul>
            <li>Доступ определяется ролью пользователя (минимальный уровень доступа указан в документе):
                <ul>
                    <li>Документы с видимостью <code>all</code> доступны всем авторизованным пользователям панели студента.</li>
                    <li>Документы с видимостью «Студент», «Преподаватель», «Администратор» видны только пользователям с соответствующей ролью.</li>
                </ul>
            </li>
        </ul>

        <h2>5. Печать и сохранение в PDF</h2>
        <ul>
            <li>Откройте документ и нажмите «Распечатать» — в браузере появится диалог печати.</li>
            <li>В параметрах принтера выберите «Сохранить как PDF», чтобы сохранить документ или инструкцию в PDF-файл.</li>
            <li>Для многостраничных документов будут напечатаны все листы.</li>
        </ul>

        <h2>6. Частые вопросы</h2>
        <ul>
            <li><strong>Не вижу документ в списке:</strong>
                <ul>
                    <li>Убедитесь, что у вас есть нужная роль доступа, либо документ помечен как «all».</li>
                    <li>Проверьте, что вы находитесь в нужном разделе (Сертификаты / Формы / Справочник).</li>
                </ul>
            </li>
            <li><strong>Кнопка «Просмотр» не активна:</strong>
                <ul>
                    <li>У документа может отсутствовать файл и листы — обратитесь к администратору.</li>
                </ul>
            </li>
            <li><strong>Нужен весь документ одним файлом:</strong>
                <ul>
                    <li>Используйте «Скачать всё» для загрузки ZIP всех листов. Для объединённого PDF обратитесь к администратору.</li>
                </ul>
            </li>
        </ul>

        <h2>7. Поддержка</h2>
        <p>При проблемах с доступом или отображением файлов обратитесь к администратору системы. Сообщите название документа и раздел, в котором он расположен, а также приложите скриншот ошибки (если есть).</p>
    </div>

    <div class="note" style="margin-top:12px;">Веб‑версия: <code>{{ url('/documents/guide') }}</code>. Для сохранения этой страницы в PDF используйте кнопку сверху или сочетание клавиш печати в браузере.</div>
</div>
<script>
    // Ничего не делаем автоматически, пользователь сам запускает печать.
</script>
</body>
</html>
