#!/usr/bin/env bash
# Backup PostgreSQL database using credentials from .env (Laravel)
# Usage: ./backup_pg_from_env.sh [output_dir]

set -euo pipefail

ENV_FILE=".env"
OUT_DIR="${1:-storage/backups}"

if [ ! -f "$ENV_FILE" ]; then
  echo "Error: $ENV_FILE not found in current directory. Run from project root or pass DB connection as args." >&2
  exit 2
fi

getenv() {
  local key=$1
  local val
  val=$(grep -E "^${key}=" "$ENV_FILE" | tail -n1 | sed -E "s/^${key}=//") || true
  val=$(echo "$val" | sed -E 's/^\"(.*)\"$/\1/' | sed -E "s/^'(.*)'$/\1/")
  echo "$val"
}

DB_CONNECTION=$(getenv DB_CONNECTION)
DB_HOST=$(getenv DB_HOST)
DB_PORT=$(getenv DB_PORT)
DB_DATABASE=$(getenv DB_DATABASE)
DB_USERNAME=$(getenv DB_USERNAME)
DB_PASSWORD=$(getenv DB_PASSWORD)

DB_PORT=${DB_PORT:-5432}
OUT_DIR=${OUT_DIR%/}
mkdir -p "$OUT_DIR"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
OUT_FILE="$OUT_DIR/pg_backup_${TIMESTAMP}.sql"
GZ_FILE="$OUT_FILE.gz"

if [ "$DB_CONNECTION" != "pgsql" ] && [ -n "$DB_CONNECTION" ]; then
  echo "Warning: DB_CONNECTION='$DB_CONNECTION' (expected 'pgsql'). The script will still attempt pg_dump if available." >&2
fi

export PGPASSWORD="$DB_PASSWORD"

echo "Running pg_dump on database '$DB_DATABASE' at ${DB_HOST}:${DB_PORT} ..."

pg_dump -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -F p -f "$OUT_FILE" "$DB_DATABASE"

if [ $? -ne 0 ]; then
  echo "pg_dump failed" >&2
  unset PGPASSWORD
  exit 3
fi

unset PGPASSWORD

# compress
gzip -9 -c "$OUT_FILE" > "$GZ_FILE" && rm -f "$OUT_FILE"

echo "Backup created: $GZ_FILE"
ls -lh "$GZ_FILE"

exit 0
