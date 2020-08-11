<template>
    <tr>
        <td>
            <select @change="update" class="form-control input-sm" :name="`level[field]`"
                    v-model="criterion.field">
                <option value="">Select Field</option>
                <option value="category_id">Category</option>
                <option value="subcategory_id">Subcategory</option>
                <option value="item_id">Item</option>
                <option value="subitem_id">Subitem</option>

            </select>
        </td>
        <td>
            <select @change="update" class="form-control input-sm" :name="`level[operator]`"
                    v-model="criterion.operator">
                <option value="is" selected>is</option>
            </select>
        </td>
        <td>
            <div class="input-group" v-if="showMenuIcon && !showDate">
                <input class="form-control input-sm" :name="`level[label]`" type="text"
                       @click="loadOptions()" v-model="criterion.label" readonly>
                <span class="input-group-btn">
                <button type="button" class="btn btn-default btn-sm" @click="loadOptions()"><i
                        class="fa fa-bars"></i></button>
            </span>
            </div>
            <input v-else-if="!showDate" class="form-control input-sm" :name="`level[label]`" type="text"
                   v-model="criterion.label" @change="update">


            <input v-if="!showDate" type="hidden" :name="`level[value]`" v-model="criterion.value">

        </td>
        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from '../../Bus';

    const fields = {
        category_id: {type: 'select', list: 'kgs_category', name: 'Category'},
        subcategory_id: {type: 'select', list: 'kgs_subcategory', name: 'Subcategory'},
        item_id: {type: 'select', list: 'kgs_item', name: 'Item'},
        subitem_id: {type: 'select', list: 'kgs_subitem', name: 'subitem_id'},
    };

    export default {
        props: ['criterion', 'index'],

        computed: {
            showMenuIcon() {
                const field = fields[this.criterion.field];
                if (!field) {
                    return false;
                }
                return field.type == 'select' && (this.criterion.operator == 'is' || this.criterion.operator == 'isnot')
            },
            showDate() {
                const field = fields[this.criterion.field];
                if (!field || field.type == 'text') {
                    return false;
                } else if (field.type == 'date') {
                    return true;
                }
            }
        },

        methods: {
            update() {
                this.criterion.value = this.criterion.label;
            },

            remove() {
                EventBus.$emit('removeCriterion', this.index);
            },

            loadOptions() {
                const field = fields[this.criterion.field];
                if (!field || field.type != 'select') {
                    return false;
                }
                jQuery.get('/list/' + field.list).done((response) => {
                    EventBus.$emit('openSelectModal', {
                        options: response,
                        key: this.index,
                        field: field.name,
                        selected: this.criterion.value.split(',')
                    });
                });
            }
        },

        created() {
            EventBus.$on('setCriterionValue', (index, values, labels) => {
                if (index != this.index) {
                    return false;
                }

                this.criterion.value = values.join(',');
                this.criterion.label = labels.join(', ');
            })
        }
    }
</script>
