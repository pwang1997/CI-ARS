// $(document).ready(() => {
//     get_session().then((user) => {
//         user = JSON.parse(user);
//         if (window.WebSocket) {
//             websocket = new WebSocket(wsurl);
//             sessionStorage.setItem("ws_instance", websocket);

//             websocket.onopen = function(evevt) {
//                 console.log("Connected to WebSocket server.");
//                 msg = {
//                     'cmd': "connect",
//                     'from_id': user.id,
//                     'username': user.username,
//                     'role': user.role
//                 };
//                 websocket.send(JSON.stringify(msg));
//             }
//         } else {
//             alert('this browser does not support websocket, please use Google Chrome or FireFox');
//         }
//     });
// })