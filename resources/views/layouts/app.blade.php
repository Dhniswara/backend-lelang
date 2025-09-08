<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Demo Xendit' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#f6f7fb;color:#111}
    .container{max-width:960px;margin:32px auto;padding:0 16px}
    .card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 8px 24px rgba(0,0,0,.06)}
    .btn{display:inline-block;padding:10px 16px;border-radius:10px;border:0;background:#111;color:#fff;font-weight:600;cursor:pointer}
    .btn[disabled]{opacity:.6;cursor:not-allowed}
    .muted{color:#666}
    table{width:100%;border-collapse:collapse}
    th,td{padding:12px;border-bottom:1px solid #eee;text-align:left}
    .right{text-align:right}
    a.btn-link{color:#111;text-decoration:none;font-weight:600}
  </style>
</head>
<body>
  <div class="container">
    @yield('content')
  </div>
  @stack('scripts')
</body>
</html>