#!/usr/bin/env bash
rsync -avz /www/yyy_dict/app /usr/share/nginx/html/yyy_dict/ && \
rsync -avz /www/yyy_dict/resources /usr/share/nginx/html/yyy_dict/ && \
rsync -avz /www/yyy_dict/routes /usr/share/nginx/html/yyy_dict/

