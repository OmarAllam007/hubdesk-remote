<template>
    <div>
        <fieldset>
            <legend>Report Type</legend>
            <div class="form-group">
                <div class="report-label">
                    <label>
                        <input type="radio" name="type" class="">
                        Once
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="report-label">
                    <label>
                        <input type="radio" name="type" class="">
                        Daily
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="report-label">
                    <label>
                        <input type="radio" name="type" class="">
                        Weekly
                    </label>
                </div>
            </div>
            <div class="form-group form-check-inline">
                <div class="report-label">
                    <label>
                        <input type="radio" name="type" class="">
                        Monthly
                    </label>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Select Report</legend>
            <div class="form-group">

            </div>
        </fieldset>

    </div>

</template>

<script>
    export default {
        name: "scheduled-report"
    }
</script>

<style scoped>
    .report-label {
        margin: 0 20px 0 0px;
        border: 1px solid lightgray;
        padding: 20px;
        border-radius: 20px;
        width: 110px;
        height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>