#!/usr/bin/env bash
if [ -f vendor/bin/sail ]; then
    exec bash vendor/bin/sail "$@"
else
    echo "Sail not installed. Run composer install."
    exit 1
fi
