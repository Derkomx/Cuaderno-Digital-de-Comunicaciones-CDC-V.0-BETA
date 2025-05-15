/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
FullCalendar.globalLocales.push(function () {
  'use strict';

  var id = {
    code: 'id',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'mundur',
      next: 'maju',
      today: 'hari ini',
      month: 'Bulan',
      week: 'Minggu',
      day: 'Hari',
      list: 'Agenda',
    },
    weekText: 'Mg',
    allDayText: 'Sehari penuh',
    moreLinkText: 'lebih',
    noEventsText: 'Tidak ada acara untuk ditampilkan',
  };

  return id;

}());
