/*
 * This file is part of flarum-lang/utils.
 *
 * Copyright (c) 2023 Robert Korulczyk <robert@korulczyk.pl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

import app from 'flarum/admin/app';

app.initializers.add(PLACEHOLDER_INITIALIZER_ID, () => {
	app.extensionData.for(PLACEHOLDER_EXTENSION_ID).registerSetting(PLACEHOLDER_SETTINGS);
});
