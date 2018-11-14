#!/usr/bin/env bash

curl -fsSL --user "${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD}" \
"${ARTIFACTORY_URL}scripts-local/composer-package-deploy/composer-package-deploy.sh" \
| bash -s -- "`echo $TRAVIS_REPO_SLUG | awk 'BEGIN { FS = "/" } ; { print $2 }'`" "${TRAVIS_TAG}"