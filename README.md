# Proyecto Symfony con RabbitMQ en Arquitectura Hexagonal

Este proyecto implementa un **sistema de mensajería** con **RabbitMQ** en un entorno **Symfony**, siguiendo la **Arquitectura Hexagonal** y utilizando **eventos de dominio** para **desacoplar** la **lógica de negocio** de la **infraestructura**. Los **eventos de dominio** se disparan en el núcleo de la aplicación cuando ocurren cambios significativos y se procesan mediante **handlers** específicos, lo que permite actualizar, por ejemplo, contadores u otros procesos de forma **asíncrona**.

Además, el módulo **Message** envía mensajes de forma directa, sin utilizar eventos de dominio, lo que permite un flujo de comunicación sencillo y sin lógica adicional de negocio.

En contraste, el módulo **User** sí utiliza **eventos de dominio** para reaccionar a cambios importantes en el sistema.

Por ejemplo:

- **UserRegisteredEvent**: Este evento se dispara cuando se registra un nuevo usuario. Permite ejecutar acciones adicionales, como el envío de notificaciones o la realización de otras tareas relacionadas con la integración del usuario en el sistema.
- **UserRegisteredEventHandler**: Este evento se utiliza para actualizar el contador de usuarios por género de manera asíncrona. Con ello, se mantiene un seguimiento del número de usuarios de cada género sin acoplar directamente la lógica de actualización al flujo principal de negocio.

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
php bin/console messenger:consume async -vv
```

Ejecutar el Consumidor de RabbitMQ en segundo plano:

```bash
php bin/console messenger:consume async --daemon
```

### 5⃣ Enviar un Mensaje de Prueba

#### 📩 Enviar Mensaje a la Cola `async`

Con Postman:

- **Método:** `POST`
- **URL:** `http://dev.rabbit-mq.com/send-message`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "message": "Hola RabbitMQ!"
  }
  ```

#### 👤 Registrar un Usuario

Con Postman:

- **Método:** `POST`
- **URL:** `http://dev.rabbit-mq.com/register-user`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
  ```json
  {
    "name": "Juan",
    "lastname": "Flores",
    "gender": "Male"
  }
  ```

#### 📊 Contar Usuarios por Género

Con Postman:

- **Método:** `GET`
- **URL:** `http://dev.rabbit-mq.com/gender-count`

### 🐇 Acceso a RabbitMQ

- RabbitMQ estará accesible en `http://dev.rabbit-mq.com:15672/` con usuario **guest** y contraseña **guest**.
- Si usas Windows, añade la siguiente línea en tu archivo `C:\Windows\System32\drivers\etc\hosts`:
  ```
  127.0.0.1 dev.rabbit-mq.com
  ```
