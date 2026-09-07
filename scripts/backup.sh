#!/bin/bash
# Habit Tracker Pro - Automated DB Backup
# Run via cron: 0 2 * * * /opt/habit-tracker-php/scripts/backup.sh

DB_PATH="/opt/habit-tracker-php/data/habit_tracker.db"
BACKUP_DIR="/opt/habit-tracker-php/data/backups"
KEEP_DAYS=30

mkdir -p "$BACKUP_DIR"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/habit_tracker_${TIMESTAMP}.db"

cp "$DB_PATH" "$BACKUP_FILE"
gzip "$BACKUP_FILE"

# Remove backups older than KEEP_DAYS
find "$BACKUP_DIR" -name "*.db.gz" -mtime +$KEEP_DAYS -delete

echo "[$(date)] Backup completed: ${BACKUP_FILE}.gz"
