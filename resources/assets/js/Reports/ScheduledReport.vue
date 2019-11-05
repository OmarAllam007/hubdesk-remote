<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <legend>Report Type</legend>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="1" checked @click="change_report_type(1)"
                                   :checked="report_type == 1">
                            Once
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="2" class="" @click="change_report_type(2)"
                                   :checked="report_type == 2">
                            Daily
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="3" class="" @click="change_report_type(3)"
                                   :checked="report_type == 3">
                            Weekly
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="report-label">
                        <label>
                            <input type="radio" name="type" value="4" @click="change_report_type(4)"
                                   :checked="report_type == 4">
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
                                        <input type="checkbox" name="everyday" id="everyday" @click="selectAllInputs()"
                                               :checked="selectAll">
                                        Everyday
                                    </label>
                                </legend>
                                <div class="form-group">
                                    <label for="saturday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="6" id="saturday"
                                               :checked="checkDayOrMonth(6)">
                                        Saturday
                                    </label>

                                    <label for="sunday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="0" id="sunday"
                                               :checked="checkDayOrMonth(0)">
                                        Sunday
                                    </label>

                                    <label for="monday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="1" id="monday"
                                               :checked="checkDayOrMonth(1)">
                                        Monday
                                    </label>
                                    <label for="tuesday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="2" id="tuesday"
                                               :checked="checkDayOrMonth(2)">
                                        Tuesday
                                    </label>

                                    <label for="wednesday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="3" id="wednesday"
                                               :checked="checkDayOrMonth(3)">
                                        Wednesday
                                    </label>

                                    <label for="thursday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="4" id="thursday"
                                               :checked="checkDayOrMonth(4)">
                                        Thursday
                                    </label>

                                    <label for="friday">
                                        <input type="checkbox" name="scheduled_time[days][]" value="5" id="friday"
                                               :checked="checkDayOrMonth(5)">
                                        Friday
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="weekly_hour">Hour:</label>
                                    <select name="scheduled_time[hour]" id="weekly_hour" class="form-control">
                                        <option :value="i" v-for="(n,i) in 13" :selected="report.scheduled_time.hour == i">{{i}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="weekly_hour">Minutes:</label>
                                    <select name="scheduled_time[minutes]" id="weekly_minutes" class="form-control">
                                        <option :value="i" v-for="(n,i) in 60" :selected="report.scheduled_time.minutes == i">{{i}}</option>
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
                                               @click="selectAllInputs()" :checked="selectAll">
                                        Every Month
                                    </label>
                                </legend>
                                <div class="form-group">
                                    <label for="january">
                                        <input type="checkbox" name="scheduled_time[months][]" value="1" id="january"
                                               :checked="checkDayOrMonth(1)">
                                        January
                                    </label>

                                    <label for="february">
                                        <input type="checkbox" name="scheduled_time[months][]" value="2" id="february"
                                               :checked="checkDayOrMonth(2)">
                                        February
                                    </label>

                                    <label for="march">
                                        <input type="checkbox" name="scheduled_time[months][]" value="3" id="march"
                                               :checked="checkDayOrMonth(3)">
                                        March
                                    </label>

                                    <label for="april">
                                        <input type="checkbox" name="scheduled_time[months][]" value="4" id="april"
                                               :checked="checkDayOrMonth(4)">
                                        April
                                    </label>

                                    <label for="may">
                                        <input type="checkbox" name="scheduled_time[months][]" value="5" id="may"
                                               :checked="checkDayOrMonth(5)">
                                        May
                                    </label>

                                    <label for="june">
                                        <input type="checkbox" name="scheduled_time[months][]" value="6" id="june"
                                               :checked="checkDayOrMonth(6)">
                                        June
                                    </label>

                                    <label for="july">
                                        <input type="checkbox" name="scheduled_time[months][]" value="7" id="july"
                                               :checked="checkDayOrMonth(7)">
                                        July
                                    </label>

                                    <label for="august">
                                        <input type="checkbox" name="scheduled_time[months][]" value="8" id="august"
                                               :checked="checkDayOrMonth(8)">
                                        August
                                    </label>

                                    <label for="september">
                                        <input type="checkbox" name="scheduled_time[months][]" value="9" id="september"
                                               :checked="checkDayOrMonth(9)">
                                        September
                                    </label>

                                    <label for="october">
                                        <input type="checkbox" name="scheduled_time[months][]" value="10" id="october"
                                               :checked="checkDayOrMonth(10)">
                                        October
                                    </label>

                                    <label for="november">
                                        <input type="checkbox" name="scheduled_time[months][]" value="11" id="november"
                                               :checked="checkDayOrMonth(11)">
                                        November
                                    </label>

                                    <label for="december">
                                        <input type="checkbox" name="scheduled_time[months][]" value="12" id="december"
                                               :checked="checkDayOrMonth(12)">
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
                                        <select name="scheduled_time[minutes]" id="monthly_minutes"
                                                class="form-control">
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
                        <option v-for="user in users" :value="user.id" :selected="selectUser(user.id)">
                            {{user.name}}
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject"
                           :value="report ? report.subject :''">
                </div>
                <div class="form-group">
                    <label for="message"></label>
                    <textarea name="message" id="message" cols="50" rows="10" class="form-control"
                              v-text="report ? report.message : ''"></textarea>
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
                                <option v-for="r in folder" :value="r.id" :selected="selectReport(r)">
                                    {{r.title}}
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="format">Format:</label>
                        <select name="format" id="format" class="form-control">
                            <option value="">Select Format</option>
                            <option value="1" :selected="report ? report.format == 1 : false"> PDF</option>
                            <option value="2" :selected="report ? report.format == 2 : false"> Excel</option>
                        </select>
                    </div>

                </fieldset>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['reports', 'users', 'report'],
        name: "scheduled-report",
        data() {
            return {
                report_type: 1,
                all_week: false,
                all_months: false,
                months:[],
                days:[],
                select_days:false,
                select_months:false,

            }
        },
        created() {
            if (this.report) {
                this.report_type = this.report.type;
                if(this.report.type === 3){
                    this.days = this.report.scheduled_time.days
                }
                else if(this.report.type === 4){
                    this.months = this.report.scheduled_time.months
                }
                this.checkTheSelection()
            }
        },


        methods: {
            checkTheSelection(){
                if(this.report.type == 3 && this.days.length == 7){
                    this.select_days = true;
                }else if (this.report.type == 4 && this.months.length == 12){
                    this.select_months = true;
                }
            },
            change_report_type(id) {
                this.report_type = id;
                this.days = [];
                this.months = [];
                this.select_days = false;
                this.select_months = false;
            },
            select_all_days() {
                this.all_week = !this.all_week
            },
            select_all_months() {
                this.all_months = !this.all_months
            },
            selectUser(id) {
                if (this.report) {
                    return this.report.to.indexOf("" + id + "") > -1
                }
                return false;
            },

            selectReport(r) {
                if (this.report) {
                    return r.id === this.report.report_id
                }
                return false;
            },

            checkDayOrMonth(item){
                if(this.report_type == 3){
                    return this.days.includes(""+item)
                }else if(this.report_type == 4){
                    return this.months.includes(""+item)
                }
            },
            selectAllInputs(){
                if(this.report_type === 3){
                    $('input[name$="scheduled_time[days][]"]:checkbox').prop('checked',false);
                    this.select_days = !this.select_days;
                    $('input[name$="scheduled_time[days][]"]:checkbox').prop('checked',this.select_days)
                }else if(this.report_type === 4){
                    this.select_months = !this.select_months;
                    $('input[name$="scheduled_time[months][]"]:checkbox').prop('checked',this.select_months)
                }

            }

        },
        computed:{
            selectAll(){
                if(this.report_type == 3){
                    return this.days.length == 7
                }else if (this.report_type == 4){
                    return this.months.length == 12
                }
            },
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