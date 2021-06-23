<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Resminamalar dolanşygy</title>

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/papers.css') }}" rel="stylesheet">
</head>
<body>

<div id="app">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">Resminalar toplumy</a>
    <div class="navbar-nav mx-auto">
      <router-link to="/" tag="a" class="nav-item nav-link" exact>Esasy sahypa</router-link>
      <router-link to="/panel" tag="a" class="nav-item nav-link">Dolandyryş paneli</router-link>
    </div>
  </nav>

  <div class="contents">
    <router-view></router-view>
  </div>

  <footer class="bg-dark">
    <div class="text-white">
      &copy; 2020 HNGU
    </div>
  </footer>

  <div id="stars"></div>
  <div id="stars2"></div>
  <div id="stars3"></div>
  <div id="stars4"></div>
</div>

<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
