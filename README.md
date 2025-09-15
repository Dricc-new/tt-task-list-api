# Technical Test - Task List API (Laravel)

Este proyecto es una API para la gestión de tareas, desarrollada en **Laravel**, con soporte para tareas, keywords asociadas y control de estado de completado. Incluye validación, manejo de errores y protección básica contra abusos mediante **Rate Limiting** de 120 solicitudes por minuto.

---

## 🚀 Especificaciones del proyecto

- Laravel 12+
- Base de datos: SQLite/MySQL/PostgreSQL  
- Middleware de validación para `TaskRequest` en creación y actualización  
- Relaciones: Task → Keywords (muchos a muchos)  
- Endpoints seguros con **Rate Limiting** de 120 requests por minuto
- Respuestas en **JSON** para integración con frontend (Quasar + Vue 3)  
- Manejo de errores estandarizado para:
  - 404 → Recurso no encontrado
  - 500 → Error interno del servidor  
  
---

## 📦 Instalación y arranque (Windows/Linux/MacOS)

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Dricc-new/tt-task-list-api.git
   cd laravel-task-api
   ````

2. Instala dependencias:

   ```bash
   composer install
   ```

3. Copia el archivo de entorno:

   ```bash
   cp .env.example .env
   ```

4. Configura la base de datos en `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_db
   DB_USERNAME=usuario
   DB_PASSWORD=contraseña
   ```

5. Genera la clave de aplicación:

   ```bash
   php artisan key:generate
   ```

6. Ejecuta las migraciones:

   ```bash
   php artisan migrate
   ```

7. (Opcional) Seeders para datos de prueba:

   ```bash
   php artisan db:seed
   ```

8. Levanta el servidor de desarrollo:

   ```bash
   php artisan serve
   ```

   Por defecto esto iniciará la API en `http://127.0.0.1:8000`.

---

## 🔗 Endpoints disponibles

| Método | URL                  | Descripción                                                      |
| ------ | -------------------- | ---------------------------------------------------------------- |
| GET    | `/keywords`          | Lista todas las keywords                                         |
| POST   | `/keywords`          | Crea una nueva keyword (`name`)                                  |
| GET    | `/tasks`             | Lista todas las tareas con sus keywords                          |
| POST   | `/tasks`             | Crea una nueva tarea (requiere `title` y `keyword_ids`)          |
| PATCH  | `/tasks/{id}`        | Actualiza una tarea existente (requiere `title` y `keyword_ids`) |
| PATCH  | `/tasks/{id}/toggle` | Cambia el estado de is_done de la tarea                          |
| DELETE | `/tasks/{id}`        | Elimina una tarea por su ID                                      |

> Todos los endpoints responden con JSON.
> Los errores retornan un `status` adecuado (`404` o `500`) y un mensaje descriptivo.

---

## 📊 Ejemplos de payload

### Crear Keyword
#### Payload
```json
POST /keywords
{
  "name": "Urgente"
}
```
#### Respuesta
```json
{
  "id": "9a328c24-024a-473c-a4ab-8ba32bb51c68",
  "name": "Urgente",
  "created_at": "2025-09-14T14:39:19.000000Z",
  "updated_at": "2025-09-14T14:39:19.000000Z"
}
```

### Listar Keywords
#### Payload
```json
GET /keywords
```
#### Respuesta
```json
[
  {
    "id": "9a328c24-024a-473c-a4ab-8ba32bb51c68",
    "name": "Urgente",
    "created_at": "2025-09-14T14:39:19.000000Z",
    "updated_at": "2025-09-14T14:39:19.000000Z"
  },
  {
    "id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f",
    "name": "Trabajo",
    "created_at": "2025-09-14T14:39:19.000000Z",
    "updated_at": "2025-09-14T14:39:19.000000Z"
  },
  ...
]
```

### Crear tarea
#### Payload
```json
POST /tasks
{
  "title": "Nueva tarea",
  "keyword_ids": ["9a328c24-024a-473c-a4ab-8ba32bb51c68", "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f"]
}
```
#### Respuesta
```json
{
  "message": "Task created successfully",
  "task":{
    "title": "Nueva tarea",
    "id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
    "updated_at": "2025-09-15T02:47:28.000000Z",
    "created_at": "2025-09-15T02:47:28.000000Z",
    "keywords": [
      {
        "id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f",
        "name": "Trabajo",
        "created_at": "2025-09-14T14:39:19.000000Z",
        "updated_at": "2025-09-14T14:39:19.000000Z",
        "pivot": {
          "task_id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
          "keyword_id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f"
        }
      },
      {
        "id": "9a328c24-024a-473c-a4ab-8ba32bb51c68",
        "name": "Urgente",
        "created_at": "2025-09-14T14:39:19.000000Z",
        "updated_at": "2025-09-14T14:39:19.000000Z",
        "pivot": {
          "task_id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
          "keyword_id": "9a328c24-024a-473c-a4ab-8ba32bb51c68"
        }
      }
    ]
  }
}
```

### Actualizar tarea
#### Payload
```json
PATCH /tasks/ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf
{
  "title": "Título actualizado",
  "keyword_ids": ["65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f"]
}
```
#### Respuesta
```json
{
  "message": "Task updated successfully",
  "task": {
    "id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
    "title": "Título actualizado",
    "is_done": 0,
    "created_at": "2025-09-15T02:47:28.000000Z",
    "updated_at": "2025-09-15T02:54:05.000000Z",
    "keywords": [
      {
        "id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f",
        "name": "Trabajo",
        "created_at": "2025-09-14T14:39:19.000000Z",
        "updated_at": "2025-09-14T14:39:19.000000Z",
        "pivot": {
          "task_id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
          "keyword_id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f"
        }
      }
    ]
  }
}
```

### Listar tareas
#### Payload
```json
GET /tasks
```
#### Respuesta
```json
[ 
  {
    "id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
    "title": "Título actualizado",
    "is_done": 0,
    "created_at": "2025-09-15T02:47:28.000000Z",
    "updated_at": "2025-09-15T02:54:05.000000Z",
    "keywords": [
      {
        "id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f",
        "name": "Trabajo",
        "created_at": "2025-09-14T14:39:19.000000Z",
        "updated_at": "2025-09-14T14:39:19.000000Z",
        "pivot": {
          "task_id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
          "keyword_id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f"
        }
      }
    ]
  },
  ...
]
```

### Toggle 
#### Payload
```json
PATCH /tasks/ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf/toggle
```
#### Respuesta
```json
{
  "message": "Task toggle successfully",
  "task": {
    "id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
    "title": "Título actualizado",
    "is_done": true,
    "created_at": "2025-09-15T02:47:28.000000Z",
    "updated_at": "2025-09-15T03:02:59.000000Z",
    "keywords": [
      {
        "id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f",
        "name": "Trabajo",
        "created_at": "2025-09-14T14:39:19.000000Z",
        "updated_at": "2025-09-14T14:39:19.000000Z",
        "pivot": {
          "task_id": "ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf",
          "keyword_id": "65f6cb9d-2a53-4b17-9df8-ddb4cfa81a0f"
        }
      }
    ]
  }
}
```

### Eliminar tarea
#### Payload
```json
DELETE /tasks/ce7afdfe-fc6f-4714-a8d9-e6f3f3ebfaaf
```
#### Respuesta
```json
{
  "message": "Task deleted successfully"
}
```
