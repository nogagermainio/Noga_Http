
## Noga http

├── Installation
├── Quick Start
├── Routes
├── Middleware
└── Cache

## Basic Route

```php
Routes::get('/')
    ->controller("Controller.index")
    ->name("home");
```

```php
Routes::get('/{id}/{slug}')
->controller("Controller.home")
->where([
    "id" => "\d+",
    "slug" => "\w+"
])
->name("parametrable");
```

# Noga_HTTP — Lightweight PHP HTTP Framework

A custom lightweight PHP HTTP framework built from scratch, designed to explore modern backend architecture concepts such as routing compilation, dependency injection, and middleware pipelines.

This project focuses on learning how real-world frameworks like Laravel and Symfony are internally structured.

---

## 🚀 Features

- ⚡ Compiled routing system (tree-based + optimized lookup)
- 🧭 Middleware pipeline architecture
- 🧩 Dependency Injection container with autowiring
- 🔄 Flexible controller resolution:
  - Closures
  - Global functions
  - Class methods
  - Invokable classes
- 📦 Route cache system (compiled definitions)
- 🌐 HTTP Request / Response lifecycle
- 🧠 Extensible and modular architecture

---

## 🏗️ Architecture Overview

