# InvoicePing 💰

> Jangan lagi kehilangan pembayaran karena lupa follow-up.

InvoicePing adalah micro-SaaS untuk freelancer dan agency kecil yang membantu mengingatkan follow-up invoice secara otomatis.

## Features

- 📝 **Invoice Management** — CRUD invoice dengan status tracking
- ⏰ **Auto Reminder** — Email follow-up otomatis H+14, H+21, H+30
- 📊 **Dashboard** — Ringkasan outstanding, overdue, dan paid invoices
- 💳 **Status Tracking** — Draft → Sent → Paid/Overdue
- 🔔 **Toggle Reminder** — Aktifkan/nonaktifkan reminder per invoice
- 💰 **Freemium** — Gratis 30 invoice, Pro unlimited

## Tech Stack

- **Backend:** Laravel 12 + PHP 8.3
- **Frontend:** Vue 3 + Inertia.js + Tailwind CSS
- **Database:** SQLite (local) / PostgreSQL (production)
- **Email:** Resend API
- **Auth:** Laravel Breeze (Google OAuth + Email)
- **Deployment:** Docker

## Quick Start

```bash
# Clone
git clone https://github.com/verifydream/invoiceping.git
cd invoiceping

# Install
composer install
npm install

# Setup
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate

# Build & Run
npm run build
php artisan serve --port=8077
```

Visit: http://localhost:8077

## Docker Deployment

```bash
docker compose up -d --build
```

## Project Structure

```
app/
├── Http/Controllers/
│   └── InvoiceController.php    # CRUD + status actions
├── Models/
│   ├── Invoice.php              # Invoice model
│   └── User.php                 # User model (has invoices)
resources/js/
├── Pages/
│   ├── Dashboard.vue            # Stats overview
│   └── Invoice/
│       ├── Index.vue            # Invoice list
│       ├── Create.vue           # Create form
│       ├── Show.vue             # Invoice detail
│       └── Edit.vue             # Edit form
└── Layouts/
    └── AuthenticatedLayout.vue  # App layout
routes/web.php                    # Routes
```

## License

MIT
