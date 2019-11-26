<template>
    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td class="col-md-4">Parameters</td>
                <td class="col-md-6">Type</td>
                <td class="col-md-2">
                    <button class="btn btn-success" @click.prevent="AddRow">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr is="report-parameters-row" v-for="(row, index) in rows" :key="index" :index="index"
                :row="row" :fee="row"></tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import ReportParametersRow from "./ReportParametersRow";
    import {EventBus} from '../../js/EventBus.js'

    export default {
        name: "report-parameters",
        props: ['params'],

        data() {
            return {
                rows: []
            }
        },

        created() {
            if(this.params.length != 0){
                this.rows = this.params;
            }
        },

        mounted() {
            EventBus.$on('remove-params-row', (index, item) => {
                var idx = this.rows.indexOf(item);
                this.rows.splice(idx, 1);

            })
        },
        methods: {
            AddRow() {
                this.rows.push({name: '', type: ''})
            },
        },
        components: {
            ReportParametersRow
        }
    }
</script>

<style scoped>

</style>