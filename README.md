# Bilingual README / README Bilingüe

**This repository is bilingual. Below you will find the English version followed by the Spanish version.  
Este repositorio es bilingüe. A continuación encontrarás la versión en inglés seguida de la versión en español.**

---

<div style="display: block; text-align: center;">
<img src="youtube-banner.png" alt="@fitCoding - RabbitMQ - Domain Event" style="max-width: 30%; float: left; margin-right: 20px;">

  <div style="max-width: 70%; text-align: left;">
    <strong style="font-size: 30px; font-weight: bold;">Proyecto "RabbitMQ - Domain Event"</strong>
    <br>
    <span style="font-size: 24px;">Desarrollo de Software con Symfony 7, PHP 8.3, RabbitMQ latest y Arquitectura Hexagonal</span>
  </div>
</div>
<p></p>

## English

# Symfony Project with RabbitMQ in Hexagonal Architecture

This project implements a **messaging system** with **RabbitMQ** in a **Symfony** environment, following **Hexagonal Architecture** and using **domain events** to **decouple** the **business logic** from the **infrastructure**. Domain events are triggered at the core of the application when significant changes occur and are processed by specific **handlers**, allowing, for example, counters or other processes to be updated **asynchronously**.

### Module `Message`

Additionally, the **Message** module sends messages directly without domain events,

### Módulo `User`

Whereas the **User** module uses domain events. For example, when a user registers, a **UserRegisteredEvent** (or **UserRegisteredEventHandler**) is triggered to update the gender count.

## 🚀 Installation Steps

### 1️⃣ Clone the Repository and Navigate to the Folder

```bash
git clone https://github.com/Luispfa/sf7-rabbitmq-ha.git
cd rabbit-mq
```

### 2️⃣ Start the Services with Docker

```bash
docker-compose up -d --build
```

### 3️⃣Enter the PHP container:

```bash
docker exec -it sf7_php_ha bash
```

### 4️⃣ Install Symfony Dependencies

```bash
php composer install
```

### 5️⃣Check if RabbitMQ's port 15672 is open on host `sf7_rabbitmq_ha`:

```bash
nc -zv sf7_rabbitmq_ha 15672
```

### 6️⃣ Make an HTTP request to the RabbitMQ API for detailed status information:

```bash
curl -u guest:guest http://sf7_rabbitmq_ha:15672/api/overview
```

### 7️⃣ Run the RabbitMQ Consumer

```bash
php bin/console messenger:consume async -vv
```

### 8️⃣ Run the RabbitMQ Consumer in daemon mode:

```bash
php bin/console messenger:consume async --daemon
```

### 9️⃣ If you are using Windows, add the following line to your `C:\Windows\System32\drivers\etc\hosts` file:

```
127.0.0.1 dev.rabbit-mq.com
```

### Send a Test Message

#### Sending a Message to the `async` Queue

- **Method:** `POST`
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "message": "Hello RabbitMQ!"
  }
  ```

---

## Domain Event Functionality: User Registration and Gender Count

The project includes functionality to register a user and automatically update a gender count via a **domain event** sent to RabbitMQ.

### Endpoint to Register a User

- **URL:** `POST http://dev.rabbit-mq.com/register-user`
- **Headers:**  
  `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "name": "Juan",
    "lastname": "Flores",
    "gender": "male"
  }
  ```

### What Happens When a User is Registered?

1. The user is created in the application.
2. A domain event (e.g., **UserRegisteredEvent**) is triggered to update the gender count.
3. The event is sent to RabbitMQ via Symfony Messenger.
4. A specific **handler** (e.g., **UserRegisteredEventHandler**) processes the event and updates a counter (using a file, database, etc.).
5. Make sure the consumer is running to process the event:
   ```bash
   php bin/console messenger:consume async
   ```
   _(If all events are routed to the same transport, the worker will consume them and execute the corresponding **handlers**.)_

---

## API Usage Examples

### Sending a Test Message

- **Method:** `POST`
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "message": "Hello RabbitMQ! Luis"
  }
  ```

---

### Registering a User and Updating Gender Count

- **Method:** `POST`
- **URL:** `http://dev.rabbit-mq.com/register-user`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "name": "Juan",
    "lastname": "Flores",
    "gender": "male"
  }
  ```

---

## 🐇 Access to RabbitMQ

- RabbitMQ is accessible at `http://dev.rabbit-mq.com:15672/` with username **guest** and password **guest**.
- If you are using Windows, add the following line to your `C:\Windows\System32\drivers\etc\hosts` file:
  ```
  127.0.0.1 dev.rabbit-mq.com
  ```

---

---

## Español

# Proyecto Symfony con RabbitMQ en Arquitectura Hexagonal

Este proyecto implementa un **sistema de mensajería** con **RabbitMQ** en un entorno **Symfony**, siguiendo la **Arquitectura Hexagonal** y utilizando **eventos de dominio** para **desacoplar** la **lógica de negocio** de la **infraestructura**. Los eventos de dominio se disparan en el núcleo de la aplicación cuando ocurren cambios significativos y son procesados por **handlers** específicos, lo que permite actualizar, por ejemplo, contadores u otros procesos de forma **asíncrona**.

### Módulo `Message`

Además, el módulo **Message** envía mensajes directamente sin eventos de dominio.

### Módulo `User`

Mientras que el módulo **User** usa eventos de dominio. Por ejemplo, cuando un usuario se registra, se dispara un **UserRegisteredEvent** (o **UserRegisteredEventHandler**) para actualizar el contador de género.

## 🚀 Pasos de Instalación

### 1️⃣ Clonar el repositorio y navegar a la carpeta

```bash
git clone https://github.com/Luispfa/sf7-rabbitmq-ha.git
cd rabbit-mq
```

### 2️⃣ Iniciar los servicios con Docker

```bash
docker-compose up -d --build
```

### 3️⃣ Ingresar al contenedor PHP:

```bash
docker exec -it sf7_php_ha bash
```

### 4️⃣ Instalar dependencias de Symfony

```bash
php composer install
```

### 5️⃣ Verificar si el puerto 15672 de RabbitMQ está abierto en el host `sf7_rabbitmq_ha`:

```bash
nc -zv sf7_rabbitmq_ha 15672
```

### 6️⃣ Hacer una solicitud HTTP a la API de RabbitMQ para obtener información detallada del estado:

```bash
curl -u guest:guest http://sf7_rabbitmq_ha:15672/api/overview
```

### 7️⃣ Ejecutar el consumidor de RabbitMQ

```bash
php bin/console messenger:consume async
```

### 8️⃣ Ejecutar el consumidor de RabbitMQ en modo demonio:

```bash
php bin/console messenger:consume async --daemon
```

### 9️⃣ Si estás usando Windows, agrega la siguiente línea a tu archivo `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1 dev.rabbit-mq.com
```

### Enviar un mensaje de prueba

#### Enviando un mensaje a la cola `async`

- **Método:** `POST`
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Headers:** `Content-Type: application/json`
- **Cuerpo (JSON crudo):**
  ```json
  {
    "message": "Hello RabbitMQ!"
  }
  ```

---

## Funcionalidad de Eventos de Dominio: Registro de Usuario y Contador de Género

El proyecto incluye la funcionalidad para registrar un usuario y actualizar automáticamente un contador de género mediante un **evento de dominio** enviado a RabbitMQ.

### Endpoint para registrar un usuario

- **URL:** `POST http://dev.rabbit-mq.com/register-user`
- **Headers:**  
  `Content-Type: application/json`
- **Cuerpo (JSON crudo):**
  ```json
  {
    "name": "Juan",
    "lastname": "Flores",
    "gender": "male"
  }
  ```

### ¿Qué sucede cuando se registra un usuario?

1. Se crea el usuario en la aplicación.
2. Se dispara un evento de dominio (por ejemplo, **UserRegisteredEvent**) para actualizar el contador de género.
3. El evento se envía a RabbitMQ mediante Symfony Messenger.
4. Un **handler** específico (por ejemplo, **UserRegisteredEventHandler**) procesa el evento y actualiza un contador (usando un archivo, base de datos, etc.).
5. Asegúrate de que el consumidor esté en ejecución para procesar el evento:
   ```bash
   php bin/console messenger:consume async
   ```
   _(Si todos los eventos están enrutados al mismo transporte, el worker los consumirá y ejecutará los **handlers** correspondientes.)_

---

## Ejemplos de Uso de la API

### Enviando un mensaje de prueba

- **Método:** `POST`
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Headers:** `Content-Type: application/json`
- **Cuerpo (JSON crudo):**
  ```json
  {
    "message": "Hello RabbitMQ! Luis"
  }
  ```

---

### Registrando un usuario y actualizando el contador de género

- **Método:** `POST`
- **URL:** `http://dev.rabbit-mq.com/register-user`
- **Headers:** `Content-Type: application/json`
- **Cuerpo (JSON crudo):**
  ```json
  {
    "name": "Juan",
    "lastname": "Flores",
    "gender": "male"
  }
  ```

---

## 🐇 Acceso a RabbitMQ

- RabbitMQ es accesible en `http://dev.rabbit-mq.com:15672/` con usuario **guest** y contraseña **guest**.
- Si estás usando Windows, agrega la siguiente línea a tu archivo `C:\Windows\System32\drivers\etc\hosts`:
  ```
  127.0.0.1 dev.rabbit-mq.com
  ```
