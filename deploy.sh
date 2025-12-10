#!/bin/bash

# Jalankan migration
php artisan migrate --force

# Kosongkan cache supaya config baru masuk
php artisan optimize:clear

# Hidupkan server sebenar
/start.sh