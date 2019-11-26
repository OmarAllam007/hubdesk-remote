<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-4">Type</th>
                <th class="col-md-4">Requirement</th>
                <th class="col-md-4">Value</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addRequirement" type="button"><i
                            class="fa fa-plus-circle"></i></button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr is="service-requirement" v-for="(requirement, index) in requirements" :key="index" :index="index"
                :requirement="requirement"></tr>
            </tbody>
        </table>

        <div class="modal fade" role="dialog" id="openModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{modal.title}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text"  class="form-control" v-model="modal.search" placeholder="Filters" autofocus>
                        </div>
                        <div class="form-group">
                            <select class="form-control data-list" v-model="modal.selected" multiple="multiple" >
                            <option v-for="(value, index) in filteredOptions" :value="value.id" v-text="value.name"
                            :key="index"></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="applySelection"><i
                                class="fa fa-check-circle"></i> Apply
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
    import ServiceRequirement from './ServiceRequirement.vue'
    import EventBus from './Bus';

    export default {
        props:['requirements_data'],
        name: "service-requirements",
        data(){
            return {
                requirements:[],
                modal:{
                    title:'',
                    options:{},
                    key:'',
                    selected:[],
                    search:''
                }
            }
        },
        created(){
            if (this.requirements_data && this.requirements_data.length) {
                this.requirements = this.requirements_data;
            }

            EventBus.$on('openModal', (options) => {
                this.modal.title = options.field;
                this.modal.options = options.options;
                this.modal.key = options.key;
                this.modal.selected = [];
                this.modal.search = ''

                if (options.selected) {
                    this.modal.selected = options.selected;
                }
                jQuery('#openModal').modal('show');

            });

            EventBus.$on('remove-requirement',(index,requirement)=>{
                var id = this.requirements.indexOf(requirement);
                this.requirements.splice(id, 1);
            })
        },
        computed: {
            filteredOptions() {
                if (!this.modal.search) {
                    return this.modal.options;
                }

                const term = this.modal.search.toLowerCase();
                let filtered = {}
                for (let key in this.modal.options) {
                    let value = this.modal.options[key];
                    if (value.name.toLowerCase().indexOf(term) != -1) {
                        filtered[key] = value;
                    }
                }

                return filtered;
            }
        },
        methods:{
            addRequirement(){
                this.requirements.push({ field: '',
                    operator: 'is',
                    label: '',
                    value: '',
                    type:1,
                })
            },
            applySelection(){
                    let labels = [], i = 0;
                    for (i; i < this.modal.selected.length; ++i) {
                        for (let key in this.modal.options) {
                            let value = this.modal.options[key];
                            if(value.id==this.modal.selected[i]){
                                let value = this.modal.selected[i];
                                labels.push(this.modal.options[key].name);
                            }
                        }

                    }
                    EventBus.$emit('requirementsSelected', this.modal.key, this.modal.selected, labels);
                    jQuery('#openModal').modal('hide');

            }
        },
        components:{ServiceRequirement}
    }
</script>

<style scoped>
.data-list{
    height: 400px;
}
</style>