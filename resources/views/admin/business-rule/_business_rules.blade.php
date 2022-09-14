<section class="w-full">
    <h4 class="font-bold">Business Rules</h4>
    @foreach($businessRules as $businessRule)
        <div class="w-full bg-white p-5 my-2 rounded-xl shadow-md">
            <div class="flex">
                <a href="{{ route('admin.business-rule.edit', $businessRule) }}" class="w-full h-full">
                    {{$businessRule->name}}
                </a>
            </div>
        </div>
    @endforeach

</section>