<div class="position-relative">
    <div class="progress">

        @if($progress>=80)
            <div class="progress-bar bg-success" role="progressbar"
                 style="width: {{$progress}}%;background: #03af03;"
                 aria-valuenow="{{$progress}}"
                 aria-valuemin="0" aria-valuemax="100"></div>
        @elseif($progress>70)
            <div class="progress-bar bg-warning" role="progressbar"
                 style="width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
.

            <div class="progress-bar" role="progressbar"
                 style="width: {{$progress-70}}%;
             background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                 aria-valuenow="{{$progress-70}}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @elseif($progress>50)
            <div class="progress-bar bg-warning" role="progressbar"
                 style="width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @elseif($progress>40)
            <div class="progress-bar bg-danger" role="progressbar"
                 style="width: 40%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>

            <div class="progress-bar" role="progressbar"
                 style="width: {{$progress-40}}%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="{{$progress-40}}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @else
            <div class="progress-bar bg-danger" role="progressbar"
                 style="width: {{$progress}}%"
                 aria-valuenow="{{$progress}}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        @endif
    </div>
    <div class="position-absolute w-100 text-center" style="top: 0;font-size: 12px;">
        {{$slot}}
    </div>
</div>
