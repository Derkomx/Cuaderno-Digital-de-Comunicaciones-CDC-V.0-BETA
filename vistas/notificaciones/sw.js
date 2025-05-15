/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
self.addEventListener("push", (event =>{
    const notif = event.data.json().notification;
    event.waitUntil(self.registration.showNotification(notif.title , {
        body: notif.body,
        icon: notif.imagen,
        data: {
            url: notif.click_action
        }
    }));
}));

self.addEventListener("notificationclick", (event) =>{
    event.waitUntil(clients.openWindow(event.notification.data.url))
});