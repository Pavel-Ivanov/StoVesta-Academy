<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Печать — {{ $document->title }}</title>
    <style>
        html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
        .toolbar { padding:8px; background:#f5f5f5; border-bottom:1px solid #ddd; display:flex; gap:8px; align-items:center; }
        .page { height: 100vh; }
        .page + .page { page-break-before: always; }
        iframe, object, embed { height: 100%; width: 100%; border: 0; }
        .page-title { font: 14px/1.2 sans-serif; color:#555; padding:6px 8px; background:#fafafa; border-bottom:1px solid #eee; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="toolbar no-print">
    <strong style="margin-right:auto;">{{ $document->title }}</strong>
    <button onclick="window.print()" style="padding:6px 12px;">Распечатать</button>
    @if (!empty($fileUrls))
        <a href="{{ $fileUrls[0]['url'] }}" target="_blank" style="padding:6px 12px;">Открыть оригинал</a>
    @endif
</div>

@if (!empty($fileUrls))
    @foreach ($fileUrls as $i => $f)
        <div class="page">
            @if (!empty($f['title']))
                <div class="page-title">{{ $f['title'] }}</div>
            @endif
            <iframe src="{{ $f['url'] }}"></iframe>
        </div>
    @endforeach
@else
    <div style="padding:16px;">Файлы для печати не найдены.</div>
@endif

<script>
    // Автозапуск печати после загрузки
    window.addEventListener('load', () => {
        setTimeout(() => window.print(), 600);
    });
</script>
</body>
</html>
