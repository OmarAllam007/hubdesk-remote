<template>
    <tr>
        <td>
            <div class="input-group">
                <input class="form-control input-sm" :name="`limitations[${index}][label]`" type="text"
                        readonly @click="loadLimitationOptions" v-model="limitation.label">
                <input  type="hidden" :name="`limitations[${index}][value]`" v-model="limitation.value">

                <span class="input-group-btn">
                <button type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-bars"></i></button>
            </span>
            </div>
        </td>

        <td>
            <input

                   type="number"
                   class="form-control"
                   :name="`limitations[${index}][number_of_tickets]`"
                   v-model="limitation.number_of_tickets">
        </td>

        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from '../../Bus.js';

    export default {
        name: "limitation-row",
        props: ['limitation', 'index'],
        methods: {
            remove() {
                EventBus.$emit('remove-limitation', this.index, this.limitation);
            },
            loadLimitationOptions() {
                    EventBus.$emit('openSelectLimitationModal', {
                        key: this.index,
                        selected: this.limitation.value.toString().split(',')
                    });

            }
        },
        created() {

            EventBus.$on('setBusinessUnits', (data) => {
                if (data.index != this.index) {
                    return false;
                }
                this.limitation.value = data.values.join(',');
                this.limitation.label = data.labels.join(', ');

            })
        }
    }
</script>

<style scoped>

</style>