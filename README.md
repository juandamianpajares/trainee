# BITNET Trainee PRO - Laravel Bootcamp System

## 🚀 Nuevo: Laravel Bootcamp PRO

Este repositorio ahora incluye un **sistema completo de bootcamp** para aprender Laravel construyendo un ERP profesional desde cero.

### Características del Bootcamp

- **7 módulos progresivos** (143 horas de contenido)
- **20+ lecciones** con contenido teórico en Markdown
- **15+ ejercicios** prácticos con evaluación automática
- **7 quizzes** de evaluación por módulo
- **Sistema de certificación** automática al completar 100%
- **Frontend Next.js** moderno con tracking en tiempo real
- **APIs RESTful** completas para integración
- **3 roles ERP:** Gerente, Empleado, Cliente

### Inicio Rápido

```bash
# Instalación automática (recomendado)
./setup-bootcamp.sh

# Iniciar frontend (en otra terminal)
cd frontend && npm run dev

# Acceder al bootcamp
# http://localhost:3000
```

### Documentación

- **📖 [BOOTCAMP_README.md](BOOTCAMP_README.md)** - Documentación técnica completa
- **⚡ [QUICKSTART.md](QUICKSTART.md)** - Guía de inicio rápido
- **📊 [BOOTCAMP_SUMMARY.md](BOOTCAMP_SUMMARY.md)** - Resumen ejecutivo

---

## Sistema Base (BITNET Trainee PRO)

This package includes the full BITNET Trainee PRO starter:
- Laravel starter scaffolding (models, controllers, migrations)
- PDF certificate generation via barryvdh/laravel-dompdf
- Magic-link email login (development via Mailhog)
- GitHub connect stub (uses GITHUB_TOKEN env, updates progress)
- Tailwind + Vite frontend scaffold (assets + package.json)
- Docker Compose stack with queue, scheduler and Mailhog
- provision.sh to build and prepare environment

Quick notes:
- Run `./provision.sh` (requires Docker)
- Set GITHUB_TOKEN in .env to enable GitHub connect functionality
- PDF package is already included in composer.json
