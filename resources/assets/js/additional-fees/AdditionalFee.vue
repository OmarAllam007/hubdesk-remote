<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-6">Name</th>
                <th class="col-md-6">Cost</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addNew" type="button"><i
                            class="fa fa-plus-circle"></i></button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr is="additional-fee-row" v-for="(row, index) in charges" :key="index" :index="index"
                :row="row" :fee="row"></tr>
            </tbody>
        </table>
    </section>
</template>

<script>
    import AdditionalFeeRow from './AdditionalFeeRow.vue'
    import EventBus from '../Bus';

    export default {
        name: "additional-fee",
        props:['fees'],
        data() {
            let charges = [];


                // if (this.bu_id) {
                //     let roles = this.bu_id.bu_roles;
                //     $.each(roles, (key, role) => {
                //         charges.push({role_id: role.role_id, user_id: role.user_id})
                //     })
                // }

            return { charges }

        },
        methods: {
            addNew() {
                this.charges.push({name: '', cost: 0});
            }

        },
        created() {
            if (this.fees){
                this.charges = this.fees
            }

            EventBus.$on('removeFee', (index,fee) => {

                var idx = this.charges.indexOf(fee);
                console.log(idx)
                this.charges.splice(idx, 1);



            });
        },
        mounted(){

        },
        components: {AdditionalFeeRow}

    }
</script>

<style scoped>

</style>