<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-3">Business Unit/s</th>
                <th class="col-md-3">Type</th>
                <th class="col-md-3">Available Until</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addNew" type="button"><i
                            class="fa fa-plus-circle"></i>
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>

            <tr is="availability-row" v-for="(row, index) in availabilities" :key="index" :index="index"
                :row="row" :availability="row"></tr>

            </tbody>
        </table>

        <div class="modal fade selection-modal" tabindex="-1" role="dialog" id="SelectBusinessUnits">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{modal.field}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="search" class="form-control" v-model="modal.search" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <select class="form-control" v-model="modal.selected" multiple="multiple">
                                <option v-for="(label, index) in filteredOptions" :value="label.id">
                                    {{label.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="modalApply"><i
                                class="fa fa-check-circle" ></i> Apply
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import AvailabilityRow from "./AvailabilityRow";
    import EventBus from '../Bus.js';

    export default {
        name: "Availability",
        props: ['types', 'business_units','availabilities_data'],

        data() {
            let availabilities = this.availabilities_data;

            if (!availabilities) {
                availabilities = [];
            }
            return {
                availabilities,
                modal: {
                    field: '',
                    options: {},
                    search: '',
                    index: null,
                    selected: []
                }
            }
        },
        created() {

            EventBus.$on('openSelectModal', (options) => {
                this.modal.field = 'Select Business Units';
                this.modal.options = this.business_units;
                this.modal.index = options.key;
                this.modal.selected = [];

                if (options.selected) {
                    this.modal.selected = options.selected;
                }

                jQuery('#SelectBusinessUnits').modal('show');
            });


            EventBus.$on('remove-availability', (index, item) => {
                this.availabilities.splice(this.availabilities.indexOf(item), 1);
            });
        },

        methods: {
            addNew() {
                this.availabilities.push({business_units: '', type: '', available_until: 0,value:'',label:''});
            },

            modalApply(){
                let labels = [], i = 0;
                for (i; i < this.modal.selected.length; ++i) {
                    let value = this.modal.selected[i];
                    this.modal.options.forEach((item)=>{
                        if(value == item.id){
                            labels.push(item.name);
                        }
                    })

                }

                EventBus.$emit('setBusinessUnits', { index: this.modal.index, values: this.modal.selected, labels});
                jQuery('#SelectBusinessUnits').modal('hide');
            }

        },
        computed: {
            filteredOptions() {
                if (!this.modal.search) {
                    return this.modal.options;
                }

                const term = this.modal.search.toLowerCase();
                let filtered = {};
                for (let key in this.modal.options) {
                    if (!this.modal.options.hasOwnProperty(key)) continue;
                    let value = this.modal.options[key];
                    if (value.toLowerCase().indexOf(term) != -1) {
                        filtered[key] = value;
                    }
                }

                return filtered;
            }
        },
        components: {AvailabilityRow}
    }
</script>

<style scoped>

</style>