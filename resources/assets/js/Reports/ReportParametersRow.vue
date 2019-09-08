<template>
    <tr v-model="row">
        <td>
            <input type="text" class="form-control" :name="`filters[${index}][param]`" v-model="row.title">
        </td>
        <td>
            <select :name="`filters[${index}][type]`" class="form-control" v-model="row.type">
                <option value="">Select Type</option>
                <option value="date">Date</option>
                <option value="text">Text</option>
            </select>
        </td>
        <td>
            <button class="btn btn-danger" @click.prevent="removeRow()">
                <i class="fa fa-minus"></i>
            </button>
        </td>
    </tr>

</template>

<script>
    import {EventBus}from '../../js/EventBus.js'

    export default {
        props:['row','index'],
        name: "report-parameters-row",
        data() {
            return {

            }
        },
        methods: {
            removeRow() {
                EventBus.$emit('remove-params-row',this.index,this.row)
            }
        }
    }
</script>

<style scoped>

</style>