<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <legend>Report Type</legend>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="1" checked @click="change_report_type(1)">
                            Once
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="2" class="" @click="change_report_type(2)">
                            Daily
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="3" class="" @click="change_report_type(3)">
                            Weekly
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="4" @click="change_report_type(4)">
                            Monthly
                        </label>
                    </div>
                </div>
            </div>


            <div class="col-md-10">
                <fieldset id="once" v-if="report_type == 1">
                    <legend>Once</legend>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="once_date">Date:</label>
                                <input type="datetime-local" class="form-control" name="scheduled_time[date]"
                                       id="once_date">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset id="daily" v-if="report_type == 2">
                    <legend>Daily</legend>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="daily_hour">Hour:</label>
                                <select name="scheduled_time[hour]" id="daily_hour" class="form-control">
                                    <option :value="index" v-for="(n,i) in 12">{{i}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="daily_mins">Minutes:</label>
                                <select name="scheduled_time[minutes]" id="daily_mins" class="form-control">
                                    <option :value="index" v-for="(n,i) in 60">{{i}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset id="weekly" v-if="report_type == 3">
                    <legend>Weekly</legend>
                    <div class="panel panel-primary">
                        <div class="panel-body">

                            <fieldset>
                                <legend>
                                    <label for="everyday">
                                        <input type="checkbox" name="everyday" id="everyday" @click="select_all_days()"
                                               :checked="all_week">
                                        Everyday
                                    </label>
                                </legend>
                                <div class="form-group">
                                    <label for="saturday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="6" id="saturday"
                                               :checked="all_week">
                                        Saturday
                                    </label>

                                    <label for="sunday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="0" id="sunday"
                                               :checked="all_week">
                                        Sunday
                                    </label>

                                    <label for="monday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="1" id="monday"
                                               :checked="all_week">
                                        Monday
                                    </label>
                                    <label for="tuesday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="2" id="tuesday"
                                               :checked="all_week">
                                        Tuesday
                                    </label>

                                    <label for="wednesday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="3" id="wednesday"
                                               :checked="all_week">
                                        Wednesday
                                    </label>

                                    <label for="thursday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="4" id="thursday"
                                               :checked="all_week">
                                        Thursday
                                    </label>

                                    <label for="friday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="5" id="friday"
                                               :checked="all_week">
                                        Friday
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="weekly_hour">Hour:</label>
                                    <select name="scheduled_time[hour]" id="weekly_hour" class="form-control">
                                        <option :value="i" v-for="(n,i) in 13">{{i}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="weekly_hour">Minutes:</label>
                                    <select name="scheduled_time[minutes]" id="weekly_minutes" class="form-control">
                                        <option :value="i" v-for="(n,i) in 60">{{i}}</option>
                                    </select>
                                </div>
                            </fieldset>

                        </div>
                    </div>
                </fieldset>

                <fieldset id="monthly" v-if="report_type == 4">
                    <legend>Monthly</legend>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <fieldset>
                                <legend>
                                    <label for="every_month">
                                        <input type="checkbox" name="every_month" id="every_month"
                                               @click="select_all_months()" :checked="all_months">
                                        Every Month
                                    </label>
                                </legend>
                                <div class="form-group">
                                    <label for="january">
                                        <input type="checkbox" name="scheduled_time[months][]" value="1" id="january"
                                               :checked="all_months">
                                        January
                                    </label>

                                    <label for="february">
                                        <input type="checkbox" name="scheduled_time[months][]" value="2" id="february"
                                               :checked="all_months">
                                        February
                                    </label>

                                    <label for="march">
                                        <input type="checkbox" name="scheduled_time[months][]" value="3" id="march"
                                               :checked="all_months">
                                        March
                                    </label>

                                    <label for="april">
                                        <input type="checkbox" name="scheduled_time[months][]" value="4" id="april"
                                               :checked="all_months">
                                        April
                                    </label>

                                    <label for="may">
                                        <input type="checkbox" name="scheduled_time[months][]" value="5" id="may"
                                               :checked="all_months">
                                        May
                                    </label>

                                    <label for="june">
                                        <input type="checkbox" name="scheduled_time[months][]" value="6" id="june"
                                               :checked="all_months">
                                        June
                                    </label>

                                    <label for="july">
                                        <input type="checkbox" name="scheduled_time[months][]" value="7" id="july"
                                               :checked="all_months">
                                        July
                                    </label>

                                    <label for="august">
                                        <input type="checkbox" name="scheduled_time[months][]" value="8" id="august"
                                               :checked="all_months">
                                        August
                                    </label>

                                    <label for="september">
                                        <input type="checkbox" name="scheduled_time[months][]" value="9" id="september"
                                               :checked="all_months">
                                        September
                                    </label>

                                    <label for="october">
                                        <input type="checkbox" name="scheduled_time[months][]" value="10" id="october"
                                               :checked="all_months">
                                        October
                                    </label>

                                    <label for="november">
                                        <input type="checkbox" name="scheduled_time[months][]" value="11" id="november"
                                               :checked="all_months">
                                        November
                                    </label>

                                    <label for="december">
                                        <input type="checkbox" name="scheduled_time[months][]"value="12"  id="december"
                                               :checked="all_months">
                                        December
                                    </label>

                                </div>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label for="monthly_day">Day:</label>
                                        <select name="scheduled_time[day]" id="monthly_day" class="form-control">
                                            <option :value="index" v-for="index in 31">{{index}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="monthly_hour">Hour:</label>
                                        <select name="scheduled_time[hour]" id="monthly_hour" class="form-control">
                                            <option :value="i" v-for="(n,i) in 12">{{i}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="monthly_hour">Minutes:</label>
                                        <select name="scheduled_time[minutes]" id="monthly_minutes" class="form-control">
                                            <option :value="i" v-for="(n,i) in 60">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>

        </div>

        <div class="row">

            <fieldset class="col-md-6">
                <legend>Email Information</legend>
                <div class="form-group">
                    <label for="user_id">Send To:</label>
                    <select name="to[]" id="user_id" class="form-control select2" multiple size="20">
                        <option value="" disabled="disabled">Select User</option>
                        <option v-for="user in users" :value="user.id">
                            {{user.name}}
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject">
                </div>
                <div class="form-group">
                    <label for="message"></label>
                    <textarea name="message" id="message" cols="50" rows="10" class="form-control"></textarea>
                </div>
            </fieldset>

            <div class="col-md-6">
                <fieldset>
                    <legend>Select Report</legend>
                    <div class="form-group">
                        <label for="report_id">Report:</label>
                        <select name="report_id" id="report_id" class="form-control">
                            <option value="">Select Report</option>
                            <optgroup v-for="(folder, folder_name) in reports" :label="folder_name">
                                <option v-for="report in folder" :value="report.id">
                                    {{report.title}}
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="format">Format:</label>
                        <select name="format" id="format" class="form-control">
                            <option value="">Select Format</option>
                            <option value="1"> PDF</option>
                            <option value="2"> Excel</option>
                        </select>
                    </div>

                </fieldset>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['reports', 'users'],
        name: "scheduled-report",

        data() {
            return {
                report_type: 1,
                all_week: false,
                all_months: false,
            }
        },
        methods: {
            change_report_type(id) {
                this.report_type = id
            },
            select_all_days() {
                this.all_week = !this.all_week
            },
            select_all_months() {
                this.all_months = !this.all_months
            }

        }
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