@component('mail::message')
Dear Mr / Ms:  {{$name}}
<br/><br/>
You have successfully submitted your Co-op internship application. Your application reference number is #{{$ticket->id}}

Please note that your application will be reviewed considering our current organizational requirements and in case you are shortlisted for any available internship opportunities during the requested period, someone from our training team will contact you for further steps.

In case you don't hear back from us within 15 days please consider that we are unable to entertain your request on this occasion. For any further queries, you may contact on internship@alkifah.com while mentioning your application reference number.

Thank you, again, for your interest in AlKifah Group of Companies, we look forward to having you part of our family.
<br/><br/>
Kind regards,
<br/><br/>
AlQuwa Training Team
<br/>
Part of Al Kifah Group

@endcomponent
