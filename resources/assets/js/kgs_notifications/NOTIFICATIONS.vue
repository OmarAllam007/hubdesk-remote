<template>
    <div>
        <fieldset>
            <legend>Notifications
                <button class="btn btn-xs btn-success" @click.prevent="addNewNotification"><i class="fa fa-plus"></i>
                </button>
            </legend>

            <notification v-for="(notification,index) in notifications" :key="index" :index="index"
                          :notification="notification"></notification>

        </fieldset>


    </div>
</template>

<script>
    import Notification from "./Notification";
    import EventBus from '../Bus';

    export default {
        props: ['users','db_notifications'],
        data() {
            return {
                notifications: [],
            }
        },

        methods: {
            addNewNotification() {
                this.notifications.push({days:1,users:[]});
            }
        },
        created() {
            this.notifications.push({days:1,users:[]});
            if(this.db_notifications.length){
                this.notifications = this.db_notifications
            }

            EventBus.$on('remove-notification', (index,notification) => {

                let idx = this.notifications.indexOf(notification);
                this.notifications.splice(idx, 1);

            });
        },
        components: {Notification}
    }
</script>

<style scoped>

</style>