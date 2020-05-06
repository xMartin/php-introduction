#!/usr/bin/env bash

REMOTE=$(git remote -v | grep push | cut -f 2 | cut -f 1 -d " ")
DATE=$(date -u +"%Y-%m-%dT%H:%M:%S%z")

if [ -d output_prod ]; then rm -rf output_prod; fi

git clone $REMOTE output_prod

cd output_prod
git checkout -b gh-pages origin/gh-pages
git pull --rebase
rm -rf *
cd ..
vendor/bin/sculpin generate --env=prod
cd output_prod
git add -A .
git commit -m "gh-pages deploy $DATE"
git push origin gh-pages
cd ..


