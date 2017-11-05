define([
    'Magento_Ui/js/grid/listing'
], function (Collection) {
    'use strict';

    return Collection.extend({
        defaults: {
            editorConfig: {
                component: 'Firebear_FastPrice/js/grid/editing/editor'
            }
        }
    });
});