var connected = false;

function disconnect(client, message) {
  if (connected === true) {
    client.disconnect(() => {
      connected = false;

      if (message) {
        console.log(message);
      }
    });
  } else {
    connected = false;
  }
}

export function connect(partnerId, scope, onConnect, onUserAuthenticate, API_LOGIN) {
  if (API_LOGIN === undefined) {
    API_LOGIN = 'https://api.authenteq.com/login';
  }

  if (onConnect === undefined || onUserAuthenticate === undefined) {
    throw Error('Authenteq API::connect - both onConnect and onUserAuthenticate must be specified');
  }

  const socket = new SockJS(API_LOGIN);
  const stompClient = Stomp.over(socket);

  // Don't print debug messages into console
  stompClient.debug = function() {};

  console.log('Authenteq::Connecting..');
  stompClient.connect({}, () => {
    const transportUrl = socket._transport.url; // eslint-disable-line no-underscore-dangle
    const sessionId = /\/([^/]+)\/websocket/.exec(transportUrl)[1];

    stompClient.subscribe(`/queue/${sessionId}.authenticationId`, (response) => {
      console.log('Authenteq::Connected');
      connected = true;

      // Parse response
      const data = JSON.parse(response.body);
      const tokenId = data.id;

      // Handle tokenId to app logic, so app can display a QR code
      onConnect({ tokenId: tokenId, svg: data.svg });

      // Disconnect in 15 minutes automatically
      setTimeout(
        () => disconnect(stompClient, 'Authenteq::Disconnected (15 minutes timeout)'),
        1000 * 60 * 15
      );

      stompClient.subscribe(`/topic/authenticate.${tokenId}`, () => {

        // Handle tokenId back to app logic
        onUserAuthenticate(tokenId);

        // Disconnect. Connection is no longer needed.
        disconnect(stompClient, 'Authenteq::Disconnected (connection no longer needed)');
      });
    });

    stompClient.send('/app/partnerLogin', {}, JSON.stringify({
      partnerId: partnerId,
      scope: scope,
    }));
  });
}
