<template>
    <tr>
        <td>
            <select @change="update" class="form-control input-sm" :name="`criterions[${index}][field]`"
                    v-model="criterion.field">
                <option value="">Select Field</option>
                <optgroup label="Request">
                    <option value="subject">Subject</option>
                    <option value="description">Description</option>
                    <option value="category_id">Category</option>
                    <option value="subcategory_id">Subcategory</option>
                    <option value="item_id">Item</option>
                    <option value="urgency_id">Urgency</option>
                    <option value="priority_id">Priority</option>
                    <option value="impact_id">Impact</option>
                    <option value="status_id">Status</option>

                </optgroup>
                <optgroup label="Requester">
                    <option value="requester_id">Requester</option>
                    <option value="business_unit_id">Business Unit</option>
                    <option value="location_id">Location</option>
                </optgroup>
                <optgroup label="Technician">
                    <option value="technician_id">Technician</option>
                    <option value="group_id">Support Group</option>
                </optgroup>

                <optgroup label="Date">
                    <option value="due_date">Due Date</option>
                    <option value="created_at">Created Date</option>
                    <option value="resolve_date">Resolved Date</option>
                </optgroup>
            </select>
        </td>
        <td>
            <select @change="update" class="form-control input-sm" :name="`criterions[${index}][operator]`"
                    v-model="criterion.operator">
                <option value="is">is</option>
                <option value="isnot" v-if="!showDate">is not</option>
                <option value="contains" v-if="!showDate">contains</option>
                <option value="notcontain" v-if="!showDate">does not contain</option>
                <option value="starts" v-if="!showDate">starts with</option>
                <option value="ends" v-if="!showDate">ends with</option>
                <option value="greater" v-if="showDate">Greater Than</option>
                <option value="less" v-if="showDate">Less Than</option>
            </select>
        </td>
        <td>
            <div class="input-group" v-if="showMenuIcon && !showDate">
                <input class="form-control input-sm" :name="`criterions[${index}][label]`" type="text"
                       @click="loadOptions()" v-model="criterion.label" readonly>
                <span class="input-group-btn">
                <button type="button" class="btn btn-default btn-sm" @click="loadOptions()"><i
                        class="fa fa-bars"></i></button>
            </span>
            </div>
            <input v-else-if="!showDate" class="form-control input-sm" :name="`criterions[${index}][label]`" type="text"
                   v-model="criterion.label" @change="update">

            <input v-if="showDate" type="date" class="form-control input-sm" :name="`criterions[${index}][value]`"
                   v-model="criterion.label"/>

            <input v-if="!showDate" type="hidden" :name="`criterions[${index}][value]`" v-model="criterion.value">

        </td>
        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from './Bus';

    const fields = {
        subject: {type: 'text'},
        description: {type: 'text'},
        category_id: {type: 'select', list: 'category', name: 'Category'},
        subcategory_id: {type: 'select', list: 'subcategory', name: 'Subcategory'},
        item_id: {type: 'select', list: 'item', name: 'Item'},
        location_id: {type: 'select', list: 'location', name: 'Location'},
        business_unit_id: {type: 'select', list: 'business-unit', name: 'Business Unit'},
        priority_id: {type: 'select', list: 'priority', name: 'Priority'},
        urgency_id: {type: 'select', list: 'urgency', name: 'Urgency'},
        impact_id: {type: 'select', list: 'impact', name: 'Impact'},
        technician_id: {type: 'select', list: 'technician', name: 'Technician'},
        group_id: {type: 'select', list: 'group', name: 'Support Group'},
        status_id: {type: 'select', list: 'status', name: 'Status'},
        due_date: {type: 'date', name: 'Due Date'},
        created_at: {type: 'date', name: 'Created Date'},
        resolve_date: {type: 'date', name: 'Resolved Date'},
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
                }
                else if (field.type == 'date') {
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
