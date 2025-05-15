/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
FullCalendar.globalLocales.push(function () {
  'use strict';

  var is = {
    code: 'is',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Fyrri',
      next: 'Næsti',
      today: 'Í dag',
      month: 'Mánuður',
      week: 'Vika',
      day: 'Dagur',
      list: 'Dagskrá',
    },
    weekText: 'Vika',
    allDayText: 'Allan daginn',
    moreLinkText: 'meira',
    noEventsText: 'Engir viðburðir til að sýna',
  };

  return is;

}());
