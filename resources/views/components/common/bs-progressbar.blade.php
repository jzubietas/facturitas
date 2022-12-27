<div class="position-relative">
    <div class="progress">

        @if($progress>50)
            <div class="progress-bar bg-warning" role="progressbar"
                 style="width: 50%"
                 aria-valuenow="50"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @else
            <div class="progress-bar bg-danger" role="progressbar"

                 style="width: {{$progress>=40?'40':$progress}}%"
                 style="width: {{$progress}}%"
                 aria-valuenow="{{$progress}}"

                 aria-valuemin="0"
                 aria-valuemax="100"></div>

            @if($progress>40)
                <div class="progress-bar" role="progressbar"
                     style="width: {{$progress>=50?'10':$progress-40}}%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                     aria-valuenow="{{$progress>=50?'10':$progress-40}}"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            @endif
        @endif


        @if($progress>50 )
            <div class="progress-bar bg-warning" role="progressbar"
                 style="width: {{$progress>=70?'20':$progress-50}}%"
                 aria-valuenow="{{$progress>=70?'20':$progress-50}}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @endif
        @if($progress>70)
            <div class="progress-bar" role="progressbar"
                 style="width: {{$progress>=80?'10':$progress-70}}%;
             background: -webkit-linear-gradient( left, #ffc107,{{$progress<80?'#89c11b':'#28a745'}});"
                 aria-valuenow="{{$progress>=80?'10':$progress-70}}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @endif
        @if($progress>80)
            <div class="progress-bar bg-success" role="progressbar"
                 style="width: {{$progress-80}}%;background: #03af03;"
                 aria-valuenow="{{$progress-80}}"
                 aria-valuemin="0" aria-valuemax="100"></div>
        @endif
    </div>
    <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
        {{$slot}}
    </div>
</div>
