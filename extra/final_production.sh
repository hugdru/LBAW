#!/usr/bin/bash

set -e

repo_final="$HOME/lbaw1522-git/Artefatos/A12/"
final_production="$HOME/public_html/final/"

mkdir -p "$final_production"
rm -rf "$final_production"
cp -r "$repo_final". "$final_production"

cd "$final_production"
echo -e "\nSetting permissions to all hidden directories and files in $final_production"
find . -iname '.*' -exec chmod -c 700 {} \;

echo -e "\nSetting permissions to all directories in $final_production"
find . -type d -exec chmod -c 711 {} \;

echo -e "\nSetting permissions to all php and tpl files in $final_production"
find . -type f -iname '*.php' -exec chmod -c 600 {} \;
find . -type f -iname '*.tpl' -exec chmod -c 600 {} \;

echo -e "\nSetting permissions to all css files in $public_html_path"
find . -type f -iname '*.css' -exec chmod -c 644 {} \;

script_name="${0#*/}"
script_name="${script_name%%.*}"
date > "${HOME}/${script_name}.date"
