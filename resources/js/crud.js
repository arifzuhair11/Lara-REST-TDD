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
                window.location = response['data']['redirect'];
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
            $('#baseModal').find('#episodeValue').val(response['data']['anime']['episode']);
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
