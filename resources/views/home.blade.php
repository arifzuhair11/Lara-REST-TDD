@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                User
                </div>
                <div class="card-body" id="auth">
                    <form method="POST" id="logoutForm" style="display: none">
                        @csrf
                    </form>
                    <button class="btn btn-outline-danger float-right" onclick="logout()">Logout</button>
                </div>
            </div>
            <div class="card  mt-3">
                <div class="card-header">Other Action</div>
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Anime List
                    <button class="btn btn-outline-primary float-right" id="addAnime" onclick="parseAdd(this)" data-toggle="modal" data-target="#baseModal" data-title="Add Anime">Add Anime</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Num. of Episodes</th>
                        <th colspan="2">Action</th>
                        <tbody id="animeBody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="baseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modalForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input type="text" class="form-control" name="title" id="titleValue" value="">
                        <input type="hidden" id="animeID" value="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Genre</label>
                        <input type="text" class="form-control" name="genre" id="genreValue" value="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Num. of Episode</label>
                        <input type="text" class="form-control" name="episodes" id="episodeValue" value="">
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-success" ></button>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="{{asset('js/app.js')}}"></script>
<script>
    $(document).ready(function () {
            setUser();
            animeIndex();
            $('#baseModal').on('hide.bs.modal', function () {
                $(this).find('input').val('');
                location.reload();
            });
    });
    function parseAdd(element){
        title = element.getAttribute('data-title');
        $('#baseModal').find('.modal-title').text(title);
        $('#baseModal').find('.btn').text('Add');
        $('#baseModal').find('.btn').attr('id', 'addBTN');
        $('#baseModal').find('.btn').attr('onclick', 'addProcess()');
    }
    function setHeaders(){
        authToken = localStorage.getItem('token');
        authHeader = {'headers' : { 'Accept' : 'application/json', 'Authorization' : 'Bearer '+authToken}};
        return authHeader;
    }
    function setUser() {
        user = JSON.parse(localStorage.getItem('user'));
        $('#navbarDropdown').text(user.name);
    }
    function logout(){
     event.preventDefault();
     logoutForm = $('#logoutForm').serialize();
     headers = setHeaders();
     message = "You will be logged out. Continue?";
     if(confirm(message)){
         axios.post('/api/myLogout',logoutForm, headers)
             .then(response => {
                 window.location.href = "/login";
             })
             .catch(error => {
                 console.log(error);
             })
     }

    }
    function addProcess(){
        event.preventDefault();
        header = setHeaders();
        data = $('#modalForm').serialize();
        message = "The information will be saved. Continue?";
        if(confirm(message)){
            axios.post('/api/anime', data, header)
                .then(response=>{
                    alert(response['data']['message']);
                })
                .catch(error => {
                    console.log(error);
                })
        }
    }
    function animeIndex() {
        header = setHeaders();
        axios.get('/api/anime', header)
            .then(response => {
            let rowData = response.data.data;
            rowData.forEach(function (data) {
                html = "<tr>" +
                    "   <td>"+data['title']+"</td>   " +
                    "   <td>"+data['genre']+"</td>   " +
                    "   <td>"+data['episode']+"</td>   " +
                    "   <td> <button class='btn btn-outline-dark' data-id='"+data['id']+"' id='edit' onclick='parseEdit(this)' data-toggle='modal' data-title='Edit Anime' data-target='#baseModal'>Edit</button> </td>   " +
                    "   <td> <button class='btn btn-outline-danger' data-id='"+data['id']+"' id='delete' onclick='deleteAnime(this)'>Delete</button> </td>   " +
                    "</tr>";
                $('#animeBody').append(html);
            });
        });
    }
    function deleteAnime(element) {
        header = setHeaders();
        message = "Anime data will be deleted. Continue?";
        id = element.getAttribute('data-id');
        if(confirm(message)){
            axios.delete('/api/anime/'+id, header)
                .then(response => {
                    alert(response['data']['message']);
                    location.reload();
                }).catch(error => {
                console.log(error)
            });
        }
    }
    function parseEdit(element) {
        header = setHeaders();
        id = element.getAttribute('data-id');
        title = element.getAttribute('data-title');
        axios.get('/api/anime/'+id, header)
            .then(response => {
                $('#baseModal').find('.modal-title').text(title);
                $('#baseModal').find('.btn').text('Update');
                $('#baseModal').find('.btn').attr('onclick', 'editProcess()');
                $('#baseModal').find('#titleValue').val(response['data']['anime']['title']);
                $('#baseModal').find('#genreValue').val(response['data']['anime']['genre']);
                $('#baseModal').find('#episodeValue').val(response['data']['anime']['runtime']);
                $('#baseModal').find('#animeID').val(response['data']['anime']['id']);
            })
            .catch(error => {
                console.log(error)
            })
    }
    function editProcess() {
        event.preventDefault();
        header = setHeaders();
        id = $('#animeID').val();
        data = $('#modalForm').serialize();
        message = "Anime information will be updated. Continue?";
        if(confirm(message)){
            axios.put('/api/anime/'+id, data, header)
                .then(response => {
                    alert(response['data']['message']);
                })
                .catch(error => {
                    console.log(error);
                })
        }
    }

</script>
@endsection
