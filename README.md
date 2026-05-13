<div align="center">

<img width="220" src="https://cdn-icons-png.flaticon.com/512/3418/3418886.png" />

# 🎬 MovieHub Platform

### Plataforma web de streaming multimedia con PHP y Docker 🚀

<p align="center">
  <b>MovieHub Platform</b> es una plataforma web moderna desarrollada para la gestión y reproducción de películas y contenido multimedia, utilizando PHP, MySQL y contenedores Docker para un despliegue rápido y escalable.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/Docker-Container-2496ED?style=for-the-badge&logo=docker&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white">
  <img src="https://img.shields.io/badge/Streaming-Multimedia-red?style=for-the-badge">
</p>

<p align="center">
  <a href="#-acerca-del-proyecto">Acerca</a> •
  <a href="#-características">Características</a> •
  <a href="#-tecnologías-utilizadas">Tecnologías</a> •
  <a href="#-docker">Docker</a> •
  <a href="#-instalación">Instalación</a>
</p>

</div>

---

# 🌌 Acerca del proyecto

**MovieHub Platform** es una plataforma multimedia enfocada en la reproducción de películas y administración de contenido audiovisual mediante una arquitectura web moderna.

El sistema fue diseñado para funcionar fácilmente tanto en desarrollo local como en producción gracias al uso de contenedores Docker.

La plataforma permite:

- 🎬 Streaming de películas
- 📺 Reproducción multimedia online
- 🔍 Búsqueda de contenido
- 👤 Gestión de usuarios
- ❤️ Sistema de favoritos
- 📱 Diseño responsive
- ⚡ Despliegue rápido con Docker
- 🗄️ Integración con MySQL

El proyecto fue desarrollado para practicar:

- PHP Backend
- Docker & Containers
- Arquitectura web
- Streaming multimedia
- MySQL
- Responsive Design
- Sistemas OTT

---

# ✨ Características

## 🎥 Plataforma multimedia

- 🍿 Streaming de películas
- 📺 Reproducción online
- 🎞️ Catálogo multimedia
- 🔥 Interfaz estilo Netflix

---

## 👤 Gestión de usuarios

- 🔐 Login y registro
- ❤️ Lista de favoritos
- 🧾 Historial de reproducción
- 👥 Administración de perfiles

---

## 🔍 Exploración de contenido

- 🎬 Películas populares
- ⭐ Contenido destacado
- 🏷️ Categorías y géneros
- 🔎 Buscador dinámico

---

## 📱 Responsive Design

- 📲 Compatible con móviles
- 💻 Optimizado para escritorio
- 🖥️ Interfaz adaptable
- ⚡ Navegación fluida

---

# 🛠️ Tecnologías utilizadas

## 🌐 Frontend

<p>
  <img src="https://skillicons.dev/icons?i=html,css,js,bootstrap" />
</p>

- HTML5
- CSS3
- JavaScript
- Bootstrap

---

## ⚙️ Backend

<p>
  <img src="https://skillicons.dev/icons?i=php" />
</p>

- PHP
- Apache Server
- REST APIs

---

## 🗄️ Base de datos

<p>
  <img src="https://skillicons.dev/icons?i=mysql" />
</p>

- MySQL
- phpMyAdmin

---

## 🐳 Contenedores

<p>
  <img src="https://skillicons.dev/icons?i=docker" />
</p>

- Docker
- Docker Compose

---

# 📂 Estructura del proyecto

```bash
MovieHub/
│
├── app/
├── assets/
├── css/
├── js/
├── database/
├── docker/
├── uploads/
├── Dockerfile
├── docker-compose.yml
├── index.php
└── README.md
```

---

# 🐳 Docker

## 📦 Dockerfile

```Dockerfile
FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

EXPOSE 80
```

---

## ⚙️ Docker Compose

```yaml
version: '3.9'

services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db

  db:
    image: mysql:8
    restart: always
    environment:
      MYSQL_DATABASE: moviehub
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
```

---

# ⚡ Instalación

## 📋 Requisitos

- Docker
- Docker Compose
- Git

---

# 🚀 Configuración del proyecto

## 1️⃣ Clonar repositorio

```bash
git clone https://github.com/usuario/moviehub-platform.git
```

---

## 2️⃣ Entrar al proyecto

```bash
cd moviehub-platform
```

---

## 3️⃣ Levantar contenedores

```bash
docker-compose up -d
```

---

## 4️⃣ Acceder al sistema

Abrir navegador:

```bash
http://localhost:8080
```

---

# 🗄️ Base de datos

## 📥 Importar SQL

Importar archivo:

```bash
database/moviehub.sql
```

en phpMyAdmin o MySQL.

---

# 🎬 Funcionalidades principales

## 🍿 Streaming multimedia

- Reproducción de películas
- Plataforma OTT
- Player integrado

---

## 🔎 Exploración de contenido

- Filtros dinámicos
- Categorías
- Recomendaciones

---

## 👤 Sistema de usuarios

- Registro/Login
- Favoritos
- Gestión de perfiles

---

## ⚡ Docker Deployment

- Fácil despliegue
- Arquitectura portable
- Contenedores optimizados

---

# 📸 Vista previa

<div align="center">

<img width="1000" src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=1200&auto=format&fit=crop" />

</div>

---

# 🧠 Objetivos del proyecto

## 🎯 Aprender y practicar

- PHP Development
- Docker Containers
- MySQL
- Streaming Platforms
- Responsive Design
- Backend Architecture
- Multimedia Systems

---

# 🚧 Roadmap

## 🔮 Próximas mejoras

- 🎥 Player avanzado
- 📡 Streaming adaptativo
- ❤️ Watchlist
- 🌙 Dark Mode
- ☁️ Deployment cloud
- 🔐 JWT Authentication
- 📱 Aplicación móvil

---

# 🤝 Contribuciones

Las contribuciones son bienvenidas ❤️

## Cómo contribuir

1. Fork del proyecto

```bash
git checkout -b feature/new-feature
```

2. Commit de cambios

```bash
git commit -m "✨ Nueva funcionalidad"
```

3. Push al repositorio

```bash
git push origin feature/new-feature
```

4. Crear Pull Request 🚀

---

# 👨‍💻 Autor

<div align="center">

## Isai Reyes — Full Stack Developer

Desarrollador enfocado en plataformas multimedia, streaming web y arquitecturas modernas con Docker.

</div>

---

# 🌟 Apoya el proyecto

⭐ Dale una estrella  
🍴 Haz fork  
📢 Comparte el proyecto

---

# 📜 Licencia

Proyecto educativo desarrollado para práctica de PHP, Docker y plataformas multimedia.

---

<div align="center">

### 🎬 MovieHub Platform — streaming moderno impulsado por Docker 🚀

</div>
