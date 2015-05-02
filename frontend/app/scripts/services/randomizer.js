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

      function createRandomNumber(_low, _high) {
        _high++;
        return Math.floor((Math.random() * (_high - _low) + _low));
      }

      return {
        ////////////////////////////////////////////////////////////////////////////////
        /// Simple helper function to create a random number between _low and _high boundaries
        ////////////////////////////////////////////////////////////////////////////////
        numberBetween: function (_low, _high) {
          return createRandomNumber(_low, _high);
        },

        shuffleArray : function (_array) {

          for (var index = _array.length - 1; index >= 1; index--) {
            var randomIndex = createRandomNumber(0, index);

            // Swap
            var tmp = _array[index];
            _array[index] = _array[randomIndex];
            _array[randomIndex] = tmp;
          }
        }
      }
  });
