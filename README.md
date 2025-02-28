# Bilingual README / README Bilingüe

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

---

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

### 3️⃣ Ingresar al Contenedor PHP:

```bash
docker exec -it sf7_php_ha bash
```

### 4️⃣ Instalar Dependencias de Symfony

```bash
php composer install
```

### 5️⃣ Verificar si los puertos de RabbitMQ y Redis están abiertos:

```bash
nc -zv sf7_rabbitmq_ha 15672
nc -zv sf7_redis_ha 6379
```

### 6️⃣ Realizar una Petición HTTP a la API de RabbitMQ para obtener información detallada del estado:

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

### 9️⃣ Si estás en Windows, agrega la siguiente línea a tu archivo C:\Windows\System32\drivers\etc\hosts:

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
