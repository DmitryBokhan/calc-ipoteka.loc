<?php

use iteush\Router;

Router::add('^admin/?$', ['controller' => 'Main', 'action' => 'index', 'admin_prefix' => 'admin']);
Router::add('^admin/(?<controller>[a-z-]+)/?(?<action>[a-z-]+)?$', ['admin_prefix' => 'admin']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']); // Главная страница

Router::add('^(?<controller>[a-z-]+)/(?<action>[a-z-]+)/?$'); // общее правило для всех маршрутов типа (например: page/views)

