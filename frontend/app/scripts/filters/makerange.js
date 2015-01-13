'use strict';

/**
 * @ngdoc filter
 * @name mouselabApp.filter:makeRange
 * @function
 * @description
 * # makeRange
 * Filter in the mouselabApp.
 */
angular.module('mouselabApp')
  .filter('makeRange', function () {
      return function (_input) {
        var lowBound, highBound;

        switch (_input.length) {
          case 1:
            lowBound = 0;
            highBound = parseInt(_input[0]) - 1;
            break;
          case 2:
            lowBound = parseInt(_input[0]);
            highBound = parseInt(_input[1]);
            break;
          default:
            return _input;
        }

        var result = [];
        for (var i = lowBound; i <= highBound; i++)
        {
          result.push(i);
        }

        return result;
      };
  });
