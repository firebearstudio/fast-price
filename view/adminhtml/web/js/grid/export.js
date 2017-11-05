/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'underscore',
    'Magento_Ui/js/grid/export',
    'mage/translate'
], function ($, _, Element, $t) {
    'use strict';

    return Element.extend({
        defaults: {
            template: 'Firebear_FastPrice/grid/exportButton',
            placeholder: $t(''),
            input: ''
        },

        initObservable: function () {
            this._super()
                .observe('input', this.input);
            return this;
        },
        getParams: function () {
            var selections = this.selections(),
                data = selections ? selections.getSelections() : null,
                itemsType,
                result = {};

            if (data) {
                itemsType = data.excludeMode ? 'excluded' : 'selected';
                result.filters = data.params.filters;
                result.search = data.params.search;
                result.namespace = data.params.namespace;
                result.input = this.input;
                result[itemsType] = data[itemsType];

                if (!result[itemsType].length) {
                    result[itemsType] = false;
                }
            }

            return result;
        },
    });
});
