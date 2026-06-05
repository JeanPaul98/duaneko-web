import React from 'react';
import socketio from 'socket.io-client';

// ⚠️  Update this URL to your live server address before deploying.
//     The ngrok address below expires quickly — replace it with your actual server.
export const serverAddress = 'https://6ab8-34-73-204-91.ngrok.io/';
// export const serverAddress = 'http://34.73.204.91/';
// export const serverAddress = 'http://192.168.2.140:8080/';

export const socket = socketio(serverAddress);
export const SocketContext = React.createContext(socket);
