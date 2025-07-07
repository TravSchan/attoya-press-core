#!/bin/bash
# ONLY EDIT WITH NOTEPAD++ AND REMOVE ALL LINE RETURNS (\r)

#
# This is to create scaffold directory structure and create
# an empty PHP file to prevent directory from being exposed.
#

mkdir -p public/wp-content
echo "<?php // Nada" > public/wp-content/index.php

mkdir -p public/wp-content/plugins
echo "<?php // Nada" > public/wp-content/plugins/index.php

mkdir -p public/wp-content/themes
echo "<?php // Nada" > public/wp-content/themes/index.php

mkdir -p public/wp-content/themes
echo "<?php // Nada" > public/wp-content/themes/index.php

mkdir -p public/wp-content/upgrade-temp-backup
echo "<?php // Nada" > public/wp-content/upgrade-temp-backup/index.php


