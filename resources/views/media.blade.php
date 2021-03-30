@extends('layouts.app')
@section('title', 'Media')
@section('stylesheet')
    <link rel="stylesheet" href="/css/picker.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col mt-4">
                        <div class="card">
                            <div class="card-header d-flex align-items-baseline">
                                <h3 class="card-title">Media</h3>
                                <div style="margin-left: auto">
                                    <label id="delete" class="btn btn-sm btn-primary"> <i
                                            class="nav-icon fas fa-trash-alt"></i></label>
                                    <label for="file" class="btn btn-sm btn-primary"> <i class="nav-icon fas fa-upload"></i>
                                        upload </label>
                                    <input id="file" type="file" name="media" style="display: none" />
                                </div>
                            </div>
                            <div class="card-body">
                                <select id="media" multiple="multiple" class="image-picker row">

                                    @foreach ($user_media as $media)

                                        <option data-img-src="{{ route('media.show.thumb', $media->name) }}"
                                            value="{{ $media->id }}"></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@section('script')
    <!-- PAGE SCRIPTS -->
    <script src="/js/picker.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        document.getElementById("file").onchange = function(e) {
            var data = new FormData();
            data.append('media', e.target.files[0])
            $.ajax({
                url: "/media",
                type: "POST",
                data: data,
                contentType: false,
                processData: false,
                success: function(response) {
                    option = document.createElement("option");
                    option.setAttribute("data-img-src", `{{ url('/') }}/media/thumb/${response.name}`);
                    option.setAttribute("value", `${response.id}`);
                    $("select").append(option);
                    $("select").imagepicker();
                },
            })
        };
        document.getElementById("delete").onclick = function() {
            $("select").data("picker").select.val().forEach((id) => {
                $.ajax({
                    url: `/media/delete/${id}`,
                    type: "POST",
                    success: function(response) {
                        $(`option[value='${id}']`).remove();
                        $("select").imagepicker({});
                    },
                })


            })

        }



        $(function() {
            $("#media").imagepicker({});
        });

    </script>
@endsection
