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
            <tr is="role" v-for="(row, index) in levels" :key="index" :index="index"
                :role="row"></tr>
            </tbody>
        </table>
    </section>
</template>

<script>
    import Role from './Role'
    import EventBus from './Bus';

    export default {
        name: "roles",
        props: ['roles', 'users', 'bu_id'],

        data() {
            let levels = [];

                if (this.bu_id) {
                    let roles = this.bu_id.bu_roles;
                    $.each(roles, (key, role) => {
                        levels.push({role_id: role.role_id, user_id: role.user_id})
                    })
                }

            return { levels }

        },
        methods: {
            addRole() {
                this.levels.push({role_id: '', user_id: ''});
            }

        },
        created() {


            EventBus.$on('removeRole', (index,level) => {

                    var idx = this.levels.indexOf(level);
                    console.log(idx);

                        this.levels.splice(idx, 1);



            });
        },
        components: {Role}

    }
</script>

<style scoped>

</style>