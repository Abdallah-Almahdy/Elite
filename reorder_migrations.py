import os
import re
import shutil

MIGRATIONS_DIR = "database/migrations"
BACKUP_DIR = os.path.join(MIGRATIONS_DIR, f"backup_fixed")

# Regex patterns
create_table_pattern = re.compile(r"create\('(\w+)'")
foreign_key_pattern = re.compile(r"on\('(\w+)'\)")

# Step 1: Parse migration dependencies
migrations = []
for filename in os.listdir(MIGRATIONS_DIR):
    if not filename.endswith(".php"):
        continue

    path = os.path.join(MIGRATIONS_DIR, filename)
    with open(path, "r", encoding="utf-8", errors="ignore") as f:
        content = f.read()

    creates = create_table_pattern.findall(content)
    refs = foreign_key_pattern.findall(content)

    migrations.append({
        "file": filename,
        "path": path,
        "creates": creates,
        "refs": refs
    })

# Step 2: Build dependency graph
graph = {}
for m in migrations:
    for c in m["creates"]:
        graph[c] = m["file"]

# Step 3: Topological sort function
visited = set()
ordered = []

def dfs(mig):
    if mig["file"] in visited:
        return
    for ref in mig["refs"]:
        if ref in graph:  # only if the referenced table exists
            dep_file = graph[ref]
            dep_mig = next(mm for mm in migrations if mm["file"] == dep_file)
            dfs(dep_mig)
    visited.add(mig["file"])
    ordered.append(mig)

for m in migrations:
    dfs(m)

# Step 4: Backup original migrations
if not os.path.exists(BACKUP_DIR):
    os.makedirs(BACKUP_DIR)

for m in migrations:
    shutil.copy(m["path"], BACKUP_DIR)

# Step 5: Rename migrations with correct order
for i, m in enumerate(ordered, start=1):
    new_name = f"{i:03d}_{m['file'].split('_', 1)[1]}"
    new_path = os.path.join(MIGRATIONS_DIR, new_name)
    os.rename(m["path"], new_path)
    print(f"{m['file']} -> {new_name}")

print("\n✅ Reordering complete! Backup saved in:", BACKUP_DIR)
