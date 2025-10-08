#!/bin/bash
set -e

MIG_DIR="database/migrations"
BACKUP_DIR="$MIG_DIR/backup_$(date +%s)"
mkdir -p "$BACKUP_DIR"

echo "📦 Backing up duplicates to: $BACKUP_DIR"

# Step 1: Deduplicate
# Find duplicate migrations by name (ignoring timestamp)
for name in $(ls $MIG_DIR | sed 's/^[0-9_]*//' | sort | uniq -d); do
  files=($(ls $MIG_DIR/*$name))
  echo "⚠️ Duplicate found: $name"
  
  # Keep the newest file (last one when sorted)
  keep=${files[-1]}
  echo "   Keeping: $(basename $keep)"
  
  # Move others to backup
  for f in "${files[@]}"; do
    if [[ "$f" != "$keep" ]]; then
      mv "$f" "$BACKUP_DIR/"
      echo "   Moved duplicate: $(basename $f)"
    fi
  done
done

# Step 2: Reorder by dependency
echo "🔄 Renaming migrations sequentially..."

i=1
for file in $(ls $MIG_DIR/*.php | sed 's/.*\///' | sort); do
  base=$(echo $file | sed 's/^[0-9_]*//')
  new_name=$(printf "%s/%03d_%s" "$MIG_DIR" "$i" "$base")
  mv "$MIG_DIR/$file" "$new_name"
  echo "   -> $file renamed to $(basename $new_name)"
  ((i++))
done

echo "✅ Done! Migrations cleaned and reordered."
echo "   Backup of old duplicates: $BACKUP_DIR"
