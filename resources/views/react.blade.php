<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  {{--  <link rel="manifest" href="/manifest.json">  --}}
  <link rel="shortcut icon" href="/favicon.ico">
  <title>{{ $title or 'Title' }}</title>
  <link rel="canonical" href="{{ url($path) }}">
  <meta name="title" content="{{ $title or 'Title' }}" />
  <meta name="referrer" content="unsafe-url">
  <meta name="description" content="{{ $description or 'Descripiton' }}">
  <meta name="theme-color" content="#000000">
  <meta property="og:title" content="{{ $title or 'Title' }}">
  <meta property="og:url" content="{{ url($path) }}">
  <meta property="og:image" content={{ $image or '' }}>
  <link href="/assets/css/github-markdown.css" rel="stylesheet">
  <link href="/assets/css/styles.css" rel="stylesheet">
  <link href="/static/css/main.3d2fc26c.css" rel="stylesheet">
</head>
<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root">
    <div class="container">
      <div id="markdown"></div>
      <article class="markdown-body">
        <h1>{{ $title or 'Title' }}</h1>
        <pre>{{ $description or 'Description' }}</pre>
      </article>
    </div>
    <div class="loading-page">
      <div class="bouncing-loader">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>
  <script src="/static/js/main.e4505255.js"></script>
</body>
</html>