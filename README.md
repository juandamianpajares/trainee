# BITNET Trainee PRO - Starter Kit (Integrated)

This package includes the full BITNET Trainee PRO starter:
- Laravel starter scaffolding (models, controllers, migrations)
- PDF certificate generation via barryvdh/laravel-dompdf (instructions)
- Magic-link email login (development via Mailhog)
- GitHub connect stub (uses GITHUB_TOKEN env, updates progress)
- Tailwind + Vite frontend scaffold (assets + package.json)
- Docker Compose stack with queue, scheduler and Mailhog
- provision.sh to build and prepare environment

Quick notes:
- Run `./provision.sh` (requires Docker)
- Set GITHUB_TOKEN in .env to enable GitHub connect functionality
- To enable PDF downloads install package inside container: composer require barryvdh/laravel-dompdf
