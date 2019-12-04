<template>
    <div>
                <span>
                    <button class="btn btn-xs btn-danger" @click.prevent="remove">
                        <i class="fa fa-minus"></i>
                    </button>
                    <label for="">Notification {{index+1}}</label>
                </span>

        <div class="form-group">
            <label :for="`notifications[${index}]['days']`" class="control-label">
                Days before end date
            </label>
            <input type="text" class="form-control" :id="`notifications[${index}][days]`"
                   :name="`notifications[${index}][days]`" placeholder="0" v-model="notification.days">
        </div>

        <div class="form-group">
            <label :for="`notifications[${index}][users][]`" class="control-label">
                Notified Users
            </label>
            <select class="form-control input-sm select2" :id="`notifications[${index}][users][]`"
                    :name="`notifications[${index}][users][]`" v-model="notification.users" multiple>
                <option value="">Select User</option>
                <option v-for="user in $parent.users" :value="user.id">{{user.name}}</option>
            </select>
        </div>
        <hr>
    </div>
</template>

<script>
    import EventBus from '../Bus';
    export default {
        name: "notification",
        props:['notification','index'],
        methods:{
            remove(){
                EventBus.$emit('remove-notification', this.index,this.notification);
            }
        },
        mounted() {
            $('.select2').select2({width: '100%', allowClear: true});
        }
    }
</script>

<style scoped>

</style>