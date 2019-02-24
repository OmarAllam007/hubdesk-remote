<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-11">Role</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addRole" type="button"><i
                            class="fa fa-plus-circle"></i></button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(level,index) in  levels" :index="index" :level="level" :key="index">
                <td>
                    <select class="form-control input-sm select2" :name="`levels[${index}]`"
                           v-model="levels[index].role_id">
                        <option value="">Select Role</option>
                        <option v-for="role in roles" :value="role.id">{{role.name}}</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning pull-right" type="button" @click="deleteRow(index,level)"><i
                            class="fa fa-remove"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
    export default {
        name: "approval-levels",

        props:['approvals','roles','category'],
        data() {
            return {
                levels: [],
            }
        },
        created(){
          if(this.approvals.length){
              this.levels = this.approvals
          }
        },
        mounted(){
        },
        methods: {
            addRole() {
                this.levels.push({role_id: 0});
            },
            deleteRow(index, level) {
                var idx = this.levels.indexOf(level);
                console.log(idx);
                if (idx > -1) {
                    this.levels.splice(idx, 1);
                }
            }

        },

    }
</script>

<style scoped>

</style>