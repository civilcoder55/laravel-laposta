@extends('layouts.app')

@section('title', 'Create Post')

@section('stylesheet')
<link rel="stylesheet" href="/css/picker.css" />
<link rel="stylesheet" href="/css/tempusdominus.css">
@endsection

@section('content')
<div class="content-wrapper" id="app">
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
                <div class="col-sm-6 mt-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-baseline">
                            <h3 class="card-title">Media Library</h3>
                            <div style="margin-left: auto">
                                <label @click="deleteMedia" class="btn btn-sm btn-primary"> <i
                                        class="nav-icon fas fa-trash-alt"></i></label>
                                <label for="file" class="btn btn-sm btn-primary"> <i class="nav-icon fas fa-upload"></i>
                                    upload </label>
                                <input id="file" type="file" name="media" @change="uploadMedia" style="display: none" />
                            </div>
                        </div>
                        <div class="card-body">
                            <select id="media" multiple="multiple" class="image-picker row">
                                <option v-for="media in userMedia" :data-img-src="mediaUrl + media.name"
                                    :value="media.id"></option>
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
                                        <div class="dropdown-menu dropdown-menu-right" @click="accountsMenu">
                                            <div v-for="account in userAccounts">
                                                <a class="dropdown-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox"
                                                            v-model="post.accounts" :id="account.id"
                                                            :value="account.id">
                                                        <label :for="account.id"
                                                            class="custom-control-label">@{{ account.name }}
                                                            [@{{ account.type }}] </label>
                                                    </div>
                                                </a>

                                                <div class="dropdown-divider"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Post</label>
                                <textarea class="form-control" rows="3" v-model="post.message"
                                    placeholder="Type what you like..." required></textarea>
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
                            <form ref="form" action="{{ route('posts.store') }}" method="post">
                                @csrf
                                <textarea v-model="post.message" name="message" hidden></textarea>
                                <input v-for="el in post.media" type="hidden" name="media[]" :value="el">
                                <input v-for="el in post.accounts" type="hidden" name="accounts[]" :value="el">
                                <input type="hidden" name="schedule_date" v-model="post.schedule_date">
                                <input type="hidden" name="draft" v-model="post.draft">
                                <button class="btn btn-primary" type="button" @click="submit(0)">Schedule</button>
                                <button class="btn btn-primary" type="button" @click="submit(1)">Save as draft</button>
                            </form>
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
<script src="/js/moment.js"></script>
<script src="/js/tempusdominus.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const app2 = new Vue({
    el: '#app',
    data: {
        userMedia: @json($userMedia),
        mediaUrl: "{{ url('/media/thumb') }}/",
        userAccounts: @json($userAccounts),
        post: {
            message: "",
            media: [],
            schedule_date: "",
            accounts: [],
            draft: 1,
        }
    },
    computed: {
        selectedImages: function() {
            return $("#media").data("picker").select.val()
        }
    },
    methods: {
        uploadMedia: function(e) {
            let data = new FormData();
            data.append('media', e.target.files[0]);
            axios.post('/media', data, {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }).then((res) => {
                console.log(res.data)
                this.userMedia.push(res.data);
                this.$nextTick(() => {
                    $("#media").imagepicker();
                })
            })
        },
        deleteMedia: function() {
            $("#media").data("picker").select.val().forEach((id) => {
                axios.delete(`/media/delete/${id}`).then((res) => {
                    if (res.data.success) {
                        $(`option[value='${id}']`).remove();
                        $("#media").imagepicker({});
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'alert',
                            body: res.data.message
                        })
                    }
                })
            })
        },
        accountsMenu: function(e) {
            e.stopPropagation();
        },
        submit: function(draft) {
            this.post.draft = draft
            this.post.media = $("#media").data("picker").select.val()
            this.post.schedule_date = $("#datetime").val()
            this.$nextTick(() => {
                this.$refs.form.submit()
            })
        }
    },
    mounted() {
        $("#media").imagepicker();
        $('#datepicker').datetimepicker({
            minDate: new Date()
        });
    }
})
</script>
@endsection