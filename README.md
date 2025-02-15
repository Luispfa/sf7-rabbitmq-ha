# Proyecto Symfony con RabbitMQ en Arquitectura Hexagonal

Este proyecto implementa un sistema de mensajería con RabbitMQ en un entorno Symfony, siguiendo la Arquitectura Hexagonal.

## 🚀 Pasos para la Instalación

### 1⃣ Clonar el Repositorio y Moverse a la Carpeta

```bash
git clone https://github.com/Luispfa/sf7-rabbitmq-ha.git
cd rabbit-mq
```

### 2⃣ Levantar los Servicios con Docker

```bash
docker-compose up -d --build
```

Ingresar al contenedor de PHP:

```bash
docker exec -it sf7_php_ha bash
```

### 3⃣ Instalar Dependencias de Symfony

```bash
php composer install
```

Verificar si el puerto 15672 de RabbitMQ está abierto en el host `sf7_rabbitmq_ha`:

```bash
nc -zv sf7_rabbitmq_ha 15672
```

Hacer una consulta HTTP a la API de RabbitMQ para obtener información detallada sobre su estado:

```bash
curl -u guest:guest http://sf7_rabbitmq_ha:15672/api/overview
```

### 4⃣ Ejecutar el Consumidor de RabbitMQ

```bash
php bin/console messenger:consume async
```

Ejecutar el Consumidor de RabbitMQ en segundo plano:

```bash
php bin/console messenger:consume async --daemon
```

### 5⃣ Enviar un Mensaje de Prueba

#### 📩 Enviar Mensaje a la Cola `async`

Con `curl`:

```bash
curl -X POST http://localhost:8000/send-message -H "Content-Type: application/json" -d '{"message": "Hola RabbitMQ! Luis"}'
```

Con Postman:

- **Método:** `POST`
- **URL:** `http://localhost:8000/send-message`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "message": "Hola RabbitMQ! Luis"
  }
  ```

#### 👤 Registrar un Usuario

Con `curl`:

```bash
curl -X POST http://localhost:8000/register-user -H "Content-Type: application/json" -d '{"name": "Juan", "email": "juan@example.com", "gender": "male"}'
```

Con Postman:

- **Método:** `POST`
- **URL:** `http://localhost:8000/register-user`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "name": "Juan",
    "email": "juan@example.com",
    "gender": "male"
  }
  ```

#### 📊 Contar Usuarios por Género

Con `curl`:

```bash
curl -X GET http://localhost:8000/gender-count
```

Con Postman:

- **Método:** `GET`
- **URL:** `http://localhost:8000/gender-count`

### 🐇 Acceso a RabbitMQ

- RabbitMQ estará accesible en `http://dev.rabbit-mq.com:15672/` con usuario **guest** y contraseña **guest**.
- Si usas Windows, añade la siguiente línea en tu archivo `C:\Windows\System32\drivers\etc\hosts`:
  ```
  127.0.0.1 dev.rabbit-mq.com
  ```
