#!/usr/bin/bash

set -e

repo_proto="$HOME/lbaw1522-git/Artefatos/A10/"
proto_production="$HOME/public_html/proto/"

mkdir -p "$proto_production"
rm -rf "$proto_production"
cp -r "$repo_proto". "$proto_production"

cd "$proto_production"
echo -e "\nSetting permissions to all hidden directories and files in $proto_production"
find . -iname '.*' -exec chmod -c 700 {} \;

echo -e "\nSetting permissions to all directories in $proto_production"
find . -type d -exec chmod -c 711 {} \;

echo -e "\nSetting permissions to all php and tpl files in $proto_production"
find . -type f -iname '*.php' -o -iname '*.tpl' -exec chmod -c 600 {} \;

echo -e "\nSetting permissions to all css files in $public_html_path"
find . -type f -iname '*.css' -exec chmod -c 644 {} \;

script_name="${0#*/}"
script_name="${script_name%%.*}"
date > "${HOME}/${script_name}.date"
