<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-3">Role</th>
                <th class="col-md-6">Assign to</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addRole" type="button"><i
                            class="fa fa-plus-circle"></i></button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr is="Role" v-for="(role, index) in levels" :index="index" :key="index"
                :role="role"></tr>
            </tbody>
        </table>

    </section>
</template>

<script>
    import Role from './Role'
    import EventBus from './Bus';

    export default {
        name: "roles",
        props:['roles','users'],

        data(){
            return {
                levels:[],
            }
        },
        methods:{
            addRole(){



               $('.select2').select2({width: '100%', allowClear: true});
                this.levels.push({role_id:'',user_id:''});
            }

    },
        created() {
            //   this.levels.push({role_id:'',user_id:''});

            EventBus.$on('removeRole', (index) => {
                if (this.levels.length > 1) {
                   this.levels.splice(index,1)
                }
            });
        },
        components:{Role}

    }
</script>

<style scoped>

</style>