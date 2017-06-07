function notifyUser(user) {
  browser.notifications.create({
    "type": "basic",
    "title": "Laboratorio de RIA",
    "message": `Hola ${user.name}`
  });
}

function logError(error) {
  console.error(`Error: ${error}`);
}

/**
When the button's clicked:
- get an access token using the identity API
- use it to get the user's info
- show a notification containing some of it
*/
browser.browserAction.onClicked.addListener(() => {
   browser.tabs.create({
     "url": "https://ria-demianpizzo.c9users.io/GoogleLogin.php"
   });
});
