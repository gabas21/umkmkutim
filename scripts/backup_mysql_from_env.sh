#!/usr/bin/env bash
# Backup MySQL database using credentials from .env (Laravel)
# Usage: ./backup_mysql_from_env.sh [output_dir]
# Example: ./backup_mysql_from_env.sh /var/backups/umkm

set -euo pipefail

ENV_FILE=".env"
OUT_DIR="${1:-storage/backups}"

if [ ! -f "$ENV_FILE" ]; then
  echo "Error: $ENV_FILE not found in current directory. Run from project root or pass DB connection as args." >&2
  exit 2
fi

# helper to read .env key value (handles quoted values)
getenv() {
  local key=$1
  local val
  val=$(grep -E "^${key}=" "$ENV_FILE" | tail -n1 | sed -E "s/^${key}=//") || true
  # strip surrounding quotes
  val=$(echo "$val" | sed -E 's/^\"(.*)\"$/\1/' | sed -E "s/^'(.*)'$/\1/")
  echo "$val"
}

DB_CONNECTION=$(getenv DB_CONNECTION)
DB_HOST=$(getenv DB_HOST)
DB_PORT=$(getenv DB_PORT)
DB_DATABASE=$(getenv DB_DATABASE)
DB_USERNAME=$(getenv DB_USERNAME)
DB_PASSWORD=$(getenv DB_PASSWORD)

# defaults
DB_PORT=${DB_PORT:-3306}
OUT_DIR=${OUT_DIR%/}
mkdir -p "$OUT_DIR"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
OUT_FILE="$OUT_DIR/mysql_backup_${TIMESTAMP}.sql"
GZ_FILE="$OUT_FILE.gz"

if [ "$DB_CONNECTION" != "mysql" ] && [ -n "$DB_CONNECTION" ]; then
  echo "Warning: DB_CONNECTION='$DB_CONNECTION' (expected 'mysql'). The script will still attempt mysqldump if available." >&2
fi

# Use MYSQL_PWD to avoid password exposure in command line
export MYSQL_PWD="$DB_PASSWORD"

echo "Running mysqldump on database '$DB_DATABASE' at ${DB_HOST}:${DB_PORT} ..."

# Use safe mysqldump flags
mysqldump --single-transaction --quick --skip-lock-tables -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" "$DB_DATABASE" > "$OUT_FILE"

if [ $? -ne 0 ]; then
  echo "mysqldump failed" >&2
  unset MYSQL_PWD
  exit 3
fi

unset MYSQL_PWD

# compress
gzip -9 -c "$OUT_FILE" > "$GZ_FILE" && rm -f "$OUT_FILE"

echo "Backup created: $GZ_FILE"

# optional: list the file
ls -lh "$GZ_FILE"

exit 0
