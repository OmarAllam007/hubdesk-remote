@component('mail::message')
# The ticket #{{$ticket['id']}} has a new reply.

<div style="font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial,sans-serif">
Ticket ID: #{{link_to_route('ticket.show', $ticket['id'], $ticket['id'])}}<br/>
By: {{$reply->user->name}}<br/>
At: {{$reply->created_at->format('d/m/Y H:i')}}<br/>
Content:<br/><br />
<div style="text-align: center;direction: rtl">
<p style="direction: rtl; text-align: center">عزيزي مقدم / ـة الطلب</p>
<p style="direction: rtl; text-align: center">تحية طيبة وبعد ،</p>
<p style="direction: rtl; text-align: center">
يرجى تحويل رسوم تصديق الخطاب بمبلغ : 45 ريال وهي موزعة كالتالي ، رسوم الخدمة : 10 ريال ، رسوم تصديق الغرفة التجارية : 35 ريال ، على رقم الحساب البنكي رقم :
</p>
<p>SA30 4500 0000 0480 7025 4013</p>
<p>، أسم المستفيد / شركة الكفاح القابضة ، بنك ساب ومن ثم تزويدنا بنسخة من إيصال التحويل حتى نتمكن من إكمال إجراءات عملية التصديق .</p>



<p style="direction: rtl; text-align: center">
شكراً
</p>

<p style="text-align: center">
Dear Requester,
</p>
<p style="text-align: center">Greeting,</p>



<p style="text-align: center">Please transfer the attestation fees : 45 riyals, distributed as follows, service fees: 10 S.R., Chamber of Commerce attestation fees: 35 S.R., to be transferred to the bank account number:</p>
<p>SA30 4500 0000 0480 7025 4013</p>
<p>, AlKifah Holding Company, SABB Bank, then provide us with a copy of the transfer receipt, based on that we can complete the attestation process.</p>


<p style="text-align: center">Thank you</p>
</div>

<br><br>
</div>
<div style="padding-left: 15px;">
@component('mail::button', ['url' => route('ticket.show',$ticket['id'])])
<b class="center-block">Display Ticket</b>
@endcomponent
<div class="alert alert-info" role="alert">
<p  style="background-color:#f6f7d2; border-radius: 5px;font-size: small; padding: 10px;margin: 10px;text-align: center">
<strong>
{{t('Please don\'t reply on this email')}}
</strong>
</p>
</div>
</div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent