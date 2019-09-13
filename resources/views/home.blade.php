@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                User
                </div>
                <div class="card-body">
                    {{Auth::user()->name}}
                    <a class="btn btn-outline-danger float-right" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
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
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <th>Title</th>
                        <th>Genre</th>
                        <th colspan="2">Action</th>
                        <tbody id="animeBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/app.js')}}"></script>
<script>
    $(document).ready(function () {
        axios.get('/api/anime').then(response => {
            let rowData = response.data.data;
            rowData.forEach(function (data) {
                html = "<tr>" +
                        "   <td>"+data['title']+"</td>   " +
                        "   <td>"+data['genre']+"</td>   " +
                        "   <td> <button class='btn btn-outline-dark' data-id='"+data['id']+"' id='editBTN'>Edit</button> </td>   " +
                        "   <td> <button class='btn btn-outline-danger' data-id='"+data['id']+"' id='deleteBTN'>Delete</button> </td>   " +
                        "</tr>";
                $('#animeBody').append(html);
            });
        });
    });
</script>
@endsection
