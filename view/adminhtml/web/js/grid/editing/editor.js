define([
    'underscore',
    'jquery',
    'Magento_Ui/js/grid/editing/editor'
], function (_, $, Collection) {
    'use strict';

    return Collection.extend({
        defaults: {
            templates: {
                record: {
                    component: 'Firebear_FastPrice/js/grid/editing/record'
                }
            }
        },
        getId: function (id, isIndex) {
            if (typeof isIndex != 'boolean') {
                isIndex = $.parseJSON(isIndex);
            }
            return this._super(id, isIndex);
        }
    });
});