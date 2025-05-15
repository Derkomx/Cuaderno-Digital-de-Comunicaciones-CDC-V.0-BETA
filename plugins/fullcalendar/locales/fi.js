/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
FullCalendar.globalLocales.push(function () {
  'use strict';

  var fi = {
    code: 'fi',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Edellinen',
      next: 'Seuraava',
      today: 'Tänään',
      month: 'Kuukausi',
      week: 'Viikko',
      day: 'Päivä',
      list: 'Tapahtumat',
    },
    weekText: 'Vk',
    allDayText: 'Koko päivä',
    moreLinkText: 'lisää',
    noEventsText: 'Ei näytettäviä tapahtumia',
  };

  return fi;

}());
