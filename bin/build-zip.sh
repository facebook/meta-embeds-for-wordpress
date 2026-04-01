#!/usr/bin/env bash
# Copyright (c) Meta Platforms, Inc. and affiliates.
# Licensed under the GPL-2.0-or-later license.
#
# Build a clean zip for WordPress.org submission.
# Usage: bin/build-zip.sh [output-dir]
#
# Reads the version from the plugin header automatically.
# Produces: meta-embeds-{version}.zip containing a meta-embeds/ folder
# with only production files (no dev tooling, tests, or dotfiles).

set -euo pipefail

PLUGIN_SLUG="meta-embeds"
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PLUGIN_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
OUTPUT_DIR="${1:-$PLUGIN_DIR}"
VERSION="$(grep -m1 '^ \* Version:' "$PLUGIN_DIR/meta-embeds.php" | sed 's/.*Version:[[:space:]]*//')"
BUILD_DIR="$(mktemp -d)"
DEST="$BUILD_DIR/$PLUGIN_SLUG"
ZIP_NAME="$PLUGIN_SLUG-$VERSION"

echo "Building $ZIP_NAME.zip..."

mkdir -p "$DEST"

# Copy only production files
cp "$PLUGIN_DIR/meta-embeds.php" "$DEST/"
cp "$PLUGIN_DIR/uninstall.php" "$DEST/"
cp "$PLUGIN_DIR/readme.txt" "$DEST/"
cp "$PLUGIN_DIR/README.md" "$DEST/"
cp "$PLUGIN_DIR/CHANGELOG.md" "$DEST/"
cp "$PLUGIN_DIR/LICENSE" "$DEST/"

cp -r "$PLUGIN_DIR/includes" "$DEST/"
cp -r "$PLUGIN_DIR/src" "$DEST/"
cp -r "$PLUGIN_DIR/languages" "$DEST/"

# Build zip
cd "$BUILD_DIR"
zip -r "$OUTPUT_DIR/$ZIP_NAME.zip" "$PLUGIN_SLUG/" -x '*.DS_Store'

# Cleanup
rm -rf "$BUILD_DIR"

echo "Created: $OUTPUT_DIR/$ZIP_NAME.zip"
echo ""
echo "Contents:"
unzip -l "$OUTPUT_DIR/$ZIP_NAME.zip"
