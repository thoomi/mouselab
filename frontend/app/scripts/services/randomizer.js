'use strict';

/**
 * @ngdoc service
 * @name mouselabApp.randomizer
 * @description
 * # randomizer
 * Service in the mouselabApp.
 */
angular.module('mouselabApp')
  .service('randomizer', function () {
      return {
        ////////////////////////////////////////////////////////////////////////////////
        /// Simple helper function to create a random number between _low and _high boundaries
        ////////////////////////////////////////////////////////////////////////////////
        numberBetween : function createRandomNumber(_low, _high) {
            _high++;
            return Math.floor((Math.random() * (_high - _low) + _low));
          }
      };
  });
