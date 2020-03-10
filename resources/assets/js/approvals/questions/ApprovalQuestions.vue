<template>
    <section class="table-container">
        <table class="listing-table table-bordered">
            <thead>
            <tr>
                <th class="col-md-10">Questions</th>
                <th>
                    <button class="btn btn-sm btn-primary pull-right" @click="addNew" type="button"><i
                            class="fa fa-plus-circle"></i></button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr is="approval-question-row" v-for="(question, index) in questions" :key="index" :index="index"
                :row="question" :question="question"></tr>
            </tbody>
        </table>
    </section>
</template>

<script>
    import ApprovalQuestionRow from "./ApprovalQuestionRow";

    export default {
        name: "ApprovalQuestions",
        data() {
            return {
                questions: [],
            }
        },
        methods: {
            addNew() {
                this.questions.push({description: ''});
            }
        },
        components: {ApprovalQuestionRow}
    }
</script>

<style scoped>

</style>