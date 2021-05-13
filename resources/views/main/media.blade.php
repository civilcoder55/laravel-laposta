@extends('layouts.app')
@section('title', 'Media')
@section('stylesheet')
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
            </div>

        </div>
    </section>
</div>
@endsection

@section('script')
<!-- PAGE SCRIPTS -->
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
        selectedMedia:[],
        mediaUrl: "{{ url('/media/thumb') }}/",
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
        removeMedia:function(id){
            this.userMedia = this.userMedia.filter((obj)=>{return obj.id != id })
            this.$refs.picker.removeFromMultipleSelected(id)
        },
    },
})
</script>
@endsection