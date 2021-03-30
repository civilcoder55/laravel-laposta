@extends('layouts.app')

@section('title', 'Edit Post')
@section('stylesheet')
    <link rel="stylesheet" href="/css/picker.css" />
    <link rel="stylesheet" href="/css/tempusdominus.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                @if (session('status'))
                    <div class="row">
                        <div class="col mt-3">
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="row">
                        <div class="col mt-3">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    @if (!$userAccounts->isEmpty())
                        <div class="col-sm-6 mt-5">
                            <div class="card">
                                <div class="card-header d-flex align-items-baseline">
                                    <h3 class="card-title">Media</h3>
                                    <div style="margin-left: auto">
                                        <label id="delete" class="btn btn-sm btn-primary"> <i
                                                class="nav-icon fas fa-trash-alt"></i></label>
                                        <label for="file" class="btn btn-sm btn-primary"> <i
                                                class="nav-icon fas fa-upload"></i>
                                            upload </label>
                                        <input id="file" type="file" name="media" style="display: none" />
                                    </div>
                                </div>
                                <div class="card-body">
                                    <select id="media" multiple="multiple" class="image-picker row">
                                        @foreach ($userMedia as $media)
                                            <option data-img-src="{{ route('media.show.thumb', $media->name) }}"
                                                value="{{ $media->id }}" @if (in_array($media->id, $postMedia)) selected="selected" @endif></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-5">
                            <div class="card">
                                <div class="card-header d-flex align-items-baseline">
                                    <h3 class="card-title">New Post</h3>
                                    <div class="row d-flex align-items-baseline" style="margin-left: auto">
                                        <div class="col">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Accounts
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" id="accountsMenu">

                                                    @foreach ($userAccounts as $account)

                                                        @if ($account->type == 'Account')
                                                            @continue
                                                        @endif
                                                        <a class="dropdown-item">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" name="account[]"
                                                                    id="ch_{{ $account->id }}"
                                                                    class="custom-control-input"
                                                                    value="{{ $account->id }}" @if (in_array($account->id, $postAccounts)) checked @endif>
                                                                <label class="custom-control-label"
                                                                    for="ch_{{ $account->id }}">{{ $account->name . ' [' . $account->type . ']' }}</label>
                                                            </div>
                                                        </a>

                                                        <div class="dropdown-divider"></div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Post</label>
                                        <textarea class="form-control" rows="3" id='message' name='message'
                                            placeholder="Type what you like...">{{ $post->message }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Schedule</label>
                                        <div class="input-group date" id="datepicker" data-target-input="nearest">
                                            <input type="text" id='datetime' name='datetime'
                                                class="form-control datetimepicker-input" data-target="#datepicker"
                                                placeholder="Please select a date and time to schedule " />
                                            <div class="input-group-append" data-target="#datepicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <form id='scheduleForm' action="{{ route('posts.update', $post->id) }}" method="post">
                                        @csrf
                                    </form>
                                    <button type="submit" id='schedule' class="btn btn-primary">Schedule</button>
                                    <button type="submit" id='saveDraft' class="btn btn-primary">Save as draft</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col mt-3">
                            <div class="alert alert-warning" role="alert">
                                Please add at least one account from accounts page first ...
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </section>
    </div>
@endsection

@section('script')
    <!-- PAGE SCRIPTS -->
    <script src="/js/picker.js"></script>
    <script src="/js/moment.js"></script>
    <script src="/js/tempusdominus.js"></script>
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
                    option.setAttribute("data-img-src",
                        `{{ url('/') }}/media/thumb/${response.name}`);
                    option.setAttribute("value", `${response.id}`);
                    $("#media").append(option);
                    $("#media").imagepicker();
                },
            })
        };
        document.getElementById("delete").onclick = function() {
            $("#media").data("picker").select.val().forEach((id) => {
                $.ajax({
                    url: `/media/delete/${id}`,
                    type: "POST",
                    success: function(response) {
                        $(`option[value='${id}']`).remove();
                        $("#media").imagepicker({});
                    },
                })


            })

        }


        function sumbitPost(draft) {
            var media = $("#media").data("picker").select.val()
            var schedule_date = $("#datetime").val()
            $("#scheduleForm").append($('#message').clone()).hide();
            $("#scheduleForm").append($('#link').clone()).hide();
            $("#scheduleForm").append($(`<input type="hidden" name="schedule_date" value="${schedule_date}" >`));


            var selectedAccount = $("input:checkbox:checked").map(function() {
                id = $(this).val();
                $("#scheduleForm").append($(`<input type="hidden" name='accounts[]' value='${id}' />`))
            })


            $("#media").data("picker").select.val().forEach((id) => {
                $("#scheduleForm").append($(
                    `<input type="hidden" name='media[]' value='${id}' />`))
            })
            $("#scheduleForm").append($(`<input type="hidden" name="draft" value=${draft} >`))
            $("#scheduleForm").submit()
        }
        document.getElementById("schedule").onclick = function() {
            sumbitPost(0)
        }
        document.getElementById("saveDraft").onclick = function() {
            sumbitPost(1)
        }
        $(function() {
            $("#accountsMenu").click(function(e) {
                e.stopPropagation();
            })
            $("#media").imagepicker();
        });
        $('#datepicker').datetimepicker({
            minDate: new Date(),
            defaultDate: "{{ $post->schedule_date }}"
        });

    </script>
@endsection
