#!/usr/bin/env bash
public_html_path="$HOME/public_html"

echo -e "\nThis script only sets permissions in $public_html_path.\nGit repos and other sensitive files in other places are not handled!!!"

echo -e "\n\nSetting permission to $public_html_path folder"
chmod -c 755 "$public_html_path"

echo -e "\nSetting permissions to all hidden directories and files in $public_html_path"
find "$public_html_path" -name '.*' -type d -exec chmod -c 700 {} \;
find "$public_html_path" -name '.*' -type f -exec chmod -c 600 {} \;

echo -e "\nSetting permissions to all directories in $public_html_path"
find "$public_html_path"/*/ -type d -exec chmod -c 711 {} \;

echo -e "\nSetting permissions to all php and tpl files in $public_html_path"
find "$public_html_path" -type f -iname '*.php' -exec chmod -c 600 {} \;
find "$public_html_path" -type f -iname '*.tpl' -exec chmod -c 600 {} \;

echo -e "\nSetting permissions to all css files in $public_html_path"
find "$public_html_path" -type f -iname '*.css' -exec chmod -c 644 {} \;
