<template>
    <tr>
        <td>

            <label>
                <input type="radio"  :name="`requirements[${index}][type]`" v-model="requirement.type"
                       value="1" @change="changeType">
                Service
            </label>
            /
            <label>
                <input type="radio"  :name="`requirements[${index}][type]`" v-model="requirement.type"
                       value="2" @change="changeType">
                Document
            </label>
        </td>
        <td>
            <!--:name="`requirements[${index}][field]`"-->
            <select @change="updateRequirement" v-show="requirement.type == 1"
                    class="form-control input-sm" v-model="requirement.field" :name="`requirements[${index}][field]`">
                <option value="">Select Field</option>
                <option value="category_id">Category</option>
                <option value="subcategory_id">Subcategory</option>
                <option value="item_id">Item</option>
            </select>

            <input type="text" v-show="requirement.type == 2" v-model="requirement.field"  class="form-control input-sm"
                   :name="`requirements[${index}][field]`">


        </td>
        <td>
            <div class="input-group" v-show="requirement.type == 1">
                <input class="form-control input-sm" type="text" @click="loadData()" v-model="requirement.label" :name="`requirements[${index}][label]`"
                       readonly>
                <input  type="hidden" :name="`requirements[${index}][value]`" v-model="requirement.value">

                <span class="input-group-btn">
                <button type="button" class="btn btn-default btn-sm" data-toggle="modal"><i
                        class="fa fa-bars"></i></button>
                </span>
            </div>

            <div class="input-group" v-show="requirement.type == 2">
                <input type="text"   class="form-control input-sm"
                       :name="`requirements[${index}][value]`" v-model="requirement.value">
            </div>
        </td>
        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="removeRequirement()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from './Bus';

    const fields = {
        category_id: {type: 'select', list: 'category', name: 'Category'},
        subcategory_id: {type: 'select', list: 'subcategory', name: 'Subcategory'},
        item_id: {type: 'select', list: 'item', name: 'Item'},
    };

    export default {
        props:['requirement','index'],

        name: "service-requirement",
        created(){
            EventBus.$on('requirementsSelected', (index, values, labels) => {
                if (index != this.index) {
                    return false;
                }

                this.requirement.value = values.join(',');
                this.requirement.label = labels.join(', ');
            })
        },
        methods:{
            changeType(){
                this.requirement.value = '';
                this.requirement.label = '';
            },
            updateRequirement(){
                this.requirement.value = '';
                this.requirement.label = '';
            },
            loadData(){
                const field = fields[this.requirement.field];
                if (!field ) {
                    return false;
                }
                jQuery.get('/list/' + field.list).done((response) => {
                    EventBus.$emit('openModal', {
                        options: response,
                        key: this.index,
                        field: field.name,
                        selected: this.requirement.value.split(',')
                    });
                });
            },
            removeRequirement(){
                EventBus.$emit('remove-requirement', this.index,this.requirement);
            }
        }
    }
</script>

<style scoped>

</style>