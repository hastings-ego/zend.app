#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# --- Configuration ---
SOURCE_DIR="assets"                  # Local folder to copy from
BASE_DEST_DIR="../collection"            # Parent directory where themes are stored

echo "=== Asset Transfer Utility ==="

# 1. Verify the source assets directory exists
if [ ! -d "$SOURCE_DIR" ]; then
    echo "Error: Source directory '$SOURCE_DIR' does not exist in the current path." >&2
    exit 1
fi

# 2. Prompt the user for the target theme name
read -p "Enter the name of the theme you are working on: " THEME_NAME

# Validate that the user actually entered something
if [ -z "$THEME_NAME" ]; then
    echo "Error: Theme name cannot be empty." >&2
    exit 1
fi

# Construct the full destination path
TARGET_DIR="$BASE_DEST_DIR/$THEME_NAME/$SOURCE_DIR"

# 3. Check if the theme directory exists, create if missing
if [ ! -d "$BASE_DEST_DIR/$THEME_NAME" ]; then
    echo "Theme '$THEME_NAME' does not exist. Creating directory..."
    mkdir -p "$TARGET_DIR"
else
    echo "Theme '$THEME_NAME' found. Preparing to sync assets..."
    mkdir -p "$TARGET_DIR"
fi

# 4. Copy/Sync the files
echo "Copying '$SOURCE_DIR/' to '$TARGET_DIR/'..."
cp -r "$SOURCE_DIR/"* "$TARGET_DIR/"

TARGET_DIR="$BASE_DEST_DIR/$THEME_NAME/data"
if [ ! -d "$BASE_DEST_DIR/$THEME_NAME/data" ]; then
    echo "Theme '$THEME_NAME' does not exist. Creating directory..."
    mkdir -p "$TARGET_DIR"
else
    echo "Theme '$THEME_NAME' found. Preparing to sync assets..."
    mkdir -p "$TARGET_DIR"
fi
cp -r "data/"* "$TARGET_DIR/"

TARGET_DIR="$BASE_DEST_DIR/$THEME_NAME/theme"
if [ ! -d "$BASE_DEST_DIR/$THEME_NAME/theme" ]; then
    echo "Theme '$THEME_NAME' does not exist. Creating directory..."
    mkdir -p "$TARGET_DIR"
else
    echo "Theme '$THEME_NAME' found. Preparing to sync assets..."
    mkdir -p "$TARGET_DIR"
fi
cp -r "theme/"* "$TARGET_DIR/"

TARGET_DIR="$BASE_DEST_DIR/$THEME_NAME"
cp "poster.png" "$TARGET_DIR/"
cp "index.html" "$TARGET_DIR/"
cp "autofill.json" "$TARGET_DIR/"

echo "========================================"
echo " Transfer Complete!"
echo " Destination: $(realpath "$TARGET_DIR")"
echo "========================================"