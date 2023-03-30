@foreach ($grupo_courier_data as $item)
    <section class="timeline_area section_padding_130">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="apland-timeline-area">

                        <div class="single-timeline-area">
                            <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                <p>DATO 1</p>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-9 col-lg-6">
                                    <div class="single-timeline-content wow fadeInLeft position-relative" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                        <div class="timeline-icon position-absolute" style="top: -12px;left: -9px;">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </div>
                                        <div class="timeline-text w-100">
                                            {{--<a target="_blank" href="$#"><img src="" class="w-100"></a>--}}
                                            ACA IMAGEN
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
