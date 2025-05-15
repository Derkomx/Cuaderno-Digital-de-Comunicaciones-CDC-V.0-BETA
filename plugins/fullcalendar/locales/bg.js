/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
FullCalendar.globalLocales.push(function () {
  'use strict';

  var bg = {
    code: 'bg',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'назад',
      next: 'напред',
      today: 'днес',
      month: 'Месец',
      week: 'Седмица',
      day: 'Ден',
      list: 'График',
    },
    allDayText: 'Цял ден',
    moreLinkText: function(n) {
      return '+още ' + n
    },
    noEventsText: 'Няма събития за показване',
  };

  return bg;

}());
