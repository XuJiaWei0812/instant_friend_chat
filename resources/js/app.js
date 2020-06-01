require('./bootstrap');

window.Echo.channel('create-friendMessage')
    .listen('createFriendMessage', (e) => {
        console.log(e);
    });
