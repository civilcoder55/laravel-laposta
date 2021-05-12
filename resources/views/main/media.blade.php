@extends('layouts.app')
@section('title', 'Media')
@section('stylesheet')
<link rel="stylesheet" href="/css/picker.css" />
@endsection
@section('content')
<div class="content-wrapper" id="app">
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col mt-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-baseline">
                            <h3 class="card-title">Media</h3>
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
            </div>

        </div>
    </section>
</div>
@endsection

@section('script')
<!-- PAGE SCRIPTS -->
<script src="/js/picker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const app2 = new Vue({
    el: '#app',
    data: {
        userMedia: @json($userMedia),
        mediaUrl: "{{ url('/media/thumb') }}/",
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
                this.userMedia.push(res.data);
                this.$nextTick(() => {
                    $("#media").imagepicker();
                })
            })
        },
        deleteMedia: function() {
            $("#media").data("picker").select.val().forEach((id) => {
                axios.delete(`/media/delete/${id}`).then((res) => {
                    $(`option[value='${id}']`).remove();
                    $("#media").imagepicker({});
                })
            })
        },
    },
    mounted() {
        $("#media").imagepicker();
    }
})
</script>
@endsection