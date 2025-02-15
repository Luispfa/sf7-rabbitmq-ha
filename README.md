# Proyecto Symfony con RabbitMQ en Arquitectura Hexagonal

Este proyecto implementa un sistema de mensajería con RabbitMQ en un entorno Symfony, siguiendo la Arquitectura Hexagonal.

## 🚀 Pasos para la Instalación

### 1️⃣ Clonar el Repositorio y Moverse a la Carpeta
```bash
git clone https://github.com/Luispfa/sf7-rabbitmq-ha.git
cd rabbit-mq
```

### 2️⃣ Levantar los Servicios con Docker
```bash
docker-compose up -d --build
```

Ingresar al contenedor de PHP:
```bash
docker exec -it sf7_php_ha bash
```

### 3️⃣ Instalar Dependencias de Symfony
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

### 4️⃣ Ejecutar el Consumidor de RabbitMQ
```bash
php bin/console messenger:consume async
```

Ejecutar el Consumidor de RabbitMQ en segundo plano:
```bash
php bin/console messenger:consume async --daemon
```

### 5️⃣ Enviar un Mensaje de Prueba
Puedes enviar un mensaje con `curl`:
```bash
curl -X POST http://localhost:8000/send-message -H "Content-Type: application/json" -d '{"message": "Hola RabbitMQ! Luis"}'
```

### 🐇 Acceso a RabbitMQ
- RabbitMQ estará accesible en `http://dev.rabbit-mq.com:15672/` con usuario **guest** y contraseña **guest**.
- Si usas Windows, añade la siguiente línea en tu archivo `C:\Windows\System32\drivers\etc\hosts`:
  ```
  127.0.0.1 dev.rabbit-mq.com
  ```

## 📌 Configuración de Postman
Para enviar mensajes desde Postman:
- **Método:** `POST`
- **URL:** `http://localhost:8000/send-message`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
      "message": "Hola RabbitMQ! Luis"
  }
  ```

