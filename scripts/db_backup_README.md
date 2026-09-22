DB Backup scripts

Location: scripts/

Available scripts:
- backup_mysql_from_env.sh  (Linux/macOS) : Reads .env and runs mysqldump, gzips output.
- backup_pg_from_env.sh     (Linux/macOS) : Reads .env and runs pg_dump, gzips output.
- backup_windows.ps1        (Windows PowerShell) : Reads .env and runs mysqldump/pg_dump when available, optional compression.

Usage notes
1. Run from project root so .env is found.
2. Make shell scripts executable: chmod +x scripts/*.sh
3. Ensure mysqldump/pg_dump are installed and available in PATH on the system where you run them.
4. The scripts will write outputs under storage/backups by default (or custom path you pass).
5. After creating backups, verify by attempting to restore to a staging DB.

Restore examples (MySQL):
  gunzip -c mysql_backup_YYYYMMDD_HHMMSS.sql.gz | mysql -u user -p database_name

Restore examples (Postgres):
  gunzip -c pg_backup_YYYYMMDD_HHMMSS.sql.gz | psql -U user -d database_name

Security
- Scripts read DB password from .env and use environment vars (MYSQL_PWD/PGPASSWORD) to avoid exposing password on process list.
- Do NOT commit .env or backup files to version control.

Scheduling
- Use cron (Linux) or Task Scheduler (Windows) to run backups regularly. Keep backups off-server (S3, remote host) for disaster recovery.
