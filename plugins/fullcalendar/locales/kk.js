/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
FullCalendar.globalLocales.push(function () {
  'use strict';

  var kk = {
    code: 'kk',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Алдыңғы',
      next: 'Келесі',
      today: 'Бүгін',
      month: 'Ай',
      week: 'Апта',
      day: 'Күн',
      list: 'Күн тәртібі',
    },
    weekText: 'Не',
    allDayText: 'Күні бойы',
    moreLinkText: function(n) {
      return '+ тағы ' + n
    },
    noEventsText: 'Көрсету үшін оқиғалар жоқ',
  };

  return kk;

}());
