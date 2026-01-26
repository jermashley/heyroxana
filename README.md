# CS2-Themed Invite (Laravel 12)

A cute, playful CS2-inspired invite flow built with Laravel 12, Blade, Tailwind, and Alpine.js. It guides the recipient through a 4-step map-select style form and sends a Mailgun notification when submitted.

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
- `MAIL_TO_ADDRESS`
- Mailgun SMTP settings (see below)

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

## Mailgun (SMTP)

This app uses the SMTP mailer by default. In `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_SCHEME=tls
MAIL_USERNAME=postmaster@YOUR_DOMAIN
MAIL_PASSWORD=YOUR_SMTP_PASSWORD
MAIL_FROM_ADDRESS="hello@YOUR_DOMAIN"
MAIL_FROM_NAME="CS2 Invite"
MAIL_TO_ADDRESS="you@example.com"
```

Optional: If you prefer the Mailgun API transport, install the Mailgun mailer package and set `MAIL_MAILER=mailgun`.

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
