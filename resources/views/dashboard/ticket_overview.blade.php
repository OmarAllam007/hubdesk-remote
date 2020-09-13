<style>
    .c-dashboardInfo {
        margin-bottom: 15px;

    }

    .c-dashboardInfo .wrap {
        background: #ffffff;
        box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
        border-radius: 7px;
        text-align: center;
        position: relative;
        overflow: hidden;
        padding: 40px 25px 20px;
        height: 100%;
    }

    .c-dashboardInfo__title,
    .c-dashboardInfo__subInfo {
        color: #6c6c6c;
        font-size: 1.18em;
    }

    .c-dashboardInfo span {
        display: block;
    }

    .c-dashboardInfo__count {
        font-weight: 600;
        font-size: 2.5em;
        line-height: 64px;
        color: #323c43;
    }

    .c-dashboardInfo .wrap:after {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 10px;
        content: "";
    }

    #firstChild .wrap:after {
        background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
        /*background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);*/
    }

    #secondChild .wrap:after {
        background: linear-gradient(81.67deg, #35393f 0%, rgba(27, 27, 32, 0.58) 100%);
    }

    #thirdChild .wrap:after {
        background: linear-gradient(69.83deg, #f47523 0%, #c49156 100%);
    }

    #fourthChild .wrap:after {
        background: linear-gradient(81.67deg, #034d25 0%, rgba(59, 151, 89, 0.73) 100%);
    }

    #fifthChild .wrap:after {
        background: linear-gradient(81.67deg, #630501 0%, #c52627 100%);
    }

    #sixthChild .wrap:after {
        background: linear-gradient(81.67deg, #034d25 0%, rgba(59, 151, 89, 0.73) 100%);
    }

    #seventhChild .wrap:after {
        background: linear-gradient(81.67deg, #034d25 0%, rgba(59, 151, 89, 0.73) 100%);
    }

    .c-dashboardInfo__title svg {
        color: #d7d7d7;
        margin-left: 5px;
    }

    .MuiSvgIcon-root-19 {
        fill: currentColor;
        width: 1em;
        height: 1em;
        display: inline-block;
        font-size: 24px;
        transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        user-select: none;
        flex-shrink: 0;
    }

</style>

<div>
    @foreach($data->ticketOverView as $key=>$tickets)
        <div class="row">
            <div class="col-lg-3 col-md-6"></div>
            <div class="c-dashboardInfo col-lg-3 col-md-6" id="firstChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('All Tickets')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                    {{$tickets['all']}}
                </span>
                </div>
            </div>
            <div class="c-dashboardInfo col-lg-3 col-md-6" id="secondChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('Open')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                     {{$tickets['open']}}
                </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6"></div>

            <div class="c-dashboardInfo col-lg-3 col-md-6" id="thirdChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('On Hold')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                    {{$tickets['onHold']}}
                </span>
                </div>
            </div>

            <div class="c-dashboardInfo col-lg-3 col-md-6" id="fourthChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('Resolved')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                    {{$tickets['resolved']}}
                </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6"></div>

            <div class="c-dashboardInfo col-lg-3 col-md-6" id="fifthChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('Overdue')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                    {{$tickets['overdue']}}
                </span>
                </div>
            </div>

            <div class="c-dashboardInfo col-lg-3 col-md-6" id="sixthChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('Closed On time')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                    {{$tickets['closedOnTime']}}
                </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6"></div>

            <div class="c-dashboardInfo col-lg-3 col-md-6" id="seventhChild">
                <div class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title" >
                        {{t('Customer Satisfaction')}}
                    </h4>
                    <span class="hind-font caption-12 c-dashboardInfo__count">
                   {{$tickets['customer_satisfaction']}} %
                </span>
                </div>
            </div>
        </div>

    @endforeach

</div>
