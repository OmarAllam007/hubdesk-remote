<div class="h-auto bg-white rounded-xl m-5 shadow-md ">
    <div>
        <div class="flex bg-gray-300 p-5 ">
            <h3 class="panel-title">{{t('Letters')}}</h3>

        </div>
        <div class="flex flex-col bg-white p-5 ">
            <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
               href="{{route('letters.letter-group.index')}}">{{t('Groups')}}</a>
        </div>

        <div class="flex flex-col bg-white p-5 ">
            <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
               href="{{route('letters.letter.index')}}">{{t('Letters')}}</a>
        </div>

        <div class="flex flex-col bg-white p-5 ">
            <a class="p-5 hover:bg-gray-200 hover:text-black hover:shadow-md rounded-xl "
               href="{{route('letters.letter-field.index')}}">{{t('Fields')}}</a>
        </div>

    </div>

</div>
