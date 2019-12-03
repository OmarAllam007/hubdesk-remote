<template>
    <div>
        <fieldset >
            <legend></legend>
        </fieldset>
                <div class="form-group">
                    <label for="notification_period" class="control-label">
                    </label>
                        <input type="text" class="form-control" id="notification_period" name="notification_period" placeholder="0">
                </div>

                <div class="form-group">
                    <label for="notification_users" class="control-label">
                    </label>
                    <select  class="form-control input-sm select2"  multiple>
                        <option value="">Select User</option>
                        <option v-for="user in users" :value="user.id">{{user.name}}</option>
                    </select>
                </div>

    </div>
</template>

<script>
    export default {
        // name: "notifications",
        props:['users'],
        data(){
            return {
                notifications:'',
            }
        },
        created() {
console.log('here')
        }
    }
</script>

<style scoped>

</style>