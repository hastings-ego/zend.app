#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# --- Configuration ---
APP_DIR="${1:-.}"               # Default to current directory, or pass path as 1st argument
HOST="localhost"
PORT="8099"
LOG_FILE="website-trail.log"

echo "=== Starting PHP Web App Deployment ==="

# 1. Verify PHP is installed
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed. Please install PHP first." >&2
    exit 1
fi

# 2. Verify application directory exists
if [ ! -d "$APP_DIR" ]; then
    echo "Error: Directory '$APP_DIR' does not exist." >&2
    exit 1
fi

# Navigate to the app directory
cd "$APP_DIR"
echo "Deployment directory: $(pwd)"

# 3. Check if the port is already in use
if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null ; then
    echo "Warning: Port $PORT is already in use. Stopping existing process..."
    kill -9 $(lsof -t -i:$PORT) || true
fi

# 4. Start the PHP built-in server in the background
echo "Starting PHP development server on http://$HOST:$PORT ..."
nohup php -S "$HOST:$PORT" > "$LOG_FILE" 2>&1 &

# Save the Process ID (PID) for future management
SERVER_PID=$!
echo $SERVER_PID > .php_server.pid

# 5. Health Check: Wait a moment and check if the server is still running
sleep 1
if ps -p $SERVER_PID > /dev/null; then
    echo "========================================"
    echo " Deployment Successful!"
    echo " URL: http://$HOST:$PORT"
    echo " PID: $SERVER_PID"
    echo " Logs: $(pwd)/$LOG_FILE"
    echo "========================================"
else
    echo "Error: PHP server failed to start. Check $LOG_FILE for details." >&2
    exit 1
fi