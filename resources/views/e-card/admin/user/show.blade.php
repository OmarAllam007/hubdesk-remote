<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AlKifah Holding</title>
    <link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
    {{--    <link href="{{asset('css/ecard/bootstrap.min.css')}}" rel="stylesheet">--}}
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{asset('/css/ecard/flexslider.css')}}" rel="stylesheet">

    @php
    $view = $user->business_unit_view ? $user->business_unit_view->view_path : 'holding';
    @endphp
    <link href="{{asset('/css/ecard/'.$view.'/styles.css')}}" rel="stylesheet">
    <link href="{{asset('/css/ecard/queries.css')}}" rel="stylesheet">
    <link href="{{asset('/css/ecard/animate.css')}}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@v3.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body id="top">
<header id="home">


</header>

<section class="features text-center section-padding" id="features">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wp2 delay-1s">
                @php
                    $logo = $user->business_unit_view ? $user->business_unit_view->logo_path : '';
                @endphp
                <img style="width:120px" src="{{asset('/images/ecard/'.$logo)}}"
                     alt="logo">
            </div>
        </div>
    </div>
</section>


<section class="team text-center section-padding" id="team">
    <div class="container">

        <div class="row">
            <div class="col-md-12 wp5">
                <div class="circular--landscape">
                    <img src="{{$user->image}}" alt="{{$user->name}}"></div>
                <h2>{{$user->name}}</h2>
                <p>{{$user->position}}</p>

            </div>
        </div>
    </div>
</section>


<section class="dark-bg text-center section-padding contact-wrap" id="contact">

    <div class="container">
        <div class="row contact-details">
            <div class="col-md-12"><a href="{{route('e-card.admin.user.download',$user)}}">
                    <div class="light-box2 box2-hover">
                        <div class="contact-r2"><img style="width:30px" src="{{('/images/ecard/vcard.png')}}"></div>
                        <div class="contact-n2"><span>V-Card (Save Contact)</span></div>
                    </div>
                </a>
            </div>


            <div class="col-md-12"><a href="tel:{{$user->mobile}}">
                    <div class="light-box box-hover">
                        <div class="contact-r"><img style="width:30px" src="{{('/images/ecard/mob.png')}}"></div>
                        <div class="contact-n"><span>{{$user->mobile}}</span></div>
                    </div>
                </a>
            </div>

            @if($user->phone)
                <div class="col-md-12"><a href="tel:{{$user->phone}}">
                        <div class="light-box box-hover">
                            <div class="contact-r"><img style="width:30px" src="{{('/images/ecard/tel.png')}}"></div>
                            <div class="contact-n"><span>{{$user->phone}}</span></div>
                        </div>
                    </a>
                </div>
            @endif

            <div class="col-md-12"><a href="mailto: {{$user->email}}">
                    <div class="light-box box-hover">
                        <div class="contact-r"><img style="width:30px" src="{{('/images/ecard/email.png')}}"></div>
                        <div class="contact-n"><span>{{$user->email}}</span></div>
                    </div>
                </a>
            </div>

            @if($user->linkedin_url)
                <div class="col-md-12"><a href="//{{$user->linkedin_url}}">
                        <div class="light-box box-hover">
                            <div class="contact-r"><img style="width:30px" src="{{('/images/ecard/linked.png')}}"></div>
                            <div class="contact-n"><span>{{$user->name}}</span></div>
                        </div>
                    </a>
                </div>
            @endif

            @if($user->website)
                <div class="col-md-12"><a href="//{{$user->website}}" target="_blank">
                        <div class="light-box box-hover">
                            <div class="contact-r"><img style="width:30px" src="{{('/images/ecard/web.png')}}"></div>
                            <div class="contact-n"><span>{{$user->website}}</span></div>
                        </div>
                    </a>
                </div>
            @endif


            <div class="row">
                <div class="col-md-12">
                    <ul class="social-buttons">
                        @php
                            $businessUnit = \App\BusinessCardBusinessUnit::where('business_unit_id',$user->business_unit_id)->first();

                        @endphp
                        <li><a
                                    @if($businessUnit && $businessUnit->facebook)
                                        href="{{$businessUnit->facebook}}"
                                    @else
                                        href="#"
                                    @endif
                                    class="social-btn"><i
                                        class="fa fa-facebook"></i></a></li>
                        <li><a
                                    @if($businessUnit && $businessUnit->twitter)
                                        href="{{$businessUnit->twitter}}"
                                    @else
                                        href="#"
                                    @endif
                                    class="social-btn">
                                <i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a
                                    @if($businessUnit && $businessUnit->linkedin)
                                        href="{{$businessUnit->linkedin}}"
                                    @else
                                        href="#"
                                    @endif

                                    class="social-btn"><i
                                        class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<footer>
    <div class="container">
        <div class="row">

            <div class="col-md-12 credit">
                <p>{{\App\BusinessUnit::find($user->business_unit_id)->name ?? "N/A"}}</p>
            </div>
        </div>
    </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{('/js/ecard/waypoints.min.js')}}"></script>
<script src="{{('/js/ecard/bootstrap.min.js')}}"></script>
<script src="{{('/js/ecard/scripts.js')}}"></script>
<script src="{{('/js/ecard/jquery.flexslider.js')}}"></script>
<script src="{{('/js/ecard/modernizr.js')}}"></script>
</body>
</html>
