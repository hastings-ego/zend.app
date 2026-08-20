#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DATA_DIR="$ROOT_DIR/data"

if [ ! -d "$DATA_DIR" ]; then
  echo "Missing data directory: $DATA_DIR"
  exit 1
fi

# Make the directory and all contained files fully writable for the browser.
find "$DATA_DIR" -type d -exec chmod 0777 {} +
find "$DATA_DIR" -type f -exec chmod 0777 {} +

# If a web user is configured, make it the owner of the writable data tree.
WEB_USER="${WEB_USER:-www-data}"
if id "$WEB_USER" >/dev/null 2>&1; then
  chown -R "$WEB_USER":"$(id -gn "$WEB_USER")" "$DATA_DIR"
fi

echo "Updated permissions for $DATA_DIR"
echo "Directories: 0777"
echo "Files: 0777"
