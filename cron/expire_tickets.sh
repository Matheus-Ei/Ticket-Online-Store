#!/bin/sh

echo "Starting job in date: $(date)"

export PGPASSWORD=$DATABASE_PASSWORD

psql -h "$DATABASE_HOST" \
     -U "$DATABASE_USER" \
     -d "$DATABASE_NAME" \
     -p "${DATABASE_PORT:-5432}" \
     -c "UPDATE tickets SET status = 'expired' WHERE status = 'reserved' AND created_at < NOW() - INTERVAL '2 minutes'"

if [ $? -eq 0 ]; then
  echo "The query was executed successfully."
else
  echo "An error occurred while executing the query."
fi

unset PGPASSWORD
