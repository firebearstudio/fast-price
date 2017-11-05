define([
    'underscore',
    'Magento_Ui/js/grid/editing/record'
], function (_, Collection) {
    'use strict';

    return Collection.extend({
        defaults: {
            templates: {
                fields: {
                    price: {
                        component: 'Firebear_FastPrice/js/form/element/price',
                        template: 'ui/form/element/input'
                    }

                }
            }
        }
    });
})