/*
 * This file is part of flarum-lang/utils.
 *
 * Copyright (c) 2023 Robert Korulczyk <robert@korulczyk.pl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

const path = require('path');

let config = require('flarum-webpack-config')();
config.entry.languagePackWithVariants = path.resolve(process.cwd(), 'languagePackWithVariants.ts');
module.exports = config;
