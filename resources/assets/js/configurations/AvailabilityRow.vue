<template>
    <tr>
        <td>
            <div class="input-group">
                <input class="form-control input-sm" :name="`availabilities[${index}][business_units_label]`" type="text"
                        readonly @click="loadOptions" v-model="availability.label">
                <input  type="hidden" :name="`availabilities[${index}][business_units_value]`" v-model="availability.value">

                <span class="input-group-btn">
                <button type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-bars"></i></button>
            </span>
            </div>
        </td>

        <td>
            <select :name="`availabilities[${index}][type]`" class="form-control" v-model="availability.type">
                <option value="">Select Type</option>
                <option :value="index+1" v-for="(type,index) in this.$parent.types">
                    {{type}}
                </option>
            </select>
        </td>

        <td>
            <input
                    v-if="availability.type == 1 || availability.type == 3"
                   type="text"
                   class="form-control"
                   :name="`availabilities[${index}][availability_until]`"
                   v-model="availability.available_until"
            >

            <select :name="`availabilities[${index}][availability_until]`"
                    v-if="availability.type == 2"
                    class="form-control"
                    v-model="availability.available_until"
            >

                <option value="">Select Month</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </td>

        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from '../Bus.js';

    export default {
        name: "availability-row",
        props: ['availability', 'index'],
        methods: {
            remove() {
                EventBus.$emit('remove-availability', this.index, this.availability);
            },
            loadOptions() {
                    EventBus.$emit('openSelectModal', {
                        key: this.index,
                        selected: this.availability.value.toString().split(',')
                    });

            }
        },
        created() {

            EventBus.$on('setBusinessUnits', (data) => {
                if (data.index != this.index) {
                    return false;
                }
                this.availability.value = data.values.join(',');
                this.availability.label = data.labels.join(', ');

            })
        }
    }
</script>

<style scoped>

</style>