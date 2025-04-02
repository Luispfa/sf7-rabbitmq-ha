# Bilingual README / README Bilingüe

[![Symfony](https://img.shields.io/badge/Symfony-7.0-000000?style=flat-square&logo=symfony)](https://symfony.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php)](https://www.php.net)
[![RabbitMQ](https://img.shields.io/badge/RabbitMQ-Latest-FF6600?style=flat-square&logo=rabbitmq)](https://www.rabbitmq.com)
[![Redis](https://img.shields.io/badge/Redis-Latest-DC382D?style=flat-square&logo=redis)](https://redis.io)
[![Docker](https://img.shields.io/badge/Docker-Required-2496ED?style=flat-square&logo=docker)](https://www.docker.com)

**This repository is bilingual. Below you will find the English version followed by the Spanish version.  
Este repositorio es bilingüe. A continuación encontrarás la versión en inglés seguida de la versión en español.**

---

<div style="display: block; text-align: center;">
<img src="youtube-banner.png" alt="@fitCoding - RabbitMQ - Domain Event" style="max-width: 30%; float: left; margin-right: 20px;">

  <div style="max-width: 70%; text-align: left;">
    <strong style="font-size: 30px; font-weight: bold;">Proyecto "RabbitMQ - Redis - Domain Event"</strong>
    <br>
    <span style="font-size: 24px;">Software Development with Symfony 7, PHP 8.3, RabbitMQ latest, Redis latest and Hexagonal Architecture</span>
    <br>
    <span style="font-size: 24px;">Desarrollo de Software con Symfony 7, PHP 8.3, RabbitMQ latest, Redis latest y Arquitectura Hexagonal</span>
  </div>
</div>
<p></p>

## 📑 Table of Contents / Índice

- [Requirements / Requisitos](#-requirements--requisitos)
- [Installation / Instalación](#-installation--instalación)
- [Configuration / Configuración](#-configuration--configuración)
- [Usage / Uso](#-usage--uso)
- [Architecture / Arquitectura](#-architecture--arquitectura)
- [Troubleshooting / Solución de Problemas](#-troubleshooting--solución-de-problemas)
- [Contributing / Contribuir](#-contributing--contribuir)
- [Security / Seguridad](#-security--seguridad)

## 🔧 Requirements / Requisitos

### System Requirements / Requisitos del Sistema

- Docker >= 20.10
- Docker Compose >= 2.0
- Git >= 2.30

### Development Requirements / Requisitos de Desarrollo

- PHP >= 8.3
- Composer >= 2.0
- Symfony CLI >= 5.0

### Environment Variables / Variables de Entorno

Create a `.env` file in the root directory with the following variables:
Crea un archivo `.env` en el directorio raíz con las siguientes variables:

```env
# Application
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=your_secret_here

# RabbitMQ
RABBITMQ_DEFAULT_USER=guest
RABBITMQ_DEFAULT_PASS=guest
MESSENGER_TRANSPORT_DSN=amqp://guest:guest@rabbitmq:5672/%2f/messages

# Redis
REDIS_URL=redis://redis:6379
```

## English

# Symfony Project with RabbitMQ and Redis in Hexagonal Architecture

This project implements a **messaging system** with **RabbitMQ** and **Redis** in a **Symfony** environment, following the **Hexagonal Architecture** and using **domain events** to **decouple** **business logic** from **infrastructure**. Domain events are triggered within the application core when significant changes occur and are processed by **specific handlers**, allowing, for example, counters or other processes to be updated **asynchronously**.

### Message Module

Additionally, the **Message** module sends messages directly without domain events.

### User Module

Whereas the **User** module utilizes domain events. For example, when a user registers, a **UserRegisteredEvent** is triggered to update the gender count. After this event is processed, a **UserEmailSentEvent** is dispatched to confirm the user's registration via email.

#### What happens when a user registers?

1. The user is created in the application.
2. A domain event (**UserRegisteredEvent**) is triggered to update the gender count.
3. The event is sent to RabbitMQ via Symfony Messenger.
4. A **specific handler** (**UserRegisteredEventHandler**) processes the event and updates a counter (via a file, database, etc.).
5. After processing **UserRegisteredEvent**, a new event **UserEmailSentEvent** is triggered to notify the user via email.
6. **UserEmailSentEvent** is sent to RabbitMQ and processed by its corresponding handler.
7. Ensure the consumer is running to process the events:

   ```bash
   php bin/console messenger:consume async user_registered user_email_sent -vv
   ```

   _(If all events are routed to the same transport, the worker will consume and execute the **handlers** accordingly.)_

---

## 🚀 Installation Steps

### 1️⃣ Clone the Repository and Navigate to the Folder

```bash
git clone https://github.com/Luispfa/sf7-rabbitmq-ha.git
cd rabbit-mq
```

### 2️⃣ Start Services with Docker

```bash
docker-compose up -d --build
```

### 3️⃣ Access the PHP Container:

```bash
docker exec -it sf7_php_ha bash
```

### 4️⃣ Install Symfony Dependencies

```bash
php composer install
```

### 5️⃣ Check if RabbitMQ and Redis Ports are Open:

```bash
nc -zv sf7_rabbitmq_ha 15672
nc -zv sf7_redis_ha 6379
```

### 6️⃣ Make an HTTP Request to the RabbitMQ API to Get Detailed Status Information:

```bash
curl -u guest:guest http://sf7_rabbitmq_ha:15672/api/overview
```

### 7️⃣ Run the RabbitMQ Consumer

```bash
php bin/console messenger:consume async user_registered user_email_sent -vv
```

### 8️⃣ Run the RabbitMQ Consumer in Daemon Mode:

```bash
php bin/console messenger:consume async user_registered user_email_sent --daemon
```

### 9️⃣ If You Are on Windows, Add the Following Line to Your C:\Windows\System32\drivers\etc\hosts File:

```
127.0.0.1 dev.rabbit-mq.com
```

---

## Changes in messenger.yml

The `messenger.yml` configuration file has been updated to define dedicated queues for **UserRegisteredEvent** and **UserEmailSentEvent**.

Updated configuration in `messenger.yml`:

```yaml
framework:
  messenger:
    transports:
      async:
        dsn: "%env(MESSENGER_TRANSPORT_DSN)%"
        options:
          exchange:
            name: messages
            type: direct
          queues:
            messages: ~
      user_registered:
        dsn: "%env(MESSENGER_TRANSPORT_DSN)%"
        options:
          exchange:
            name: user_events
            type: direct
          queues:
            user_registered_queue: ~
      user_email_sent:
        dsn: "%env(MESSENGER_TRANSPORT_DSN)%"
        options:
          exchange:
            name: email_events
            type: direct
          queues:
            user_email_sent_queue: ~

    routing:
      'App\Message\Domain\Message\Message': async
      'App\User\Domain\Event\UserRegisteredEvent': user_registered
      'App\User\Domain\Event\UserEmailSentEvent': user_email_sent
```

---

## Sending a Test Message

### Sending a Message to the async Queue

- **Method:** POST
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Headers:** `Content-Type: application/json`
- **Body (JSON):**

```json
{
  "message": "Hello RabbitMQ!"
}
```

### Registering a User and Triggering Events

- **Method:** POST
- **URL:** `http://dev.rabbit-mq.com/register-user`
- **Headers:** `Content-Type: application/json`
- **Body (JSON):**

```json
{
  "name": "Juan",
  "lastname": "Flores",
  "gender": "Male",
  "email": "email@email.com"
}
```

---

## 🐇 Accessing RabbitMQ and Redis

- RabbitMQ is accessible at `http://dev.rabbit-mq.com:15672/` with username **guest** and password **guest**.
- Redis can be accessed at `http://dev.rabbit-mq.com:8081/`.
- If you are on Windows, add the following line to your C:\Windows\System32\drivers\etc\hosts file:

```
127.0.0.1 dev.rabbit-mq.com
```

---

## 🏗️ Architecture / Arquitectura

### Hexagonal Architecture Overview / Visión General de la Arquitectura Hexagonal

```
src/
├── Message/                 # Message Module / Módulo de Mensajes
│   ├── Application/        # Application Services / Servicios de Aplicación
│   ├── Domain/            # Domain Logic / Lógica de Dominio
│   └── Infrastructure/    # Infrastructure Implementation / Implementación de Infraestructura
└── User/                   # User Module / Módulo de Usuarios
    ├── Application/       # Application Services / Servicios de Aplicación
    ├── Domain/           # Domain Logic / Lógica de Dominio
    └── Infrastructure/   # Infrastructure Implementation / Implementación de Infraestructura
```

### Event Flow / Flujo de Eventos

```mermaid
sequenceDiagram
    participant Client
    participant Controller
    participant Domain
    participant RabbitMQ
    participant Handler

    Client->>Controller: POST /register-user
    Controller->>Domain: Create User
    Domain->>Domain: Trigger UserRegisteredEvent
    Domain->>RabbitMQ: Send Event
    RabbitMQ->>Handler: Process Event
    Handler->>Domain: Update Counters
    Domain->>Domain: Trigger UserEmailSentEvent
    Domain->>RabbitMQ: Send Event
    RabbitMQ->>Handler: Process Event
    Handler->>Client: Send Email
```

## 🔍 Troubleshooting / Solución de Problemas

### Common Issues / Problemas Comunes

1. **RabbitMQ Connection Issues / Problemas de Conexión con RabbitMQ**

   - Check if RabbitMQ is running: `docker ps | grep rabbitmq`
   - Verify ports are open: `nc -zv sf7_rabbitmq_ha 5672`
   - Check RabbitMQ logs: `docker logs sf7_rabbitmq_ha`

2. **Redis Connection Issues / Problemas de Conexión con Redis**

   - Check if Redis is running: `docker ps | grep redis`
   - Verify Redis connection: `redis-cli -h sf7_redis_ha ping`
   - Check Redis logs: `docker logs sf7_redis_ha`

3. **Consumer Issues / Problemas con el Consumidor**
   - Ensure consumer is running: `ps aux | grep messenger:consume`
   - Check consumer logs: `docker logs sf7_php_ha`
   - Restart consumer if needed: `php bin/console messenger:consume async user_registered user_email_sent -vv`

## 🤝 Contributing / Contribuir

1. Fork the repository / Haz un fork del repositorio
2. Create your feature branch / Crea tu rama de características
3. Commit your changes / Haz commit de tus cambios
4. Push to the branch / Haz push a la rama
5. Create a new Pull Request / Crea un nuevo Pull Request

## 🔒 Security / Seguridad

### Best Practices / Mejores Prácticas

1. **Environment Variables / Variables de Entorno**

   - Never commit `.env` files / Nunca commits archivos `.env`
   - Use different secrets for development and production / Usa diferentes secretos para desarrollo y producción
   - Rotate secrets regularly / Rota los secretos regularmente

2. **API Security / Seguridad de la API**

   - Use HTTPS in production / Usa HTTPS en producción
   - Implement rate limiting / Implementa límites de tasa
   - Validate all input / Valida todas las entradas

3. **Message Queue Security / Seguridad de Cola de Mensajes**
   - Use strong passwords / Usa contraseñas fuertes
   - Enable SSL/TLS / Habilita SSL/TLS
   - Monitor queue access / Monitorea el acceso a las colas

## Español

# Proyecto Symfony con RabbitMQ y Redis en Arquitectura Hexagonal

Este proyecto implementa un **sistema de mensajería** con **RabbitMQ** y **Redis** en un entorno **Symfony**, siguiendo la **Arquitectura Hexagonal** y utilizando **eventos de dominio** para **desacoplar** la **lógica de negocio** de la **infraestructura**. Los eventos de dominio se activan en el núcleo de la aplicación cuando ocurren cambios significativos y son procesados por **manejadores** específicos, permitiendo, por ejemplo, actualizar contadores u otros procesos de manera **asíncrona**.

### Módulo Mensaje

Además, el módulo **Mensaje** envía mensajes directamente sin eventos de dominio.

### Módulo Usuario

Mientras que el módulo **Usuario** utiliza eventos de dominio. Por ejemplo, cuando un usuario se registra, se activa un **UserRegisteredEvent** para actualizar el conteo de género. Después de que se procesa este evento, se envía un **UserEmailSentEvent** para confirmar el registro del usuario por correo electrónico.

#### ¿Qué ocurre cuando un usuario se registra?

1. Se crea el usuario en la aplicación.
2. Se dispara un evento de dominio (**UserRegisteredEvent**) para actualizar el conteo de género.
3. El evento se envía a RabbitMQ a través de Symfony Messenger.
4. Un **manejador** específico (**UserRegisteredEventHandler**) procesa el evento y actualiza un contador (mediante un archivo, base de datos, etc.).
5. Después de procesar **UserRegisteredEvent**, se activa un nuevo evento **UserEmailSentEvent** para notificar al usuario por correo electrónico.
6. **UserEmailSentEvent** se envía a RabbitMQ y es procesado por su manejador correspondiente.
7. Asegúrate de que el consumidor esté en ejecución para procesar los eventos:

   ```bash
   php bin/console messenger:consume async user_registered user_email_sent -vv
   ```

   _(Si todos los eventos se enrutan al mismo transporte, el trabajador los consumirá y ejecutará los **manejadores** correspondientes.)_

---

## 🚀 Pasos de Instalación

### 1️⃣ Clonar el Repositorio y Navegar a la Carpeta

```bash
git clone https://github.com/Luispfa/sf7-rabbitmq-ha.git
cd rabbit-mq
```

### 2️⃣ Iniciar los Servicios con Docker

```bash
docker-compose up -d --build
```

### 3️⃣ Acceder al Contenedor PHP:

```bash
docker exec -it sf7_php_ha bash
```

### 4️⃣ Instalar Dependencias de Symfony

```bash
php composer install
```

### 5️⃣ Verificar si los Puertos de RabbitMQ y Redis están Abiertos:

```bash
nc -zv sf7_rabbitmq_ha 15672
nc -zv sf7_redis_ha 6379
```

### 6️⃣ Realizar una Petición HTTP a la API de RabbitMQ para Obtener Información Detallada del Estado:

```bash
curl -u guest:guest http://sf7_rabbitmq_ha:15672/api/overview
```

### 7️⃣ Ejecutar el Consumidor de RabbitMQ

```bash
php bin/console messenger:consume async user_registered user_email_sent -vv
```

### 8️⃣ Ejecutar el Consumidor de RabbitMQ en Modo Daemon:

```bash
php bin/console messenger:consume async user_registered user_email_sent --daemon
```

### 9️⃣ Si Estás en Windows, Agrega la Siguiente Línea a tu Archivo C:\Windows\System32\drivers\etc\hosts:

```
127.0.0.1 dev.rabbit-mq.com
```

---

## Cambios en messenger.yml

El archivo de configuración `messenger.yml` ha sido actualizado para definir colas dedicadas para **UserRegisteredEvent** y **UserEmailSentEvent**.

Configuración actualizada en `messenger.yml`:

```yaml
framework:
  messenger:
    transports:
      async:
        dsn: "%env(MESSENGER_TRANSPORT_DSN)%"
        options:
          exchange:
            name: messages
            type: direct
          queues:
            messages: ~
      user_registered:
        dsn: "%env(MESSENGER_TRANSPORT_DSN)%"
        options:
          exchange:
            name: user_events
            type: direct
          queues:
            user_registered_queue: ~
      user_email_sent:
        dsn: "%env(MESSENGER_TRANSPORT_DSN)%"
        options:
          exchange:
            name: email_events
            type: direct
          queues:
            user_email_sent_queue: ~

    routing:
      'App\Message\Domain\Message\Message': async
      'App\User\Domain\Event\UserRegisteredEvent': user_registered
      'App\User\Domain\Event\UserEmailSentEvent': user_email_sent
```

---

## Enviar un Mensaje de Prueba

### Enviando un Mensaje a la Cola async

- **Método:** POST
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Encabezados:** `Content-Type: application/json`
- **Cuerpo (JSON):**

```json
{
  "message": "¡Hola RabbitMQ!"
}
```

### Registrando un Usuario y Activando Eventos

- **Método:** POST
- **URL:** `http://dev.rabbit-mq.com/register-user`
- **Encabezados:** `Content-Type: application/json`
- **Cuerpo (JSON):**

```json
{
  "name": "Juan",
  "lastname": "Flores",
  "gender": "Male",
  "email": "email@email.com"
}
```

---

## 🐇 Acceso a RabbitMQ y Redis

- RabbitMQ es accesible en `http://dev.rabbit-mq.com:15672/` con usuario **guest** y contraseña **guest**.
- Redis puede ser accedido en `http://dev.rabbit-mq.com:8081/`.
- Si estás en Windows, agrega la siguiente línea a tu archivo C:\Windows\System32\drivers\etc\hosts:

```
127.0.0.1 dev.rabbit-mq.com
```

---

## 🏗️ Arquitectura

### Visión General de la Arquitectura Hexagonal

```
src/
├── Message/                 # Módulo de Mensajes
│   ├── Application/        # Servicios de Aplicación
│   ├── Domain/            # Lógica de Dominio
│   └── Infrastructure/    # Implementación de Infraestructura
└── User/                   # Módulo de Usuarios
    ├── Application/       # Servicios de Aplicación
    ├── Domain/           # Lógica de Dominio
    └── Infrastructure/   # Implementación de Infraestructura
```

### Flujo de Eventos

```mermaid
sequenceDiagram
    participant Client
    participant Controller
    participant Domain
    participant RabbitMQ
    participant Handler

    Client->>Controller: POST /register-user
    Controller->>Domain: Create User
    Domain->>Domain: Trigger UserRegisteredEvent
    Domain->>RabbitMQ: Send Event
    RabbitMQ->>Handler: Process Event
    Handler->>Domain: Update Counters
    Domain->>Domain: Trigger UserEmailSentEvent
    Domain->>RabbitMQ: Send Event
    RabbitMQ->>Handler: Process Event
    Handler->>Client: Send Email
```

## 🔍 Solución de Problemas

### Problemas Comunes

1. **Problemas de Conexión con RabbitMQ**

   - Verificar si RabbitMQ está ejecutándose: `docker ps | grep rabbitmq`
   - Verificar si los puertos están abiertos: `nc -zv sf7_rabbitmq_ha 5672`
   - Revisar los logs de RabbitMQ: `docker logs sf7_rabbitmq_ha`

2. **Problemas de Conexión con Redis**

   - Verificar si Redis está ejecutándose: `docker ps | grep redis`
   - Verificar la conexión con Redis: `redis-cli -h sf7_redis_ha ping`
   - Revisar los logs de Redis: `docker logs sf7_redis_ha`

3. **Problemas con el Consumidor**
   - Asegurar que el consumidor está ejecutándose: `ps aux | grep messenger:consume`
   - Revisar los logs del consumidor: `docker logs sf7_php_ha`
   - Reiniciar el consumidor si es necesario: `php bin/console messenger:consume async user_registered user_email_sent -vv`

## 🤝 Contribuir

1. Haz un fork del repositorio
2. Crea tu rama de características
3. Haz commit de tus cambios
4. Haz push a la rama
5. Crea un nuevo Pull Request

## 🔒 Seguridad

### Mejores Prácticas

1. **Variables de Entorno**

   - Nunca commits archivos `.env`
   - Usa diferentes secretos para desarrollo y producción
   - Rota los secretos regularmente

2. **Seguridad de la API**

   - Usa HTTPS en producción
   - Implementa límites de tasa
   - Valida todas las entradas

3. **Seguridad de Cola de Mensajes**
   - Usa contraseñas fuertes
   - Habilita SSL/TLS
   - Monitorea el acceso a las colas
