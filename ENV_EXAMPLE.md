# ENV_EXAMPLE — EduCP Manager / CPManager

Salin nilai-nilai ini ke konfigurasi environment Anda (`.env` atau `config/env.php`).
JANGAN commit kredensial asli — gunakan placeholder.

```
APP_NAME="EduCP Manager"
APP_ENV=local            # local | production
APP_DEBUG=true           # false di production
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=educp_manager
DB_USER=root
DB_PASS=

SESSION_SECURE=false     # true di production (HTTPS)
SESSION_SAMESITE=Lax

# AI Provider (Fase 18) — jangan tulis API key asli di sini
AI_PROVIDER=gemini
AI_ENDPOINT=
AI_MODEL=
AI_API_KEY=__PLACEHOLDER__
```
