# CS2-Themed Invite (Laravel 12)

A cute, playful CS2-inspired invite flow built with Laravel 12, Blade, Tailwind, and Alpine.js. It guides the recipient through a 4-step map-select style form and sends a MailerSend notification when submitted.

## Setup

1) Install dependencies
```
composer install
npm install
```

2) Configure environment
```
cp .env.example .env
php artisan key:generate
```

Set values in `.env`:
- `APP_URL`
- `INVITEE_NAME`
- `INVITE_AVAILABLE_DATES` (comma-separated `YYYY-MM-DD`)
- `INVITE_EVENING_TIME` (24h format, e.g. `19:00`)
- `MAIL_TO_ADDRESS`
- MailerSend API settings (see below)

3) Database
```
php artisan migrate
```

4) Run locally
```
npm run dev
php artisan serve
```

Visit:
```
http://localhost:8000/invite?t=10172024
```

## MailerSend (API)

Install the MailerSend Laravel adapter:
```
composer require mailersend/laravel-driver
```

In `.env`:
```
MAIL_MAILER=mailersend
MAILERSEND_API_KEY=ms_your_api_key
MAIL_FROM_ADDRESS="hello@your-domain.com"
MAIL_FROM_NAME="CS2 Invite"
MAIL_TO_ADDRESS="you@example.com"
```

## Forge Deployment (quick)

1) Create site + repo + environment.
2) Set `.env` values (see above) and run:
```
composer install --no-dev
php artisan migrate --force
npm install
npm run build
```
3) Point your QR code to:
```
https://your-domain.com/invite?t=10172024
```

## Customize

- Token is fixed in `config/invite.php`.
- Invitee name from `.env` (`INVITEE_NAME`) with a fallback.
- Unavailable slots in `config/invite.php` (`unavailable_datetimes`).
- Email recipient address from `.env` (`MAIL_TO_ADDRESS`).
