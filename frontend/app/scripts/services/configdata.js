'use strict';

/**
 * @ngdoc service
 * @name mouselabApp.configData
 * @description
 * # configData
 * Service in the mouselabApp.
 */
angular.module('mouselabApp')
  .service('configData', function (randomizer) {
      // Location will be different for each url
      var experimentLocation  = 'T';

      //var baseApiUrl = 'http://api.stephan-kopietz.dev';
      var baseApiUrl = 'http://apima.stephan-kopietz.de';

      var numberOfRounds = 3;
      var possibleStrategies = ['lex', 'eba', 'eqw', 'wadd'];
      var possibleGroups = ['G1', 'G2', 'G3'];
      var possibleTasks  = ['A',  'B',  'C'];

      var availableTime = {};
      availableTime[possibleTasks[0]] = 11500;
      availableTime[possibleTasks[1]] = 14000;
      availableTime[possibleTasks[2]] = 19500;

      // Define the washing powder rating test cases
      var ratingTestCases = [
        'Preis je Waschgang in Cent',
        'Schmutzentfernung',
        'Fleckenentfernung',
        'Vergrauung',
        'Farberhaltung',
        'Faserschutz',
        'Umwelteigenschaften'
      ];

      var washingPowders = {};

      washingPowders[possibleTasks[0]] = [
        { testCaseRatings : [0.25, '+', '+', 'o', '+', '-', '++'],  rank : 1},
        { testCaseRatings : [0.22, '+', '++', 'o', '--', 'o', '+'], rank : 2},
        { testCaseRatings : [0.20, '-', '+', '+', '-', '--', '+'],  rank : 3},
        { testCaseRatings : [0.31, '++', 'o', '-', 'o', '+', 'o'],  rank : 4}
      ];

      washingPowders[possibleTasks[1]] = [
        { testCaseRatings : [0.25, '+', '+', 'o', '+', '-', '++'],  rank : 1},
        { testCaseRatings : [0.22, '+', '++', 'o', '--', 'o', '+'], rank : 2},
        { testCaseRatings : [0.20, '-', '+', '+', '-', '--', '+'],  rank : 3},
        { testCaseRatings : [0.31, '++', 'o', '-', 'o', '+', 'o'],  rank : 4},
        { testCaseRatings : [0.27, 'o', '+', 'o', 'o', '-', '--'],  rank : 5},
        { testCaseRatings : [0.33, '+', '-', 'o', 'o', '+', 'o'],   rank : 5}
      ];

      washingPowders[possibleTasks[2]] = [
        { testCaseRatings : [0.25, '+', '+', 'o', '+', '-', '++'],  rank : 1},
        { testCaseRatings : [0.22, '+', '++', 'o', '--', 'o', '+'], rank : 2},
        { testCaseRatings : [0.20, '-', '+', '+', '-', '--', '+'],  rank : 3},
        { testCaseRatings : [0.31, '++', 'o', '-', 'o', '+', 'o'],  rank : 4},
        { testCaseRatings : [0.27, 'o', '+', 'o', 'o', '-', '--'],  rank : 5},
        { testCaseRatings : [0.33, '+', '-', 'o', 'o', '+', 'o'],   rank : 6},
        { testCaseRatings : [0.37, '-', 'o', '-', '+', 'o', '++'],  rank : 7},
        { testCaseRatings : [0.28, '+', '-', 'o', '--', 'o', '-'],  rank : 8}
      ];

      // Define the task order (The order how the user will see it)
      var taskOrder = {};
      taskOrder[possibleGroups[0]] = [possibleTasks[0], possibleTasks[1], possibleTasks[2]];
      taskOrder[possibleGroups[1]] = [possibleTasks[1], possibleTasks[2], possibleTasks[0]];
      taskOrder[possibleGroups[2]] = [possibleTasks[2], possibleTasks[0], possibleTasks[1]];


      return {
        getMaxRounds : function () {
          return numberOfRounds;
        },

        getRandomGroup : function () {
          return possibleGroups[randomizer.numberBetween(0, 2)];
        },

        getRandomStrategy : function () {
          return possibleStrategies[randomizer.numberBetween(0,3)];
        },

        getTask : function(group, round) {
          return taskOrder[group][round - 1];
        },

        getAvailableTime : function (task) {
          return availableTime[task];
        },

        getWashingPowders : function (task) {
          return washingPowders[task];
        },

        getExperimentLocation : function () {
          return experimentLocation;
        },

        getRatingTestCases : function () {
          return ratingTestCases;
        },

        getBaseUrl : function () {
            return baseApiUrl;
        }
      };
  });
