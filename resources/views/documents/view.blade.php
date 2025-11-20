<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Просмотр — {{ $document->title }}</title>
    <style>
        :root { --border:#e5e7eb; --muted:#6b7280; }
        html, body { margin:0; padding:0; height:100%; }
        body { display:flex; flex-direction:column; min-height:100vh; }
        .toolbar { padding:8px 12px; border-bottom:1px solid var(--border); display:flex; gap:8px; align-items:center; }
        .toolbar .spacer { flex:1; }
        .btn { appearance:none; border:1px solid var(--border); background:white; padding:6px 10px; border-radius:6px; cursor:pointer; text-decoration:none; color:inherit; }
        .pages { display:flex; flex-direction:column; gap:16px; padding:12px; }
        .page { border:1px solid var(--border); border-radius:8px; overflow:hidden; }
        .page-header { padding:6px 10px; font: 13px/1.2 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Arial; color: var(--muted); background:#fafafa; border-bottom:1px solid var(--border); }
        .page-view { height: 85vh; }
        iframe, object, embed { width:100%; height:100%; border:0; }
        @media (max-width: 768px) { .page-view { height: 70vh; } }
    </style>
</head>
<body>
<div class="toolbar">
    <strong>{{ $document->title }}</strong>
    <span class="spacer"></span>
    <a class="btn" href="{{ route('documents.print', $document) }}" target="_blank">Распечатать</a>
    <a class="btn" href="{{ route('documents.downloadAll', $document) }}" target="_blank">Скачать всё (ZIP)</a>
    @if (!empty($fileUrls))
        <a class="btn" href="{{ $fileUrls[0]['url'] }}" target="_blank">Скачать текущий</a>
    @endif
</div>

@if (empty($fileUrls))
    <div style="padding:16px;">Файлы не найдены. Обратитесь к администратору.</div>
@else
    <div class="pages">
        @foreach ($fileUrls as $i => $f)
            <div class="page">
                @if (!empty($f['title']))
                    <div class="page-header">Лист {{ $i+1 }} — {{ $f['title'] }}</div>
                @else
                    <div class="page-header">Лист {{ $i+1 }}</div>
                @endif
                <div class="page-view">
                    <iframe src="{{ $f['url'] }}"></iframe>
                </div>
            </div>
        @endforeach
    </div>
@endif

</body>
</html>
