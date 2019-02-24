<template>
    <tr>
        <td>
            <select  class="form-control input-sm select2" :name="`roles[${index}][role_id]`" v-model="role.role_id">
                <option value="">Select Role</option>
                <option v-for="role in $parent.roles" :value="role.id">{{role.name}}</option>
            </select>
        </td>
        <td>
            <select  class="form-control input-sm select2" :name="`roles[${index}][user_id]`" v-model="role.user_id">
                <option value="">Select User</option>
                <option v-for="user in $parent.users" :value="user.id">{{user.name}}</option>
            </select>
        </td>
        <td>
            <button class="btn btn-sm btn-warning pull-right" type="button" @click="remove()"><i
                    class="fa fa-remove"></i></button>
        </td>
    </tr>
</template>

<script>
    import EventBus from './Bus';

    export default {
        name: "Role",
        props:['role','index'],
        methods:{
            remove(){
                // this.$el.remove()
                EventBus.$emit('removeRole', this.index,this.role);
            }
        },
        mounted(){
            $('.select2').select2({width: '100%', allowClear: true});
        }
    }
</script>

<style scoped>

</style>