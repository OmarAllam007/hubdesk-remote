<template>
    <div>
        <div class="col-md-8">
            <ticket-requirement v-for="(require,index) in requirements" :requirement="require" :index="index"
                                :key="index"></ticket-requirement>
            <div class="form-group">
                <label>
                    Attachments
                </label>
                <input type="file" class="form-control-file" name="ticket-attachments[]" multiple>
            </div>
            <button class="btn btn-sm btn-success" :disabled="!allHaveChecked">Submit</button>
        </div>
    </div>
</template>

<script>
    import EventBus from '../Bus';
    import TicketRequirement from './TicketRequirement.vue'
    import Attachments from '../AttachmentModal.vue'

    export default {
        name: "ticket-requirements",
        props: ['requirements', 'data'],
        data() {
            return {
                items: [],
                attachments: [],
                ticket_id: ''
            }
        },
        created() {
            if (this.requirements && this.requirements.length) {
                for (var i = 0; i < this.requirements.length; i++) {
                    this.items.push({'id': this.requirements[i]['id'], 'checked': false, 'attached': false,'type':this.requirements[i]['type']})
                }
            }

            this.observeChildrenChange()
        },
        methods: {
            observeChildrenChange() {
                EventBus.$on('requirement-checked', (id) => {
                    for (var i = 0; i < this.items.length; i++) {
                        if (this.items[i].id == id) {
                            this.items[i].checked = !this.items[i].checked
                            if (!this.items[i].checked) {
                                this.items[i].attached = false
                            }
                        }
                    }
                });

                EventBus.$on('requirement-attach', (id) => {
                    for (var i = 0; i < this.items.length; i++) {
                        if (this.items[i].id == id) {
                            this.items[i].attached = !this.items[i].attached
                        }
                    }
                });
            }
        },
        computed: {
            allHaveChecked() {
                for (var i = 0; i < this.items.length; i++) {
                    // console.log(this.items[i].checked, this.items[i].attached)
                    if (this.items[i].checked && !this.items[i].attached && this.items[i].type != 2) {
                        return false;
                    }
                }
                return true
            }
        },
        components: {TicketRequirement, Attachments}
    }
</script>

<style scoped>

</style>