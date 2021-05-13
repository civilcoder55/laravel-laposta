@extends('layouts.app')

@section('title', 'Edit Post')

@section('stylesheet')
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
                                <input id="file" type="file" multiple @change="uploadMedia" style="display: none"
                                    accept=".png, .jpg, .jpeg" />
                            </div>
                        </div>
                        <div class="card-body">
                            <vue-select-image ref='picker' :data-images="userMedia" :is-multiple="true"
                                :selected-images="selectedMedia" @onselectmultipleimage="selectedImages">
                            </vue-select-image>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-baseline">
                            <h3 class="card-title">{{ 'Edit Post' }}</h3>
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
                                                            v-model="post.accounts" :id="'ach#'+account.id"
                                                            :value="account.id">
                                                        <label :for="'ach#'+account.id"
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
                            <form ref="form" action="{{ route('posts.update',$post->id) }}" method="post">
                                @csrf
                                <textarea v-model="post.message" name="message" hidden></textarea>
                                <input v-for="el in selectedMedia" type="hidden" name="media[]" :value="el.id">
                                <input v-for="el in post.accounts" type="hidden" name="accounts[]" :value="el">
                                <input type="hidden" name="schedule_date" v-model="post.schedule_date">
                                <input type="hidden" name="draft" v-model="post.draft">
                                <button class="btn btn-primary" type="button"
                                    @click="submit(0)">@{{ post.status == 'failed' ? 'Re-Schedule' : 'Schedule'}}</button>
                                <button class="btn btn-primary" type="button" @click="submit(1)">Draft</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bullhorn"></i>
                                Logs
                            </h3>
                        </div>
                        <div class="card-body overflow-auto" style="height: 300px">
                            <div v-for="log in post.logs" class="callout" :class="'callout-' + log.status">
                                <h5>@{{ log.status }}</h5>
                                <p>@{{ log.message }}</p>
                            </div>
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
<script src="/js/moment.js"></script>
<script src="/js/tempusdominus.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="/js/selectImage.js"></script>
<script>
    const app2 = new Vue({
    el: '#app',
    components: {
        VueSelectImage:
        vue_select_image.a
    },
    data: {
        userMedia: @json($userMedia),
        selectedMedia:@json($post->media_ids),
        mediaUrl: "{{ url('/media/thumb') }}/",
        userAccounts: @json($userAccounts),
        post: {
            message: "{{ $post->message }}",
            schedule_date: "",
            accounts: @json($post->accounts_ids),
            draft: 1,
            logs: @json($post->logs),
        }
    },
    methods: {
        selectedImages: function(v) {
            this.selectedMedia = v
        },
        uploadMedia: function(e) {
            let data = new FormData();
            for( var i = 0; i < e.target.files.length; i++ ){
                data.append('media[' + i + ']', e.target.files[i]);
            }
            axios.post('/media', data, {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }).then((res) => {
                this.userMedia = this.userMedia.concat(res.data.media);
            })
        },
        deleteMedia: function() {
            this.selectedMedia.forEach((media) => {
                axios.delete(`/media/delete/${media.id}`).then((res) => {
                    if (res.data.success) {
                       this.removeMedia(media.id)
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
            this.post.schedule_date = $("#datetime").val()
            this.$nextTick(() => {
                this.$refs.form.submit()
            })
        }
    },
    mounted() {
        try {
            $('#datepicker').datetimepicker({
                minDate: new Date(),
                defaultDate: "{{ $post->schedule_date }}"
            });
        } catch (error) {
            $('#datepicker').datetimepicker({
                minDate: new Date(),
            });
        }
    }
})
</script>
@endsection