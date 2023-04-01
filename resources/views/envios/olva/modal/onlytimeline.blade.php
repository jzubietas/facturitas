@foreach ($grupo_courier_data as $item)
    <section class="timeline_area section_padding_130">
        <div class="container p-2">
            <div class="row">
                <div class="col-12">
                    <div class="apland-timeline-area row">

                        <div class="single-timeline-area col-lg-12">
                            <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                <p>{{$item->nombre_sede}}</p>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="single-timeline-content wow fadeInLeft position-relative" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div class="timeline-icon position-absolute" style="top: -12px;left: -9px;">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text w-100">
                                            {{--<a target="_blank" href="$#"><img src="" class="w-100"></a>--}}
                                            @if($item->estado_tracking=='CONFIRMACION EN TIENDA')
                                                <span class="text-warning">{{$item->estado_tracking}}</span>
                                                @else
                                                {{$item->estado_tracking}}
                                                @endif

                                            <br>
                                            {{$item->obs}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endforeach
