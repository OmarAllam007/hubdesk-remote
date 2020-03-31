<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-3">Business Unit/s</th>
                <th class="col-md-3">Number Of tickets</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addNew" type="button"><i
                            class="fa fa-plus-circle"></i>
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>

            <tr is="limitation-row" v-for="(row, index) in limitations" :key="index" :index="index"
                :row="row" :limitation="row"></tr>

            </tbody>
        </table>

        <div class="modal fade selection-modal" tabindex="-1" role="dialog" id="SelectLimitationBusinessUnits">
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
                                <option v-for="(label, index) in filteredLimitationOptions" :value="label.id">
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
    import LimitationRow from "./LimitationRow.vue";
    import EventBus from './../../Bus.js';

    export default {
        name: "Limitation",
        props: [ 'business_units','limitation_data'],

        data() {
            let limitations = this.limitation_data.length ? this.limitation_data : [];

            return {
                limitations,
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

            EventBus.$on('openSelectLimitationModal', (options) => {
                this.modal.field = 'Select Business Units';
                this.modal.options = this.business_units;
                this.modal.index = options.key;
                this.modal.selected = [];

                if (options.selected) {
                    this.modal.selected = options.selected;
                }

                jQuery('#SelectLimitationBusinessUnits').modal('show');
            });


            EventBus.$on('remove-limitation', (index, item) => {
                this.limitations.splice(this.limitations.indexOf(item), 1);
            });
        },

        methods: {
            addNew() {
                this.limitations.push({business_units: '',  number_of_tickets: 0,value:'',label:''});
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
                jQuery('#SelectLimitationBusinessUnits').modal('hide');
            }

        },
        computed: {
            filteredLimitationOptions() {
                if (!this.modal.search) {
                    return this.modal.options;
                }

                const term = this.modal.search.toLowerCase();
                let filtered = {};
                for (let key in this.modal.options) {
                    if (!this.modal.options.hasOwnProperty(key)) continue;
                    let value = this.modal.options[key];

                    if (value.name.toLowerCase().indexOf(term) != -1) {
                        filtered[key] = value;
                    }
                }

                return filtered;
            }
        },
        components: {LimitationRow}
    }
</script>

<style scoped>

</style>