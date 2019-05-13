<template>
    <tr>
        <td>
           <input type="text" class="form-control" :name="`fees[${index}][name]`" placeholder="Name" v-model="fee.name">
        </td>

        <td>
           <input type="number" class="form-control" :name="`fees[${index}][cost]`" placeholder="Cost" v-model="fee.cost">
        </td>

        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from '../Bus';

    export default {
        name: "additional-free-row",
        props:['fee','index'],
        methods:{
            remove(){
                // this.$el.remove()
                EventBus.$emit('removeFee', this.index,this.fee);
            }
        }
    }
</script>

<style scoped>

</style>