<template>
    <div>
        <div class="form-group">
            <div class="requirement-container">
                <div class="requirement-check">
                    <input type="checkbox" :id="`requirement-checkbox[${index}]`"
                           :name="`requirements[${index}][checked]`"
                           class="requirement-checkbox" @change="updateChecked()">
                    <label :for="`requirement-checkbox[${index}]`">
                       <span v-show="requirement.type == 1"> {{requirement.label}} - {{requirement.cost}} SAR</span>
                       <span v-show="requirement.type ==  2"> {{requirement.field}}</span>
                       <span v-show="requirement.type ==  3"> {{requirement.field}}</span>
                    </label>
                </div>
                <div class="requirement-actions" >
                    <input type="file" :name="`requirements[${index}][file]`" class="requirement-attachment" v-if="requirement.type != 3 && checked" @change="attachFile()">
                    <input type="text" :name="`requirements[${index}][input]`" class="form-control"  v-if="requirement.type == 3 && checked">

                    <input type="hidden" :name="`requirements[${index}][reference]`" :value="requirement.value">
                    <input type="hidden" :name="`requirements[${index}][reference_type]`" :value="requirement.reference_type">
                    <input type="hidden" :name="`requirements[${index}][type]`" :value="requirement.type">
                    <input type="hidden" :name="`requirements[${index}][id]`" :value="requirement.id">
                    <!--<button class="btn btn-sm btn-primary" v-else>Create Ticket</button>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import EventBus from '../Bus';

    export default {
        name: "ticket-requirement",
        props:['requirement','index'],
        data(){
            return {
                checked:false,
                type:'',
            }
        },
        created(){
            this.type = this.requirement.type
        },
        methods:{
            updateChecked(){
                this.checked = !this.checked;
                EventBus.$emit('requirement-checked',this.requirement.id)
            },
            attachFile(){
                EventBus.$emit('requirement-attach',this.requirement.id)
            }
        }
    }
</script>

<style scoped>
    .requirement-container {
        display: flex;
        flex-direction: row;
        padding: 20px;
        border: 1px solid #194F7E;
        border-radius: 10px;
        margin-top: 20px;
        justify-content: space-between;
    }
    .requirement-container:hover{
        background-color: #e6e6e6;
    }
    .requirement-actions{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .requirement-attachment{
        padding-top: 10px;
        width: 180px;
    }

    .requirement-checkbox{
        position: absolute;
        opacity: 0;
    }

    .requirement-checkbox + label {
        position: relative;
        cursor: pointer;
        padding: 0;
    }
    .requirement-checkbox + label:before {
        content: "";
        margin-right: 10px;
        display: inline-block;
        vertical-align: text-top;
        width: 20px;
        height: 20px;
        background: white;
        border: 1px solid #194F7E;
    }

    .requirement-checkbox:hover + label:before {
        background: rgba(27, 96, 145, 0.4);
    }

    .requirement-checkbox:focus + label:before {
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.12);
    }


    .requirement-checkbox:checked + label:before {
        background: #194F7E;
    }


    .requirement-checkbox:checked + label:after {
        content: "";
        position: absolute;
        left: 5px;
        top: 9px;
        background: white;
        width: 2px;
        height: 2px;
        box-shadow: 2px 0 0 white, 4px 0 0 white, 4px -2px 0 white, 4px -4px 0 white,
        4px -6px 0 white, 4px -8px 0 white;
        transform: rotate(45deg);
    }

</style>